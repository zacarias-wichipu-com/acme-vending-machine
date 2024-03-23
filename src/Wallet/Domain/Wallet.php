<?php

declare(strict_types=1);

namespace Acme\Wallet\Domain;

use Acme\Shared\Domain\Collection;

final readonly class Wallet
{
    /**
     * @template  T
     * @param  Coins<Collection<T>>  $exchangeCoins
     * @param  Coins<Collection<T>>  $customerCoins
     */
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
}
