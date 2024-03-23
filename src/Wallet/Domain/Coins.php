<?php

declare(strict_types=1);

namespace Acme\Wallet\Domain;

use Acme\Coin\Domain\AmountInCents;
use Acme\Coin\Domain\Coin;
use Acme\Shared\Domain\Collection;
use Override;

/**
 * @template  T
 * @extends  Collection<T>
 */
final class Coins extends Collection
{
    /**
     * @param  array<T>  $coinBox
     */
    public static function create(array $coinBox): static
    {
        return new static($coinBox);
    }

    public static function createDefaultExchange(): static
    {
        return static::create([
            CoinBox::create(
                coin: Coin::createFromAmountInCents(amountInCents: AmountInCents::FIVE),
                quantity: 5
            ),
            CoinBox::create(
                coin: Coin::createFromAmountInCents(amountInCents: AmountInCents::TEN),
                quantity: 3
            ),
            CoinBox::create(
                coin: Coin::createFromAmountInCents(amountInCents: AmountInCents::TWENTY_FIVE),
                quantity: 2
            ),
            CoinBox::create(
                coin: Coin::createFromAmountInCents(amountInCents: AmountInCents::ONE_HUNDRED),
                quantity: 1
            ),
        ]);
    }

    /**
     * @return class-string
     */
    #[Override]
    protected function type(): string
    {
        return CoinBox::class;
    }
}
