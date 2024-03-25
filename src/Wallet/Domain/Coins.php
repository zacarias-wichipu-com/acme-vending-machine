<?php

declare(strict_types=1);

namespace Acme\Wallet\Domain;

use Acme\Coin\Domain\AmountInCents;
use Acme\Coin\Domain\Coin;
use Acme\Shared\Domain\Collection;
use Override;

/**
 * @template  T  of  CoinBox
 * @extends Collection<T>
 */
final class Coins extends Collection
{
    /**
     * @return class-string
     */
    #[Override]
    protected function type(): string
    {
        return CoinBox::class;
    }

    /**
     * @param  array<int, CoinBox>  $coinBox
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

    public function amount(): int
    {
        return array_reduce(
            array: $this->items(),
            callback: static fn(int $amount, CoinBox $coinBox): int => $amount + $coinBox->amount(),
            initial: 0
        );
    }

    public function countFromCoinAmount(AmountInCents $amountInCents): int
    {
        return array_reduce(
            $this->items(),
            static fn(int $quantity, CoinBox $coinBox): int => $coinBox->coin()->amountInCents() === $amountInCents ? $coinBox->quantity() : $quantity,
            0
        );
    }

    public function flatCoins(): array
    {
        $coins = array_map(
            callback: static fn(CoinBox $coinBox): array => [$coinBox->coin()->amountInCents()->value => $coinBox->quantity()],
            array: $this->items()
        );
        usort(
            array: $coins,
            callback: static fn(array $a, array $b) => array_key_first($a) <=> array_key_first($b),
        );
        return $coins;
    }
}
