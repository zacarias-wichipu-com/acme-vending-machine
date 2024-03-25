<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application\Refund;

use Acme\VendingMachine\Application\Refund\RefundCustomerWalletCommand;
use Acme\VendingMachine\Application\Refund\RefundCustomerWalletCommandHandler;
use Acme\VendingMachine\Domain\Exception\NotServiceAvailableException;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class RefundCustomerWalletCommandHandlerTest extends TestCase
{
    private VendingMachineRepository&MockObject $repository;
    private RefundCustomerWalletCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(originalClassName: VendingMachineRepository::class);
        $this->handler = new RefundCustomerWalletCommandHandler(repository: $this->repository);
    }

    /**
     * It Should Throw A Not Service Available Exception If Is Not In Service
     *
     * @group refund_customer_wallet_command_handler
     * @group unit
     */
    public function testItShouldThrowANotServiceAvailableExceptionIfIsNotInService(): void
    {
        $this->expectException(NotServiceAvailableException::class);
        $vendingMachine = VendingMachineMother::defaultMachine();
        $this->repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $this->repository->expects($this->never())->method('save')->with($vendingMachine);
        ($this->handler)(new RefundCustomerWalletCommand());
    }
}
