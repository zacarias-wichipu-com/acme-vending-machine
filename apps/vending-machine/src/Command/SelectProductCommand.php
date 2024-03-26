<?php

declare(strict_types=1);

namespace Acme\Ui\Cli\Command;

use Acme\Shared\Domain\CurrencyUtils;
use Acme\Shared\Infrastructure\Symfony\Console\Command\BusCommand;
use Acme\VendingMachine\Application\Buy\BuyProductCommand;
use Acme\VendingMachine\Application\Buy\RefundBuyExchangeCommand;
use Acme\VendingMachine\Application\Get\GetVendingMachineQuery;
use Acme\VendingMachine\Application\VendingMachineResponse;
use DomainException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'customer:product:select')]
final class SelectProductCommand extends Command
{
    public function __construct(
        private readonly BusCommand $bus
    ) {
        parent::__construct('customer:product:select');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->text([
            '<fg=bright-magenta>--> Selecting product to buy.</>',
        ]);
        /** @var VendingMachineResponse $vendingMachineResponse */
        $vendingMachineResponse = $this->bus->ask(new GetVendingMachineQuery());
        $choicesOptionAllowed = array_map(
            callback: static fn(array $rack): string => sprintf('%1$s (price: %2$s, stock: %3$d)', $rack['product'], CurrencyUtils::toDecimalString($rack['price']), $rack['quantity']),
            array: $vendingMachineResponse->store(),
        );
        $choicesOptionAllowed[] = 'Select none';
        $choicesOptionAllowed = array_combine(
            keys: array_slice(
                array: range('a', 'z'),
                offset: 0,
                length: count($choicesOptionAllowed),
            ),
            values: $choicesOptionAllowed
        );
        $choicesValueAllowed = array_map(
            callback: static fn(array $rack): string => $rack['product'],
            array: $vendingMachineResponse->store(),
        );
        $choicesValueAllowed = array_combine(
            keys: array_slice(
                array: range('a', 'z'),
                offset: 0,
                length: count($choicesValueAllowed),
            ),
            values: $choicesValueAllowed
        );
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper(name: 'question');
        $question = new ChoiceQuestion('<fg=blue>Select a product: </>', $choicesOptionAllowed);
        $choiceKey = $helper->ask(input: $input, output: $output, question: $question);
        $choice = $choicesOptionAllowed[$choiceKey];
        if ($choice === 'Select none') {
            $io->text([
                '<fg=bright-green>--> --> Select none.</>'
            ]);
            return Command::SUCCESS;
        }
        try {
            $this->bus->dispatch(command: new BuyProductCommand(product: $choicesValueAllowed[$choiceKey]));
        } catch (DomainException $exception) {
            $io->text([
                sprintf('<fg=bright-red>-->--> %1$s</>', $exception->getMessage())
            ]);
            return Command::SUCCESS;
        }

        /** @var VendingMachineResponse $vendingMachineResponse */
        $vendingMachineResponse = $this->bus->ask(new GetVendingMachineQuery());
        $refundedAmount = $vendingMachineResponse->refundAmount();
        $refundedCoins = array_reduce(
            array: $vendingMachineResponse->refundCoins(),
            callback: static fn(array $carry, array $coins): array => [
                ...$carry, ...array_fill(0, $coins['quantity'], CurrencyUtils::toDecimalString($coins['coin'])),
            ],
            initial: []
        );
        $io->text([
            sprintf(
                '<fg=bright-green>-->--> Exchange refunded %1$s (coins: %2$s).</>',
                CurrencyUtils::toDecimalString($refundedAmount),
                implode(
                    ', ',
                    $refundedCoins
                )
            ),
        ]);

    exit();
        $this->bus->dispatch(command: new RefundBuyExchangeCommand());

        $io->text([
            sprintf('<fg=bright-green>--> --> Inserted a %1$s coin.</>', $choice),
        ]);
        return Command::SUCCESS;
    }
}
