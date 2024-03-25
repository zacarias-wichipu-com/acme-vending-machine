<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Refund;

use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Domain\Exception\NotServiceAvailableException;
use Acme\VendingMachine\Domain\Status;
use Acme\VendingMachine\Domain\VendingMachine;
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
        $this->ensureCaseFrom($vendingMachine);
        $vendingMachine->refundCustomerCoins();
        $this->repository->save(vendingMachine: $vendingMachine);
        foreach ($vendingMachine->pullDomainEvents() as $domainEvent) {
            $this->eventBus->dispatch($domainEvent);
        }
    }

    private function ensureCaseFrom(VendingMachine $vendingMachine): void
    {
        if ($vendingMachine->status() !== Status::SELLING) {
            throw new NotServiceAvailableException(message: 'Not service to refund.');
        }
    }
}
