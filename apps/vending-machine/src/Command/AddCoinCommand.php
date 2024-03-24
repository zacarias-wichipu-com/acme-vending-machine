<?php

declare(strict_types=1);

namespace Acme\Ui\Cli\Command;

use Acme\Coin\Domain\AmountInCents;
use Acme\Shared\Domain\CurrencyUtils;
use Acme\Shared\Infrastructure\Symfony\Console\Command\BusCommand;
use Acme\VendingMachine\Application\AddCoinToCustomerWalletCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

#[AsCommand(name: 'coin:add')]
final class AddCoinCommand extends Command
{
    public function __construct(
        private readonly BusCommand $bus
    ) {
        parent::__construct('coin:add');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper(name: 'question');
        $choicesAllowed = array_map(
            callback: static fn(
                AmountInCents $amountInCents
            ): string => CurrencyUtils::toDecimalString($amountInCents->value),
            array: AmountInCents::cases(),
        );
        $choicesAllowed[] = 'Insert none';
        $question = new ChoiceQuestion('<fg=blue>Insert a coin: </>', $choicesAllowed);
        $choice = $helper->ask(input: $input, output: $output, question: $question);
        if ($choice === 'Insert none') {
            return Command::SUCCESS;
        }
        $amount = array_reduce(
            array: AmountInCents::cases(),
            callback: fn(?int $coinAmount, AmountInCents $amountInCents): ?int => is_null($coinAmount) && CurrencyUtils::toDecimalString($amountInCents->value) === $choice ? $amountInCents->value : $coinAmount,
        );
        $this->bus->dispatch(command: new AddCoinToCustomerWalletCommand(amount: $amount));
        return Command::SUCCESS;
    }
}
