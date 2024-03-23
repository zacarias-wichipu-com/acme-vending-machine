<?php

declare(strict_types=1);

namespace Acme\Wallet\Domain;

final readonly class Wallet
{
    private function __construct(
        private Coins $exchangeCoins,
        private Coins $customerCoins,
    ) {}
}
