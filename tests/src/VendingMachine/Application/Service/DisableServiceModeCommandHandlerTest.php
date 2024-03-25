<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application\Service;

use Acme\VendingMachine\Application\Service\DisableServiceModeCommand;
use Acme\VendingMachine\Application\Service\DisableServiceModeCommandHandler;
use Acme\VendingMachine\Domain\Exception\ServiceModeUnavailable;
use Acme\VendingMachine\Domain\Status;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class DisableServiceModeCommandHandlerTest extends TestCase
{
    private VendingMachineRepository&MockObject $repository;
    private DisableServiceModeCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(VendingMachineRepository::class);
        $this->handler = new DisableServiceModeCommandHandler(
            repository: $this->repository,
        );
    }

    /**
     * It Should Throw A Service Mode Unavailable Whe The Machine Is Not In Service
     *
     * @group disable_service_mode_command_handler
     * @group unit
     */
    public function testItShouldThrowAServiceModeUnavailableWheTheMachineIsNotInService(): void
    {
        $this->expectException(exception: ServiceModeUnavailable::class);
        $vendingMachine = VendingMachineMother::defaultMachine();
        $this->repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $this->repository->expects($this->never())->method('save')->with($vendingMachine);
        ($this->handler)(new DisableServiceModeCommand());
    }

    /**
     * It Should Put The Machine In Service Mode
     *
     * @group disable_service_mode_command_handler
     * @group unit
     */
    public function testItShouldPutTheMachineInServiceMode(): void
    {
        $vendingMachine = VendingMachineMother::randomMachine(
            status: Status::IN_SERVICE
        );
        $this->repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $this->repository->expects($this->once())->method('save')->with($vendingMachine);
        ($this->handler)(new DisableServiceModeCommand());
        $this->assertEquals(expected: Status::OPERATIONAL, actual: $vendingMachine->status());
    }
}
