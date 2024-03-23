<?php

declare(strict_types=1);

namespace Acme\Coin\Domain;

final readonly class Coin
{
    private function __construct(
        private AmountInCents $amountInCents
    ) {}
}
