<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application;

use Acme\Coin\Domain\Coin;
use Acme\Product\Domain\Product;
use Acme\Shared\Domain\Bus\Query\Response;
use Acme\Store\Domain\Rack;
use Acme\Store\Domain\Store;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\Wallet\Domain\CoinBox;
use Acme\Wallet\Domain\Coins;
use Acme\Wallet\Domain\Wallet;

final readonly class VendingMachineResponse implements Response
{
    public function __construct(
        private VendingMachine $vendingMachine,
    ) {}

    #[\Override]
    public function toArray(): array
    {
        return [
            'status' => $this->vendingMachine->status()->value,
            'exchangeAmount' => $this->vendingMachine->exchangeAmount(),
            'customerAmount' => $this->vendingMachine->customerAmount(),
            'store' => $this->storeToArray($this->vendingMachine->store()),
            'wallet' => $this->walletToArray($this->vendingMachine->wallet()),
        ];
    }

    private function storeToArray(Store $store): array
    {
        return array_map(
            callback: fn(Rack $rack): array => [
                'product' => $this->productToArray($rack->product()),
                'quantity' => $rack->quantity(),
                'price' => $rack->price(),
            ],
            array: (array) $store->racks()->getIterator()
        );
    }

    private function productToArray(Product $product): string
    {
        return $product->type()->value;
    }

    private function walletToArray(Wallet $wallet): array
    {
        return [
            'exchangeCoins' => $this->coinsToArray(coins: $wallet->exchangeCoins()),
            'customerCoins' => $this->coinsToArray(coins: $wallet->customerCoins()),
        ];
    }

    private function coinsToArray(Coins $coins): array
    {
        return array_map(
            callback: fn(CoinBox $coinBox): array => [
                'coin' => $this->coinToArray($coinBox->coin()), 'quantity' => $coinBox->quantity(),
            ],
            array: (array) $coins->getIterator()
        );
    }

    private function coinToArray(Coin $coin): int
    {
        return $coin->amount();
    }
}
