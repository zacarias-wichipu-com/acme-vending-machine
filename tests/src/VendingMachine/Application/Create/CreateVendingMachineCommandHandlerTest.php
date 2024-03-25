<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application\Create;

use Acme\VendingMachine\Application\Create\CreateVendingMachineCommandHandler;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use PHPUnit\Framework\TestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class CreateVendingMachineCommandHandlerTest extends TestCase
{
    /**
     * It Should Create A Default Vending Machine
     *
     * @group create_vending_machine_command_handler
     * @group unit
     */
    public function testItShouldCreateADefaultVendingMachine(): void
    {
        $repository = $this->createMock(VendingMachineRepository::class);
        $repository->expects($this->once())->method('save')->with(VendingMachineMother::defaultMachine());
        $handler = new CreateVendingMachineCommandHandler(repository: $repository);
        ($handler)(command: new \Acme\VendingMachine\Application\Create\CreateVendingMachineCommand());
    }
}
