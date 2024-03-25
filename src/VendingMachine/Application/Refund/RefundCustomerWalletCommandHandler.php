<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Refund;

use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class RefundCustomerWalletCommandHandler implements CommandHandler
{
    public function __construct(
        private VendingMachineRepository $repository,
        private MessageBusInterface $eventBus
    ) {}

    public function __invoke(RefundCustomerWalletCommand $command): void
    {
        $vendingMachine = $this->repository->get();
        $vendingMachine->refundCustomerCoins();
        $this->repository->save(vendingMachine: $vendingMachine);
        foreach ($vendingMachine->pullDomainEvents() as $domainEvent) {
            $this->eventBus->dispatch($domainEvent);
        }
    }
}
