<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application\Service;

use Acme\VendingMachine\Application\Service\EnableServiceModeCommand;
use Acme\VendingMachine\Application\Service\EnableServiceModeCommandHandler;
use Acme\VendingMachine\Domain\Exception\ServiceModeUnavailable;
use Acme\VendingMachine\Domain\Status;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use PHPUnit\Framework\TestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class EnableServiceModeCommandHandlerTest extends TestCase
{
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
        $repository = $this->createMock(originalClassName: VendingMachineRepository::class);
        $repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $repository->expects($this->never())->method('save')->with($vendingMachine);
        $handler = new EnableServiceModeCommandHandler($repository);
        ($handler)(new EnableServiceModeCommand());
    }
}
