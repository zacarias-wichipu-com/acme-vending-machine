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

    public static function calculateAmountData(): Generator
    {
        yield [
            Coins::create([
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::FIVE), quantity: 10),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::TEN), quantity: 2),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::TWENTY_FIVE), quantity: 4),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), quantity: 3),
            ]),
            470
        ];
        yield [
            Coins::create([
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::FIVE), quantity: 7),
                CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), quantity: 2),
            ]),
            235
        ];
    }
}
