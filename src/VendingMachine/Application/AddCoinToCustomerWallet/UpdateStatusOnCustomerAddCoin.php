<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\AddCoinToCustomerWallet;

use Acme\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Acme\VendingMachine\Domain\Event\CustomerHasInsertACoinEvent;
use Acme\VendingMachine\Domain\VendingMachineRepository;

final readonly class UpdateStatusOnCustomerAddCoin implements DomainEventSubscriber
{
    public function __construct(
        private VendingMachineRepository $repository
    ) {}

    public function __invoke(CustomerHasInsertACoinEvent $event): void
    {
        $vendingMachine = $this->repository->get();
        $vendingMachine->putInService();
        $this->repository->save($vendingMachine);
    }
}
