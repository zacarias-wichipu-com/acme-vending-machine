<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Buy;

use Acme\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Acme\VendingMachine\Domain\Event\BuyProductExchangeWasRefundedEvent;
use Acme\VendingMachine\Domain\VendingMachineRepository;

final readonly class UpdateStatusOnBuyExchangeHasBenRefunded implements DomainEventSubscriber
{
    public function __construct(
        private VendingMachineRepository $repository
    ) {}

    public function __invoke(BuyProductExchangeWasRefundedEvent $event): void
    {
        $vendingMachine = $this->repository->get();
        $vendingMachine->putOperational();
        $this->repository->save($vendingMachine);
    }
}
