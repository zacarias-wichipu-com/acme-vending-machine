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

    public static function create(
        Status $status,
        Store $store,
        Wallet $wallet,
    ): static {
        return new static(
            status: $status,
            store: $store,
            wallet: $wallet
        );
    }

    public static function createDefault(): static
    {
        return static::create(
            status: Status::OPERATIONAL,
            store: Store::createDefault(),
            wallet: Wallet::createDefault()
        );
    }

    public function status(): Status
    {
        return $this->status;
    }
}
