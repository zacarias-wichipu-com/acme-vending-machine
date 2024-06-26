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

    public static function randomMachine(
        ?Status $status = null,
        ?Store $store = null,
        ?Wallet $wallet = null,
    ): VendingMachine {
        return VendingMachine::create(
            status: $status ?: static::randomStatus(),
            store: $store ?: static::randomStore(),
            wallet: $wallet ?: static::randomWallet(),
        );
    }

    public static function randomRacks(): array
    {
        return [
            self::randomRack(),
        ];
    }

    public static function randomRack(
        ?Product $product = null,
        ?int $price = null,
        ?int $quantity = null,
    ): Rack {
        return Rack::create(
            product: $product ?: static::randomProduct(),
            price: $price ?: MotherCreator::random()->numberBetween(1, 1000),
            quantity: $quantity ?: MotherCreator::random()->numberBetween(1, 5),
        );
    }

    private static function randomStatus(): Status
    {
        $randomStatusCases = MotherCreator::random()->shuffleArray(Status::cases());
        /** @var Status $randomStatus */
        $randomStatus = array_shift($randomStatusCases);
        return Status::from($randomStatus->value);
    }

    public static function randomStore(?array $racks = null): Store
    {
        return Store::create(
            racks: Racks::create(
                racks: $racks ?: self::randomRacks()
            )
        );
    }

    public static function randomProduct(?ProductType $productType = null): Product
    {
        return Product::createFromType($productType ?: static::randomProductType());
    }

    private static function randomProductType(): ProductType
    {
        $randomProductTypeCases = MotherCreator::random()->shuffleArray(ProductType::cases());
        /** @var ProductType $randomProductType */
        $randomProductType = array_shift($randomProductTypeCases);
        return ProductType::from($randomProductType->value);
    }

    public static function randomWallet(
        ?Coins $exchangeCoins = null,
        ?Coins $customerCoins = null,
        ?Coins $refundCoins = null,
    ): Wallet {
        return Wallet::create(
            exchangeCoins: $exchangeCoins ?: self::randomCoins(),
            customerCoins: $customerCoins ?: self::noCoins(),
            refundCoins: $refundCoins ?: self::noCoins(),
        );
    }

    public static function randomCoins(?array $coinBoxes = null): Coins
    {
        return Coins::create(coinBox: $coinBoxes ?: self::randomCoinBoxes());
    }

    private static function noCoins(): Coins
    {
        return Coins::create([]);
    }

    public static function randomCoinBoxes(): array
    {
        return [
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
        ];
    }

    public static function coinBoxFrom(AmountInCents $amountInCents, int $quantity): CoinBox
    {
        return CoinBox::create(
            coin: Coin::createFromAmountInCents(amountInCents: $amountInCents),
            quantity: $quantity
        );
    }
}
