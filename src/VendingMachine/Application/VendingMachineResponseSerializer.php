<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application;

use Acme\VendingMachine\Domain\VendingMachine;

interface VendingMachineResponseSerializer
{
    public function normalize(VendingMachine $vendingMachine): array;
}
