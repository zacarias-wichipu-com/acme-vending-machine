<?php

declare(strict_types=1);

namespace Acme\Ui\Cli\Command;

use Acme\Shared\Domain\CurrencyUtils;
use Acme\Shared\Infrastructure\Symfony\Console\Command\BusCommand;
use Acme\VendingMachine\Application\Get\GetVendingMachineQuery;
use Acme\VendingMachine\Application\Refund\RefundCustomerWalletCommand;
use Acme\VendingMachine\Application\VendingMachineResponse;
use Acme\VendingMachine\Domain\Exception\NotInSellingModeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
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
        /** @var VendingMachineResponse $vendingMachineResponse */
        $vendingMachineResponse = $this->bus->ask(new GetVendingMachineQuery());
        $refundedAmount = $vendingMachineResponse->customerAmount();
        $refundedCoins = array_reduce(
            array: $vendingMachineResponse->customerCoins(),
            callback: static fn(array $carry, array $coins): array => [
                ...$carry, ...array_fill(0, $coins['quantity'], CurrencyUtils::toDecimalString($coins['coin'])),
            ],
            initial: []
        );
        try {
            $this->bus->dispatch(command: new RefundCustomerWalletCommand());
        } catch (NotInSellingModeException $exception) {
            $io->text([
                sprintf('<fg=bright-red>-->--> %1$s</>', $exception->getMessage())
            ]);
            return Command::SUCCESS;
        }
        $io->text([
            sprintf(
                '<fg=bright-green>-->--> Amount refunded %1$s (coins: %2$s).</>',
                CurrencyUtils::toDecimalString($refundedAmount),
                implode(
                    ', ',
                    $refundedCoins
                )
            ),
        ]);
        return Command::SUCCESS;
    }
}
