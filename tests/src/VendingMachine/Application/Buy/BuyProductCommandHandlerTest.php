<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application\Buy;

use Acme\Coin\Domain\AmountInCents;
use Acme\Product\Domain\Exception\InvalidProductException;
use Acme\Product\Domain\ProductType;
use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Application\Buy\BuyProductCommand;
use Acme\VendingMachine\Application\Buy\BuyProductCommandHandler;
use Acme\VendingMachine\Domain\Exception\NotInSellingModeException;
use Acme\VendingMachine\Domain\Status;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Acme\Wallet\Domain\Exception\InsufficientAmountException;
use Acme\Wallet\Domain\Exception\InsufficientExchangeException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Acme\Shared\Infrastructure\Bus\Event\InMemoryEventBus;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class BuyProductCommandHandlerTest extends TestCase implements CommandHandler
{
    private VendingMachineRepository&MockObject $repository;
    private BuyProductCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(VendingMachineRepository::class);
        $this->handler = new BuyProductCommandHandler(
            repository: $this->repository,
            eventBus: new InMemoryEventBus(),
        );
    }

    /**
     * It Should Throw A Not In Selling Mode Exception For Invalid States
     *
     * @group buy_product_command_handler
     * @group unit
     */
    public function testItShouldThrowANotInSellingModeExceptionForInvalidStates(): void
    {
        $this->expectException(NotInSellingModeException::class);
        $vendingMachine = VendingMachineMother::defaultMachine();
        $this->repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $this->repository->expects($this->never())->method('save')->with($vendingMachine);
        ($this->handler)(new BuyProductCommand(product: ProductType::WATER->value));
    }

    /**
     * It Should Throw An Invalid Product Exception For Invalid Products
     *
     * @group buy_product_command_handler
     * @group unit
     */
    public function testItShouldThrowAnInvalidProductExceptionForInvalidProducts(): void
    {
        $this->expectException(InvalidProductException::class);
        $vendingMachine = VendingMachineMother::randomMachine(
            status: Status::SELLING,
            wallet: VendingMachineMother::randomWallet(
                customerCoins: VendingMachineMother::randomCoins()
            )
        );
        $this->repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $this->repository->expects($this->never())->method('save')->with($vendingMachine);
        ($this->handler)(new BuyProductCommand(product: 'wrong product'));
    }

    /**
     * It Should Throw An Insufficient Amount Exception For Insufficient Customer Balance
     *
     * @group buy_product_command_handler
     * @group unit
     */
    public function testItShouldThrowAnInsufficientAmountExceptionForInsufficientCustomerBalance(): void
    {
        $this->expectException(InsufficientAmountException::class);
        $vendingMachine = $this->anInsufficientBalanceVendingMachine();
        $this->repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $this->repository->expects($this->never())->method('save')->with($vendingMachine);
        ($this->handler)(new BuyProductCommand(product: ProductType::WATER->value));
    }

    /**
     * It Should Throw An Insufficient Exchange Exception For Insufficient Exchange
     *
     * @group buy_product_command_handler
     */
    public function testItShouldThrowAnInsufficientExchangeExceptionForInsufficientExchange(): void
    {
        $this->expectException(InsufficientExchangeException::class);
        $vendingMachine = $this->anInsufficientExchangeVendingMachineForBuyWater();
        $this->repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $this->repository->expects($this->never())->method('save')->with($vendingMachine);
        ($this->handler)(new BuyProductCommand(product: ProductType::WATER->value));
    }

    private function anInsufficientBalanceVendingMachine(): VendingMachine
    {
        return VendingMachineMother::randomMachine(
            status: Status::SELLING,
            store: VendingMachineMother::randomStore(
                racks: [
                    VendingMachineMother::randomRack(
                        product: VendingMachineMother::randomProduct(ProductType::JUICE),
                        quantity: 2,
                        price: 100
                    ),
                    VendingMachineMother::randomRack(
                        product: VendingMachineMother::randomProduct(ProductType::WATER),
                        quantity: 1,
                        price: 65
                    ),
                    VendingMachineMother::randomRack(
                        product: VendingMachineMother::randomProduct(ProductType::SODA),
                        quantity: 1,
                        price: 150
                    ),
                ]
            ),
            wallet: VendingMachineMother::randomWallet(
                customerCoins: VendingMachineMother::randomCoins(
                    coinBoxes: [
                        VendingMachineMother::coinBoxFrom(amountInCents: AmountInCents::TWENTY_FIVE, quantity: 2),
                        VendingMachineMother::coinBoxFrom(amountInCents: AmountInCents::FIVE, quantity: 2)
                    ]
                )
            )
        );
    }

    private function anInsufficientExchangeVendingMachineForBuyWater(): VendingMachine
    {
        return VendingMachineMother::randomMachine(
            status: Status::SELLING,
            store: VendingMachineMother::randomStore(
                racks: [
                    VendingMachineMother::randomRack(
                        product: VendingMachineMother::randomProduct(ProductType::JUICE),
                        quantity: 2,
                        price: 100
                    ),
                    VendingMachineMother::randomRack(
                        product: VendingMachineMother::randomProduct(ProductType::WATER),
                        quantity: 1,
                        price: 65
                    ),
                    VendingMachineMother::randomRack(
                        product: VendingMachineMother::randomProduct(ProductType::SODA),
                        quantity: 1,
                        price: 150
                    ),
                ]
            ),
            wallet: VendingMachineMother::randomWallet(
                customerCoins: VendingMachineMother::randomCoins(
                    coinBoxes: [
                        VendingMachineMother::coinBoxFrom(amountInCents: AmountInCents::ONE_HUNDRED, quantity: 1),
                    ]
                )
            )
        );
    }
}
