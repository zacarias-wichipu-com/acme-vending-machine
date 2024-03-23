<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Domain;

use Acme\Store\Domain\Store;
use Acme\Store\Domain\WareHouse;
use Acme\Wallet\Domain\Wallet;

final readonly class VendingMachine
{
    private function __construct(
        private Status $status,
        private Store $store,
        private Wallet $wallet,
    ) {}

    public static function createDefault(): static
    {
        return new static(
            status: Status::OPERATIONAL,
            store: Store::createDefault(),
            wallet: Wallet::createDefault()
        );
    }
}