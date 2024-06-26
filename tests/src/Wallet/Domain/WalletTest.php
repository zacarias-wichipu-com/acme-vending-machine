<?php

declare(strict_types=1);

namespace Tests\Acme\Wallet\Domain;

use Acme\Coin\Domain\AmountInCents;
use Acme\Coin\Domain\Coin;
use Acme\Wallet\Domain\CoinBox;
use Acme\Wallet\Domain\Coins;
use Acme\Wallet\Domain\Wallet;
use Generator;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    /**
     * It Should Calculate Exchange Amount
     *
     * @dataProvider calculateAmountData
     * @group wallet
     * @group unit
     */
    public function testItShouldCalculateExchangeAmount(Wallet $wallet, int $amount): void
    {
        $this->assertEquals(expected: $amount, actual: $wallet->exchangeAmount());
    }

    /**
     * It Should Add A Customer Coin When The Coin Box Does Not Have Coins
     *
     * @group wallet
     * @group unit
     */
    public function testItShouldAddACustomerCoinWhenTheCoinBoxDoesNotHaveCoins(): void
    {
        $initialCustomerCoins = Coins::create([
            CoinBox::create(Coin::createFromAmountInCents(AmountInCents::FIVE), 10),
            CoinBox::create(Coin::createFromAmountInCents(AmountInCents::TEN), 1),
            CoinBox::create(Coin::createFromAmountInCents(AmountInCents::TWENTY_FIVE), 9),
        ]);
        $wallet = Wallet::create(
            exchangeCoins: Coins::create([]),
            customerCoins: $initialCustomerCoins,
            refundCoins: Coins::create([]),
        );
        $wallet->addCustomerCoin(coin: Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED));
        $updatedCustomerCoins = $wallet->customerCoins();
        $this->assertCount(4, $updatedCustomerCoins);
        $this->assertEquals(expected: 10, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::FIVE));
        $this->assertEquals(expected: 1, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::TEN));
        $this->assertEquals(expected: 9, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::TWENTY_FIVE));
        $this->assertEquals(expected: 1, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::ONE_HUNDRED));
    }

    /**
     * It Should Add A Customer Coin When The Coin Box Have Coins
     *
     * @group wallet
     * @group unit
     */
    public function testItShouldAddACustomerCoinWhenTheCoinBoxHaveCoins(): void
    {
        $initialCustomerCoins = Coins::create([
            CoinBox::create(Coin::createFromAmountInCents(AmountInCents::FIVE), 10),
            CoinBox::create(Coin::createFromAmountInCents(AmountInCents::TEN), 1),
            CoinBox::create(Coin::createFromAmountInCents(AmountInCents::TWENTY_FIVE), 9),
        ]);
        $wallet = Wallet::create(
            exchangeCoins: Coins::create([]),
            customerCoins: $initialCustomerCoins,
            refundCoins: Coins::create([]),
        );

        $wallet->addCustomerCoin(coin: Coin::createFromAmountInCents(AmountInCents::TEN));
        $updatedCustomerCoins = $wallet->customerCoins();
        $this->assertCount(3, $updatedCustomerCoins);
        $this->assertEquals(expected: 10, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::FIVE));
        $this->assertEquals(expected: 2, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::TEN));
        $this->assertEquals(expected: 9, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::TWENTY_FIVE));
        $this->assertEquals(expected: 0, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::ONE_HUNDRED));

        $wallet->addCustomerCoin(coin: Coin::createFromAmountInCents(AmountInCents::TEN));
        $updatedCustomerCoins = $wallet->customerCoins();
        $this->assertCount(3, $updatedCustomerCoins);
        $this->assertEquals(expected: 10, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::FIVE));
        $this->assertEquals(expected: 3, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::TEN));
        $this->assertEquals(expected: 9, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::TWENTY_FIVE));
        $this->assertEquals(expected: 0, actual: $updatedCustomerCoins->countFromCoinAmount(AmountInCents::ONE_HUNDRED));
    }

    public static function calculateAmountData(): Generator
    {
        yield [
            Wallet::create(
                exchangeCoins: Coins::create([
                    CoinBox::create(Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), 1),
                    CoinBox::create(Coin::createFromAmountInCents(AmountInCents::TWENTY_FIVE), 1),
                    CoinBox::create(Coin::createFromAmountInCents(AmountInCents::TEN), 1),
                ]),
                customerCoins: Coins::create([]),
                refundCoins: Coins::create([]),
            ),
            135,
        ];
        yield [
            Wallet::create(
                exchangeCoins: Coins::create([
                    CoinBox::create(Coin::createFromAmountInCents(AmountInCents::FIVE), 1),
                    CoinBox::create(Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), 5),
                ]),
                customerCoins: Coins::create([]),
                refundCoins: Coins::create([]),
            ),
            505,
        ];
    }
}
