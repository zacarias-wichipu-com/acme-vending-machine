<?php

declare(strict_types=1);

namespace Acme\Ui\Cli\Command;

use Acme\Coin\Domain\AmountInCents;
use Acme\Shared\Domain\CurrencyUtils;
use Acme\Shared\Infrastructure\Symfony\Console\Command\BusCommand;
use Acme\VendingMachine\Application\AddCoinToCustomerWallet\AddCoinToCustomerWalletCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'customer:coin:add')]
final class AddCustomerCoinCommand extends Command
{
    public function __construct(
        private readonly BusCommand $bus
    ) {
        parent::__construct('customer:coin:add');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->text([
            '<fg=bright-magenta>--> Add customer coin.</>',
        ]);
        $choicesAllowed = array_map(
            callback: static fn(
                AmountInCents $amountInCents
            ): string => CurrencyUtils::toDecimalString($amountInCents->value),
            array: AmountInCents::cases(),
        );
        $choicesAllowed[] = 'Insert none';
        $choicesAllowed = array_combine(['a', 'b', 'c', 'd', 'e',], $choicesAllowed);
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper(name: 'question');
        $question = new ChoiceQuestion('<fg=blue>Insert a coin: </>', $choicesAllowed);
        $choiceKey = $helper->ask(input: $input, output: $output, question: $question);
        $choice = $choicesAllowed[$choiceKey];
        if ($choice === 'Insert none') {
            $io->text([
                '<fg=bright-green>--> --> Insert none.</>'
            ]);
            return Command::SUCCESS;
        }
        $amount = array_reduce(
            array: AmountInCents::cases(),
            callback: fn(
                ?int $coinAmount,
                AmountInCents $amountInCents
            ): ?int => is_null($coinAmount) && CurrencyUtils::toDecimalString($amountInCents->value) === $choice ? $amountInCents->value : $coinAmount,
        );
        $this->bus->dispatch(command: new AddCoinToCustomerWalletCommand(amount: $amount));
        $io->text([
            sprintf('<fg=bright-green>--> --> Inserted a %1$s coin.</>', $choice),
        ]);
        $printInput = new ArrayInput([
            'command' => 'machine:print',
        ]);
        $this->getApplication()?->doRun($printInput, $output);
        return Command::SUCCESS;
    }
}
