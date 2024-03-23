<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Infrastructure\Persistence;

use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Override;

final readonly class JsonFileVendingMachineRepository implements VendingMachineRepository
{
    #[Override]
    public function save(VendingMachine $vendingMachine): void {}

    #[Override]
    public function get(): VendingMachine
    {
        return VendingMachine::createDefault();
    }
}
