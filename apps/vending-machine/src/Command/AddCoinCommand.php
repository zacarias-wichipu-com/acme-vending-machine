<?php

declare(strict_types=1);

namespace Acme\Ui\Cli\Command;

use Acme\Coin\Domain\AmountInCents;
use Acme\Shared\Domain\CurrencyUtils;
use Acme\Shared\Infrastructure\Symfony\Console\Command\BusCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $question = new Question(question: '<fg=blue>Insert a coin (indicate the value with decimals separated by a decimal point, e.g. 0.05, 0.10,...): </>' );
        $amount = $helper->ask(input: $input, output: $output, question: $question);
        if (filter_var(value: $amount, filter: FILTER_VALIDATE_FLOAT) === false) {
            $amountsAllowed = array_map(
                callback: static fn (AmountInCents $amountInCents): string => CurrencyUtils::toDecimalString($amountInCents->value),
                array: AmountInCents::cases(),
            );
            $io = new SymfonyStyle($input, $output);
            $io->text([
                sprintf('<fg=red>⚠️ "%1$s" coin amount isn\'t a valid coin.</>', $amount),
                sprintf('Only %1$s amounts are allowed.', implode(', ', $amountsAllowed))
            ]);
            return Command::INVALID;
        }
//        $this->bus->dispatch(command: new AddCoinToCustomerWalletCommand());
        return Command::SUCCESS;
    }
}
