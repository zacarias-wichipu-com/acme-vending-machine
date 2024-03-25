<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\AddCoinToCustomerWallet;

use Acme\Coin\Domain\AmountInCents;
use Acme\Coin\Domain\Coin;
use Acme\Coin\Domain\Exception\InvalidCoinException;
use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final readonly class AddCoinToCustomerWalletCommandHandler implements CommandHandler
{
    public function __construct(
        private VendingMachineRepository $repository,
        private MessageBusInterface $eventBus
    ) {}

    public function __invoke(AddCoinToCustomerWalletCommand $command): void
    {
        $vendingMachine = $this->repository->get();
        $amountInCents = $this->amountInCents($command->amount);
        $vendingMachine->addCustomerCoin(
            coin: Coin::createFromAmountInCents(amountInCents: $amountInCents)
        );
        $this->repository->save($vendingMachine);
        foreach ($vendingMachine->pullDomainEvents() as $domainEvent) {
            $this->eventBus->dispatch($domainEvent);
        }
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
