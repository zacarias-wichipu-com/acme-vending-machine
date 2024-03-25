<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Domain;

use Acme\Coin\Domain\Coin;
use Acme\Shared\Domain\Aggregate\AggregateRoot;
use Acme\Store\Domain\Store;
use Acme\VendingMachine\Domain\Event\CustomerCoinsWasRefundedEvent;
use Acme\VendingMachine\Domain\Event\CustomerHasInsertACoinEvent;
use Acme\VendingMachine\Domain\Exception\ServiceModeUnavailable;
use Acme\Wallet\Domain\Coins;
use Acme\Wallet\Domain\Wallet;

final class VendingMachine extends AggregateRoot
{
    private function __construct(
        private Status $status,
        private readonly Store $store,
        private readonly Wallet $wallet,
    ) {}

    public static function create(
        Status $status,
        Store $store,
        Wallet $wallet,
    ): static {
        return new static(
            status: $status,
            store: $store,
            wallet: $wallet
        );
    }

    public static function createDefault(): static
    {
        return static::create(
            status: Status::OPERATIONAL,
            store: Store::createDefault(),
            wallet: Wallet::createDefault()
        );
    }

    public function status(): Status
    {
        return $this->status;
    }

    public function putInSellingMode(): void
    {
        if ($this->status === Status::SELLING) {
            return;
        }
        $this->status = Status::SELLING;
    }

    public function putOperational()
    {
        if ($this->status === Status::OPERATIONAL) {
            return;
        }
        $this->status = Status::OPERATIONAL;
    }
    public function putInService(): void
    {
        if ($this->status === Status::IN_SERVICE) {
            return;
        }
        if ($this->status === Status::SELLING) {
            throw new ServiceModeUnavailable('You cannot enter service mode while a sales process is in progress. Cancel the purchase first.');
        }
        $this->status = Status::IN_SERVICE;
    }

    public function store(): Store
    {
        return $this->store;
    }

    public function wallet(): Wallet
    {
        return $this->wallet;
    }
    public function exchangeAmount(): int
    {
        return $this->wallet->exchangeAmount();
    }

    public function customerAmount(): int
    {
        return $this->wallet->customerAmount();
    }

    public function customerCoins(): Coins
    {
        return $this->wallet->customerCoins();
    }

    public function addCustomerCoin(Coin $coin): void
    {
        $this->wallet->addCustomerCoin(coin: $coin);
        $this->record(
            domainEvent: new CustomerHasInsertACoinEvent(
                coinAmount: $coin->amount()
            )
        );
    }

    public function refundCustomerCoins()
    {
        $coinsAmount = $this->customerAmount();
        $this->wallet()->refundCustomerCoins();
        $this->record(domainEvent: new CustomerCoinsWasRefundedEvent(
            coinsAmount: $coinsAmount
        ));
    }
}
