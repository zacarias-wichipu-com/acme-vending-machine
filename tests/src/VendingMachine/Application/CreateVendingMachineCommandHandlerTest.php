<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application;

use Acme\VendingMachine\Domain\VendingMachineRepository;
use PHPUnit\Framework\TestCase;

class CreateVendingMachineCommandHandlerTest extends TestCase
{
    /**
     * Should Create A Default Vending Machine
     *
     * @group create_vending_machine_command_handler
     * @group unit
     */
    public function testShouldCreateADefaultVendingMachine(): void
    {
        $repository = $this->createMock(VendingMachineRepository::class);
        $repository->expects($this->once())->method('save')->with(VendingMachineMother::defaultMachine());
        $handler = new CreateVendingMachineCommandHandler(repository: $repository);
        ($handler)(command: new CreateVendingMachineCommand());
    }
}
