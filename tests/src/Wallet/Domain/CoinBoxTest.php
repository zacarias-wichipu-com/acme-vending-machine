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
        $this->assertEquals($amount, $coinBox->amount());
    }

    public static function calculateAmountData(): Generator
    {
        yield [
            CoinBox::create(Coin::createFromAmountInCents(AmountInCents::FIVE), 10),
            50
        ];
        yield [
            CoinBox::create(Coin::createFromAmountInCents(AmountInCents::TEN), 10),
            100
        ];
        yield [
            CoinBox::create(Coin::createFromAmountInCents(AmountInCents::TWENTY_FIVE), 2),
            50
        ];
        yield [
            CoinBox::create(Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), 3),
            300
        ];
    }
}
