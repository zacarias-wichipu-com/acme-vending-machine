<?php

declare(strict_types=1);

namespace Acme\Wallet\Domain;

use Acme\Coin\Domain\Coin;

final class Wallet
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

    public function addCustomerCoin(Coin $coin): void
    {
        $updatedCoins = $this->addCoin($this->customerCoins, $coin);
        $this->customerCoins = Coins::create(array_values($updatedCoins));
    }

    public function refundCustomerCoins(): void
    {
        $this->customerCoins = Coins::create([]);
    }

    private function addCoin(Coins $coins, Coin $coin): array
    {
        $totalCoinsFromAmount = $coins->countFromCoinAmount($coin->amountInCents());
        $coinBoxes = (array) $coins->getIterator();
        foreach ($coinBoxes as $index => $coinBox) {
            if ($coinBox->coin()->amountInCents() === $coin->amountInCents()) {
                unset($coinBoxes[$index]);
            }
        }
        $coinBoxes[] = CoinBox::create($coin, ++$totalCoinsFromAmount);
        return $coinBoxes;
    }

}
