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


    public static function calculateAmountData(): Generator
    {
        yield [
            Wallet::create(
                exchangeCoins: Coins::create([
                    CoinBox::create(Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), 1),
                    CoinBox::create(Coin::createFromAmountInCents(AmountInCents::TWENTY_FIVE), 1),
                    CoinBox::create(Coin::createFromAmountInCents(AmountInCents::TEN), 1),
                ]),
                customerCoins: Coins::create([])
            ),
            135,
        ];
        yield [
            Wallet::create(
                exchangeCoins: Coins::create([
                    CoinBox::create(Coin::createFromAmountInCents(AmountInCents::FIVE), 1),
                    CoinBox::create(Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED), 5),
                ]),
                customerCoins: Coins::create([])
            ),
            505,
        ];
    }
}
