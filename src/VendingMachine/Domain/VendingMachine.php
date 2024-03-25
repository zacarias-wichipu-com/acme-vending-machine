<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Domain;

use Acme\Coin\Domain\Coin;
use Acme\Product\Domain\ProductType;
use Acme\Shared\Domain\Aggregate\AggregateRoot;
use Acme\Shared\Domain\CurrencyUtils;
use Acme\Store\Domain\Store;
use Acme\VendingMachine\Domain\Event\CustomerCoinsWasRefundedEvent;
use Acme\VendingMachine\Domain\Event\CustomerHasInsertACoinEvent;
use Acme\VendingMachine\Domain\Exception\NotInSellingModeException;
use Acme\VendingMachine\Domain\Exception\ServiceModeUnavailable;
use Acme\Wallet\Domain\Coins;
use Acme\Wallet\Domain\Exception\InsufficientAmountException;
use Acme\Wallet\Domain\Exception\InsufficientExchangeException;
use Acme\Wallet\Domain\Wallet;
use Exception;

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

    public function putOperational(): void
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

    public function exchangeCoins(): Coins
    {
        return $this->wallet->exchangeCoins();
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

    public function refundCustomerCoins(): void
    {
        if ($this->status !== Status::SELLING) {
            throw new NotInSellingModeException(message: 'There are no sales processes in progress to refund.');
        }
        $coinsAmount = $this->customerAmount();
        $this->wallet()->refundCustomerCoins();
        $this->record(domainEvent: new CustomerCoinsWasRefundedEvent(
            coinsAmount: $coinsAmount
        ));
    }

    /**
     * @throws Exception
     */
    public function buyProduct(ProductType $product): void
    {
        $this->ensureBuyBalance($product);
        $this->ensureBuyExchange($product);
        // Update store
        // Update exchange wallet
        // Reset customer wallet
        // Record ProductWasBoughtDomainEvent
    }

    private function ensureBuyBalance(ProductType $product): void
    {
        $productPrice = $this->store()->priceFrom(product: $product);
        $customerAmount = $this->customerAmount();
        if ($customerAmount < $productPrice) {
            throw new InsufficientAmountException(
                message: sprintf(
                    'The balance %1$s is insufficient for product %2$s, please add %3$s more to complete the amount.',
                    CurrencyUtils::toDecimalString($customerAmount),
                    $product->value,
                    CurrencyUtils::toDecimalString($productPrice - $customerAmount),
                )
            );
        }
    }

    private function ensureBuyExchange(ProductType $product): void
    {
        $productPrice = $this->store()->priceFrom(product: $product);
        $customerAmount = $this->customerAmount();
        $exchangeAmount = $customerAmount - $productPrice;
        if ($exchangeAmount === 0) {
            return;
        }
        $flatExchange = $this->exchangeCoins()->flatCoins();
        $flatAvailableExchange = $flatExchange;
        $flatCustomerExchange = [];
        foreach ($flatAvailableExchange as $index => $coins) {
            $coinAmount = array_key_first($coins);
            $availableCoinQuantity = $coins[$coinAmount];
            if ($exchangeAmount >= $coinAmount) {
                $customerCoinQuantity = min($availableCoinQuantity, intdiv($exchangeAmount, $coinAmount));
                $availableCoinQuantity -= $customerCoinQuantity;
                $exchangeAmount -= $customerCoinQuantity * $coinAmount;
                unset($flatAvailableExchange[$index]);
                $flatAvailableExchange[] = [$coinAmount => $availableCoinQuantity];
                $flatCustomerExchange[] = [$coinAmount => $customerCoinQuantity];
            }
        }
        if ($exchangeAmount > 0) {
            throw new InsufficientExchangeException(
                message: 'We do not have enough exchange, you can select another product or request a refund of the coins.'
            );
        }
    }
}
