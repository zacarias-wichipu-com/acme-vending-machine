<?php

declare(strict_types=1);

namespace Acme\Ui\Cli\Command;

use Acme\Shared\Domain\CurrencyUtils;
use Acme\Shared\Infrastructure\Symfony\Console\Command\BusCommand;
use Acme\VendingMachine\Application\Get\GetVendingMachineQuery;
use Acme\VendingMachine\Application\Refund\RefundCustomerWalletCommand;
use Acme\VendingMachine\Domain\VendingMachine;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'customer:coins:refund')]
final class RefundCustomerCoinsCommand extends Command
{
    public function __construct(
        private readonly BusCommand $bus
    ) {
        parent::__construct('customer:coins:refund');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->text([
            '<fg=bright-magenta>--> Refund customer coins.</>',
        ]);
        /** @var VendingMachine $vendingMachine */
        $vendingMachine = $this->bus->ask(new GetVendingMachineQuery());
        $refundedCoins = $vendingMachine->customerAmount();
        $this->bus->dispatch(command: new RefundCustomerWalletCommand());
        $io->text([
            sprintf('<fg=bright-green>-->--> Refunded %1$s.</>', CurrencyUtils::toDecimalString($refundedCoins)),
        ]);
        $printInput = new ArrayInput([
            'command' => 'machine:print',
        ]);
        $this->getApplication()?->doRun($printInput, $output);
        return Command::SUCCESS;
    }
}
