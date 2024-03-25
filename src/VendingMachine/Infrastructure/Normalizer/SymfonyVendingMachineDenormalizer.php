<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Infrastructure\Normalizer;

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
use stdClass;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class SymfonyVendingMachineDenormalizer implements DenormalizerInterface
{
    #[\Override]
    public function denormalize(
        mixed $data,
        string $type,
        string $format = null,
        array $context = []
    ): VendingMachine {
        return VendingMachine::create(
            status: Status::from(value: $data->status->value),
            store: Store::create(
                racks: Racks::create(
                    racks: array_map(
                        callback: static fn(stdClass $rack): Rack => Rack::create(Product::createFromType(ProductType::from($rack->product->type->value)), $rack->price, $rack->quantity),
                        array: $data->store->racks
                    )
                )
            ),
            wallet: Wallet::create(
                exchangeCoins: Coins::create(
                    coinBox: array_map(
                        callback: static fn(stdClass $coinBox): CoinBox => CoinBox::create(Coin::createFromAmountInCents(AmountInCents::from($coinBox->coin->amountInCents->value)), $coinBox->quantity),
                        array: $data->wallet->exchangeCoins
                    )
                ),
                customerCoins: Coins::create(
                    coinBox: array_map(
                        callback: static fn(stdClass $coinBox): CoinBox => CoinBox::create(Coin::createFromAmountInCents(AmountInCents::from($coinBox->coin->amountInCents->value)), $coinBox->quantity),
                        array: $data->wallet->customerCoins
                    )
                ),
                refundCoins: Coins::create(
                    coinBox: array_map(
                        callback: static fn(stdClass $coinBox): CoinBox => CoinBox::create(Coin::createFromAmountInCents(AmountInCents::from($coinBox->coin->amountInCents->value)), $coinBox->quantity),
                        array: $data->wallet->refundCoins
                    )
                ),
            )
        );
    }

    #[\Override]
    public function supportsDenormalization(
        mixed $data,
        string $type,
        string $format = null,
        array $context = []
    ): bool {
        return $type === VendingMachine::class;
    }

    #[\Override]
    public function getSupportedTypes(?string $format): array
    {
        return [
            VendingMachine::class => false,
        ];
    }
}
