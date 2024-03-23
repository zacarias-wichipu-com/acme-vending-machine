<?php

declare(strict_types=1);

namespace Acme\Wallet\Domain;

use Acme\Coin\Domain\Coin;

final readonly class CoinBox
{
    private function __construct(
        private Coin $coin,
        private int $quantity
    ) {}

    public static function createDefault(Coin $coin, int $quantity): static
    {
        return new static(
            coin: $coin,
            quantity: $quantity
        );
    }
}