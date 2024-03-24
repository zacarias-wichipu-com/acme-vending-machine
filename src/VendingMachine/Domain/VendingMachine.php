<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Domain;

use Acme\Shared\Domain\Aggregate\AggregateRoot;
use Acme\Store\Domain\Store;
use Acme\Wallet\Domain\Wallet;

final class VendingMachine extends AggregateRoot
{
    private function __construct(
        private readonly Status $status,
        private readonly Store $store,
        private readonly Wallet $wallet,
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
    public function store(): Store
    {
        return $this->store;
    }

    public function wallet(): Wallet
    {
        return $this->wallet;
    }

    public function exchangeAmount(): int
    {
        return $this->wallet->exchangeAmount();
    }
    public function customerAmount(): int
    {
        return $this->wallet->customerAmount();
    }
}
