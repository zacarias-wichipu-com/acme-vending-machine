<?php

declare(strict_types=1);

namespace Tests\Acme\Coin\Domain;

use Acme\Coin\Domain\AmountInCents;
use Acme\Coin\Domain\Coin;
use Generator;
use PHPUnit\Framework\TestCase;

class CoinTest extends TestCase
{
    /**
     * It Should Calculate Amount
     *
     * @dataProvider calculateAmountData
     * @group coin
     * @group unit
     */
    public function testItShouldCalculateAmount(Coin $coin, int $amount): void
    {
        $this->assertEquals(expected: $amount, actual: $coin->amount());
    }

    public static function calculateAmountData(): Generator
    {
        yield [Coin::createFromAmountInCents(amountInCents: AmountInCents::FIVE), 5];
        yield [Coin::createFromAmountInCents(amountInCents: AmountInCents::TEN), 10];
        yield [Coin::createFromAmountInCents(amountInCents: AmountInCents::TWENTY_FIVE), 25];
        yield [Coin::createFromAmountInCents(amountInCents: AmountInCents::ONE_HUNDRED), 100];
    }
}
