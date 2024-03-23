<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Domain;

use Acme\Coin\Domain\AmountInCents;
use Acme\Coin\Domain\Coin;
use Acme\Product\Domain\Product;
use Acme\Product\Domain\ProductType;
use Acme\Store\Domain\Rack;
use Acme\Store\Domain\Racks;
use Acme\Store\Domain\Store;
use Acme\VendingMachine\Domain\Status;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\Wallet\Domain\CoinBox;
use Acme\Wallet\Domain\Coins;
use Acme\Wallet\Domain\Wallet;
use Tests\Acme\Shared\Domain\MotherCreator;

final class VendingMachineMother
{
    public static function defaultMachine(): VendingMachine
    {
        return VendingMachine::createDefault();
    }

    public static function randomMachine(): VendingMachine
    {
        return VendingMachine::create(
            status: static::randomStatus(),
            store: static::randomStore(),
            wallet: static::randomWallet(),
        );
    }

    private static function randomStatus(): Status
    {
        $randomStatusCases = MotherCreator::random()->shuffleArray(Status::cases());
        /** @var Status $randomStatus */
        $randomStatus = array_shift($randomStatusCases);
        return Status::from($randomStatus->value);
    }

    private static function randomStore(): Store
    {
        return Store::create(
            racks: Racks::create(
                racks: [
                    Rack::create(
                        product: static::randomProduct(),
                        price: MotherCreator::random()->numberBetween(1, 1000),
                        quantity: MotherCreator::random()->numberBetween(1, 5),
                    ),
                ]
            )
        );
    }

    private static function randomProduct(): Product
    {
        return Product::createFromType(static::randomProductType());
    }

    private static function randomProductType(): ProductType
    {
        $randomProductTypeCases = MotherCreator::random()->shuffleArray(ProductType::cases());
        /** @var ProductType $randomProductType */
        $randomProductType = array_shift($randomProductTypeCases);
        return ProductType::from($randomProductType->value);
    }

    private static function randomWallet(): Wallet
    {
        return Wallet::create(
            exchangeCoins: Coins::create([
                CoinBox::create(
                    Coin::createFromAmountInCents(AmountInCents::FIVE),
                    MotherCreator::random()->numberBetween(1, 10)
                ),
                CoinBox::create(
                    Coin::createFromAmountInCents(AmountInCents::TEN),
                    MotherCreator::random()->numberBetween(1, 10)
                ),
                CoinBox::create(
                    Coin::createFromAmountInCents(AmountInCents::TWENTY_FIVE),
                    MotherCreator::random()->numberBetween(1, 10)
                ),
                CoinBox::create(
                    Coin::createFromAmountInCents(AmountInCents::ONE_HUNDRED),
                    MotherCreator::random()->numberBetween(1, 10)
                ),
            ]),
            customerCoins: Coins::create([])
        );
    }
}
