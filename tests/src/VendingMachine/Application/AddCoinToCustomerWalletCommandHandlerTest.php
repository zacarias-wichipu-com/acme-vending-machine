<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application;

use Acme\Coin\Domain\AmountInCents;
use Acme\Coin\Domain\InvalidCoinException;
use Acme\VendingMachine\Application\AddCoinToCustomerWalletCommand;
use Acme\VendingMachine\Application\AddCoinToCustomerWalletCommandHandler;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class AddCoinToCustomerWalletCommandHandlerTest extends TestCase
{
    private VendingMachineRepository|MockObject $repository;
    private AddCoinToCustomerWalletCommandHandler $handler;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(originalClassName: VendingMachineRepository::class);
        $this->handler = new AddCoinToCustomerWalletCommandHandler(repository: $this->repository);
    }

    /**
     * It Should Throw A No Valid Coin Exception For Invalid Coins
     *
     * @group add_coin_to_customer_wallet_command_handler
     * @group unit
     */
    public function testItShouldThrowANoValidCoinExceptionForInvalidCoins(): void
    {
        $this->expectException(InvalidCoinException::class);
        $this->repository->expects($this->once())->method('get')->willReturn(VendingMachineMother::defaultMachine());
        ($this->handler)(command: new AddCoinToCustomerWalletCommand(amount: 20));
    }

    /**
     * It Should Add The Coin To The Customer Wallet
     *
     * @group add_coin_to_customer_wallet_command_handler
     * @group unit
     */
    public function testItShouldAddTheCoinToTheCustomerWallet(): void
    {
        $vendingMachine = VendingMachineMother::defaultMachine();
        $this->repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $this->repository->expects($this->once())->method('save')->with($vendingMachine);
        ($this->handler)(command: new AddCoinToCustomerWalletCommand(amount: AmountInCents::TWENTY_FIVE->value));
    }
}
