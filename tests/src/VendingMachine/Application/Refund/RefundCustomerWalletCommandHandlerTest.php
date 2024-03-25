<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application\Refund;

use Acme\VendingMachine\Application\Refund\RefundCustomerWalletCommand;
use Acme\VendingMachine\Application\Refund\RefundCustomerWalletCommandHandler;
use Acme\VendingMachine\Domain\Exception\NotServiceAvailableException;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use PHPUnit\Framework\TestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class RefundCustomerWalletCommandHandlerTest extends TestCase
{
    /**
     * It Should Throw A Not Service Available Exception If Is Not In Service
     *
     * @group refund_customer_wallet_command_handler
     * @group unit
     */
    public function testItShouldThrowANotServiceAvailableExceptionIfIsNotInService(): void
    {
        $this->expectException(NotServiceAvailableException::class);
        $repository = $this->createMock(VendingMachineRepository::class);
        $vendingMachine = VendingMachineMother::defaultMachine();
        $repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $repository->expects($this->never())->method('save')->with($vendingMachine);
        $handler = new RefundCustomerWalletCommandHandler(repository: $repository);
        ($handler)(new RefundCustomerWalletCommand());
    }
}
