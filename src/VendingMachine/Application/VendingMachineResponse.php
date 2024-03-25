<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application;

use Acme\Coin\Domain\Coin;
use Acme\Product\Domain\Product;
use Acme\Shared\Domain\Bus\Query\Response;
use Acme\Store\Domain\Rack;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\Wallet\Domain\CoinBox;
use Acme\Wallet\Domain\Coins;

final readonly class VendingMachineResponse implements Response
{
    public function __construct(
        private VendingMachine $vendingMachine,
    ) {}

    #[\Override]
    public function toArray(): array
    {
        return [
            'status' => $this->status(),
            'exchangeAmount' => $this->exchangeAmount(),
            'customerAmount' => $this->customerAmount(),
            'store' => $this->store(),
            'wallet' => $this->wallet(),
        ];
    }

    public function exchangeAmount(): int
    {
        return $this->vendingMachine->exchangeAmount();
    }

    public function customerAmount(): int
    {
        return $this->vendingMachine->customerAmount();
    }

    public function store(): array
    {
        return array_map(
            callback: fn(Rack $rack): array => [
                'product' => $this->productToArray($rack->product()),
                'quantity' => $rack->quantity(),
                'price' => $rack->price(),
            ],
            array: (array) $this->vendingMachine->store()->racks()->getIterator()
        );
    }

    private function productToArray(Product $product): string
    {
        return $product->type()->value;
    }

    private function wallet(): array
    {
        return [
            'exchangeCoins' => $this->exchangeCoins(),
            'customerCoins' => $this->customerCoins(),
            'refundCoins' => $this->customerCoins(),
        ];
    }

    public function exchangeCoins(): array
    {
        return $this->coins(coins: $this->vendingMachine->wallet()->exchangeCoins());
    }

    public function customerCoins(): array
    {
        return $this->coins(coins: $this->vendingMachine->wallet()->customerCoins());
    }

    public function refundCoins(): array
    {
        return $this->coins(coins: $this->vendingMachine->wallet()->refundCoins());
    }

    private function coins(Coins $coins): array
    {
        $coins = array_map(
            callback: fn(CoinBox $coinBox): array => [
                'coin' => $this->coinToArray($coinBox->coin()), 'quantity' => $coinBox->quantity(),
            ],
            array: (array) $coins->getIterator()
        );
        usort(
            array: $coins,
            callback: static fn(array $a, array $b) => $a['coin'] <=> $b['coin'],
        );
        return $coins;
    }

    private function coinToArray(Coin $coin): int
    {
        return $coin->amount();
    }

    public function status(): string
    {
        return $this->vendingMachine->status()->value;
    }
}
