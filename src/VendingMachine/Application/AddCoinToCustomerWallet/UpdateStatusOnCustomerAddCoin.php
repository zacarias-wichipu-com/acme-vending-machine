<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\AddCoinToCustomerWallet;

use Acme\Shared\Domain\Bus\Event\DomainEventSubscriber;
use Acme\VendingMachine\Domain\Event\CustomerHasInsertACoinEvent;

final class UpdateStatusOnCustomerAddCoin implements DomainEventSubscriber
{
    public function __invoke(CustomerHasInsertACoinEvent $event): void {}
}
