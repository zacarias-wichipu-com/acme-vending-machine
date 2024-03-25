<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application\Service;

use Acme\VendingMachine\Application\Service\EnableServiceModeCommand;
use Acme\VendingMachine\Application\Service\EnableServiceModeCommandHandler;
use Acme\VendingMachine\Domain\Exception\ServiceModeUnavailable;
use Acme\VendingMachine\Domain\Status;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class EnableServiceModeCommandHandlerTest extends TestCase
{
    private VendingMachineRepository&MockObject $repository;
    private EnableServiceModeCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(VendingMachineRepository::class);
        $this->handler = new EnableServiceModeCommandHandler(
            repository: $this->repository,
        );
    }

    /**
     * It Should Throw A Service Mode Unavailable Whe The Machine Is In Use
     *
     * @group enable_service_mode_command_handler
     * @group unit
     */
    public function testItShouldThrowAServiceModeUnavailableWheTheMachineIsInUse(): void
    {
        $this->expectException(exception: ServiceModeUnavailable::class);
        $vendingMachine = VendingMachineMother::randomMachine(
            status: Status::SELLING
        );
        $this->repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $this->repository->expects($this->never())->method('save')->with($vendingMachine);
        ($this->handler)(new EnableServiceModeCommand());
    }

    /**
     * It Should Put The Machine In Service Mode
     *
     * @group enable_service_mode_command_handler
     * @group unit
     */
    public function testItShouldPutTheMachineInServiceMode(): void
    {
        $vendingMachine = VendingMachineMother::randomMachine();
        $this->repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $this->repository->expects($this->once())->method('save')->with($vendingMachine);
        ($this->handler)(new EnableServiceModeCommand());
        $this->assertEquals(expected: Status::IN_SERVICE, actual: $vendingMachine->status());
    }
}
