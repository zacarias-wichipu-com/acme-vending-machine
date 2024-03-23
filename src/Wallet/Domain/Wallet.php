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

    public static function createDefault(): static
    {
        return new static(
            exchangeCoins: Coins::createDefaultExchange(),
            customerCoins: new Coins()
        );
    }
}
