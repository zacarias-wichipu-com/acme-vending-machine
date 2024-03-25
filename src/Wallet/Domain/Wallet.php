<?php

declare(strict_types=1);

namespace Acme\Wallet\Domain;

use Acme\Coin\Domain\AmountInCents;
use Acme\Coin\Domain\Coin;
use Acme\Shared\Domain\CurrencyUtils;
use Acme\Wallet\Domain\Exception\InsufficientAmountException;
use Acme\Wallet\Domain\Exception\InsufficientExchangeException;

final class Wallet
{
    private function __construct(
        private Coins $exchangeCoins,
        private Coins $customerCoins,
        private Coins $refundCoins,
    ) {}

    public static function create(Coins $exchangeCoins, Coins $customerCoins, Coins $refundCoins): static
    {
        return new static(
            exchangeCoins: $exchangeCoins,
            customerCoins: $customerCoins,
            refundCoins: $refundCoins,
        );
    }

    public static function createDefault(): static
    {
        return static::create(
            exchangeCoins: Coins::createDefaultExchange(),
            customerCoins: Coins::create([]),
            refundCoins: Coins::create([]),
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

    public function refundCoins(): Coins
    {
        return $this->refundCoins;
    }

    public function exchangeAmount(): int
    {
        return $this->exchangeCoins->amount();
    }

    public function customerAmount(): int
    {
        return $this->customerCoins->amount();
    }

    public function refundAmount(): int
    {
        return $this->refundCoins->amount();
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

    public function updateOnBuy(string $productName, int $productPrice): void
    {
        $customerAmount = $this->customerAmount();
        $exchangeAmount = $customerAmount - $productPrice;
        if (0 > $exchangeAmount) {
            throw new InsufficientAmountException(
                message: sprintf(
                    'The balance %1$s is insufficient for product %2$s, please add %3$s more to complete the amount.',
                    CurrencyUtils::toDecimalString($customerAmount),
                    $productName,
                    CurrencyUtils::toDecimalString($productPrice - $customerAmount),
                )
            );
        }
        $flatExchange = $this->exchangeCoins()->flatCoins();
        $flatAvailableExchange = $flatExchange;
        $flatCustomerExchange = [];
        foreach ($flatAvailableExchange as $index => $coins) {
            $coinAmount = array_key_first($coins);
            $availableCoinQuantity = $coins[$coinAmount];
            if ($exchangeAmount >= $coinAmount) {
                $customerCoinQuantity = min($availableCoinQuantity, intdiv($exchangeAmount, $coinAmount));
                $availableCoinQuantity -= $customerCoinQuantity;
                $exchangeAmount -= $customerCoinQuantity * $coinAmount;
                unset($flatAvailableExchange[$index]);
                $flatAvailableExchange[] = [$coinAmount => $availableCoinQuantity];
                $flatCustomerExchange[] = [$coinAmount => $customerCoinQuantity];
            }
        }
        if ($exchangeAmount > 0) {
            throw new InsufficientExchangeException(
                message: 'We do not have enough exchange, you can select another product or request a refund of the coins.'
            );
        }
        usort(
            array: $flatAvailableExchange,
            callback: static fn(array $a, array $b) => $a[array_key_first($a)] <=> $b[array_key_first($b)],
        );
        $updateExchangeCoinBoxes = array_map(
            callback: static fn(
                array $flatCoinBox
            ): CoinBox => CoinBox::create(
                Coin::createFromAmountInCents(AmountInCents::from(array_key_first($flatCoinBox))),
                $flatCoinBox[array_key_first($flatCoinBox)]
            ),
            array: $flatAvailableExchange
        );
        usort(
            array: $flatCustomerExchange,
            callback: static fn(array $a, array $b) => $a[array_key_first($a)] <=> $b[array_key_first($b)],
        );
        $refundCoinBoxes = array_map(
            callback: static fn(
                array $flatCoinBox
            ): CoinBox => CoinBox::create(
                Coin::createFromAmountInCents(AmountInCents::from(array_key_first($flatCoinBox))),
                $flatCoinBox[array_key_first($flatCoinBox)]
            ),
            array: $flatCustomerExchange
        );
        $this->exchangeCoins = Coins::create(coinBox: array_values($updateExchangeCoinBoxes));
        $this->refundCoins = Coins::create(coinBox: array_values($refundCoinBoxes));
        $this->customerCoins = Coins::create([]);
    }
}
