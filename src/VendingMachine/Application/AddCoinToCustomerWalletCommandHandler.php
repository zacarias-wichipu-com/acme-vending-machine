<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application;

use Acme\Coin\Domain\AmountInCents;
use Acme\Coin\Domain\Coin;
use Acme\Coin\Domain\InvalidCoinException;
use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Throwable;

final readonly class AddCoinToCustomerWalletCommandHandler implements CommandHandler
{
    public function __construct(
        private VendingMachineRepository $repository
    ) {}

    public function __invoke(AddCoinToCustomerWalletCommand $command): void
    {
        $vendingMachine = $this->repository->get();
        $vendingMachine->addCustomerCoin(
            coin: Coin::createFromAmountInCents(amountInCents: $this->amountInCents($command->amount))
        );
        $this->repository->save($vendingMachine);
    }

    private function amountInCents(int $amount): AmountInCents
    {
        try {
            return AmountInCents::from($amount);
        } catch (Throwable) {
            throw new InvalidCoinException(
                message: sprintf(
                    '%1$s i not a valid coin.',
                    $amount,
                )
            );
        }
    }
}
