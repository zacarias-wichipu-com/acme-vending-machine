<?php

declare(strict_types=1);

namespace Acme\Coin\Domain;

final readonly class Coin
{
    private function __construct(
        private AmountInCents $amountInCents
    ) {}

    public static function createFromAmountInCents(AmountInCents $amountInCents): static
    {
        return new static(amountInCents: $amountInCents);
    }

    public function amount(): int
    {
        return $this->amountInCents->value;
    }
}
