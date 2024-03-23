<?php

declare(strict_types=1);

namespace Tests\Acme\Wallet\Domain;

use Acme\Coin\Domain\AmountInCents;
use Acme\Coin\Domain\Coin;
use Acme\Wallet\Domain\CoinBox;
use Generator;
use PHPUnit\Framework\TestCase;

class CoinBoxTest extends TestCase
{
    /**
     * It Should Calculate Amount
     *
     * @dataProvider calculateAmountData
     * @group coin_box
     * @group unit
     */
    public function testItShouldCalculateAmount(CoinBox $coinBox, int $amount): void
    {
        $this->assertEquals(expected: $amount, actual: $coinBox->amount());
    }

    public static function calculateAmountData(): Generator
    {
        yield [
            CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::FIVE), quantity: 10),
            50
        ];
        yield [
            CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::TEN), quantity: 10),
            100
        ];
        yield [
            CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::TWENTY_FIVE), quantity: 2),
            50
        ];
        yield [
            CoinBox::create(coin: Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), quantity: 3),
            300
        ];
    }
}
