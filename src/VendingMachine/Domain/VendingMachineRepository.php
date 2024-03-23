<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Domain;

interface VendingMachineRepository
{
    public function save(VendingMachine $vendingMachine): void;
}
