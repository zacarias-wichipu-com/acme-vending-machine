<?php

declare(strict_types=1);

namespace Acme\Wallet\Domain;

final readonly class Wallet
{
    private function __construct(
        private Coins $exchangeCoins,
        private Coins $customerCoins,
    ) {}

    public static function create(Coins $exchangeCoins, Coins $customerCoins): static
    {
        return new static(
            exchangeCoins: $exchangeCoins,
            customerCoins: $customerCoins,
        );
    }

    public static function createDefault(): static
    {
        return static::create(
            exchangeCoins: Coins::createDefaultExchange(),
            customerCoins: Coins::create([]),
        );
    }

    public function exchangeCoins(): Coins
    {
        return $this->exchangeCoins;
    }

    public function customerCoins(): Coins
    {
        return $this->customerCoins;
    }

    public function exchangeAmount(): int
    {
        return $this->exchangeCoins->amount();
    }

    public function customerAmount(): int
    {
        return $this->customerCoins->amount();
    }
}
