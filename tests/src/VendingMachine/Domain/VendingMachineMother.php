<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Domain;

use Acme\VendingMachine\Domain\VendingMachine;

final class VendingMachineMother
{
    public static function defaultMachine(): VendingMachine
    {
        return VendingMachine::createDefault();
    }
}
