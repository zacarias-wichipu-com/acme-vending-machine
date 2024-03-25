<?php

declare(strict_types=1);

namespace Acme\Ui\Cli\Command;

use Acme\Shared\Domain\CurrencyUtils;
use Acme\Shared\Infrastructure\Symfony\Console\Command\BusCommand;
use Acme\VendingMachine\Application\Get\GetVendingMachineQuery;
use Acme\VendingMachine\Application\VendingMachineResponse;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'machine:print')]
final class PrintVendingMachineCommand extends Command
{
    public function __construct(
        private readonly BusCommand $bus
    ) {
        parent::__construct('machine:print');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->text([
            '<fg=bright-magenta>--> Print vending machine.</>'
        ]);
        /** @var VendingMachineResponse $vendingMachine */
        $vendingMachine = $this->bus->ask(new GetVendingMachineQuery());
        $io->text([
            sprintf('<fg=blue>Machine status</>: %1$s', $vendingMachine->status()),
            sprintf('<fg=blue>Exchange amount</>: %1$s', CurrencyUtils::toDecimalString($vendingMachine->exchangeAmount())),
            sprintf('<fg=blue>Customer amount</>: %1$s', CurrencyUtils::toDecimalString($vendingMachine->customerAmount())),
            '',
            "<fg=blue>Store:</> ",
        ]);
        $io->table(
            headers: [
                '<fg=white>Product</>',
                '<fg=white>Price</>',
                '<fg=white>Quantity</>',
            ],
            rows: array_map(
                callback: static fn($rack): array => [$rack['product'], CurrencyUtils::toDecimalString($rack['price']), $rack['quantity']],
                array: $vendingMachine->store()
            )
        );
        $io->text([
            "<fg=blue>Exchange wallet:</> ",
        ]);
        $io->table(
            headers: [
                '<fg=white>Coin</>',
                '<fg=white>Quantity</>',
            ],
            rows: array_map(
                callback: static fn($coin): array => [CurrencyUtils::toDecimalString($coin['coin']), $coin['quantity']],
                array: $vendingMachine->exchangeCoins()
            )
        );
        $io->text([
            "<fg=blue>Customer wallet:</> ",
        ]);
        $io->table(
            headers: [
                '<fg=white>Coin</>',
                '<fg=white>Quantity</>',
            ],
            rows: array_map(
                callback: static fn($coin): array => [CurrencyUtils::toDecimalString($coin['coin']), $coin['quantity']],
                array: $vendingMachine->customerCoins()
            )
        );
        return Command::SUCCESS;
    }
}
