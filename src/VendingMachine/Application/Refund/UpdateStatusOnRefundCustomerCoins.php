<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Refund;

use Acme\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Acme\VendingMachine\Domain\Event\CustomerCoinsWasRefundedEvent;
use Acme\VendingMachine\Domain\VendingMachineRepository;

final readonly class UpdateStatusOnRefundCustomerCoins implements DomainEventSubscriber
{
    public function __construct(
        private VendingMachineRepository $repository
    ) {}

    public function __invoke(CustomerCoinsWasRefundedEvent $event): void
    {
        $vendingMachine = $this->repository->get();
        $vendingMachine->putOperational();
        $this->repository->save($vendingMachine);
    }
}
