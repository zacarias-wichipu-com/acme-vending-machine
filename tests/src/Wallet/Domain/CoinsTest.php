<?php

declare(strict_types=1);

namespace Tests\Acme\Wallet\Domain;

use Acme\Coin\Domain\AmountInCents;
use Acme\Coin\Domain\Coin;
use Acme\Wallet\Domain\CoinBox;
use Acme\Wallet\Domain\Coins;
use Generator;
use PHPUnit\Framework\TestCase;

class CoinsTest extends TestCase
{
    /**
     * It Should Calculate Amount
     *
     * @dataProvider calculateAmountData
     * @group coins
     * @group unit
     */
    public function testItShouldCalculateAmount(Coins $coins, int $amount): void
    {
        $this->assertEquals(expected: $amount, actual: $coins->amount());
    }

    /**
     * It Should Get The Quantity Of Every Coins
     *
     * @dataProvider countFromCoinsAmountData
     * @group coins
     * @group unit
     */
    public function testItShouldGetTheQuantityOfEveryCoins(Coins $coins, array $expectations): void
    {
        foreach ($expectations as $expectation) {
            $this->assertEquals(expected: $expectation['quantity'], actual: $coins->countFromCoinAmount($expectation['amountInCents']));
        }
    }

    public static function calculateAmountData(): Generator
    {
        yield [
            Coins::create([
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::FIVE), quantity: 10),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::TEN), quantity: 2),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::TWENTY_FIVE), quantity: 4),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), quantity: 3),
            ]),
            470,
        ];
        yield [
            Coins::create([
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::FIVE), quantity: 7),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), quantity: 2),
            ]),
            235,
        ];
    }
    public static function countFromCoinsAmountData(): Generator
    {
        yield [
            Coins::create([
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::FIVE), quantity: 321),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::TEN), quantity: 113),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::TWENTY_FIVE), quantity: 74),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), quantity: 29),
            ]),
            [
                [
                    'amountInCents' => AmountInCents::FIVE,
                    'quantity' => 321,
                ],
                [
                    'amountInCents' => AmountInCents::TEN,
                    'quantity' => 113,
                ],
                [
                    'amountInCents' => AmountInCents::TWENTY_FIVE,
                    'quantity' => 74,
                ],
                [
                    'amountInCents' => AmountInCents::ONE_HUNDRED,
                    'quantity' => 29,
                ],
            ]
        ];
        yield [
            Coins::create([
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::TEN), quantity: 33),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), quantity: 4),
            ]),
            [
                [
                    'amountInCents' => AmountInCents::FIVE,
                    'quantity' => 0,
                ],
                [
                    'amountInCents' => AmountInCents::TEN,
                    'quantity' => 33,
                ],
                [
                    'amountInCents' => AmountInCents::TWENTY_FIVE,
                    'quantity' => 0,
                ],
                [
                    'amountInCents' => AmountInCents::ONE_HUNDRED,
                    'quantity' => 4,
                ],
            ]
        ];
    }
}
