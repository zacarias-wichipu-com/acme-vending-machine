<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Infrastructure\Persistence;

use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Acme\VendingMachine\Infrastructure\Persistence\JsonFileVendingMachineRepository;
use Tests\Acme\Shared\Infrastructure\PhpUnit\AppContextInfrastructureTestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class JsonFileVendingMachineRepositoryTest extends AppContextInfrastructureTestCase
{
    /**
     * It Should Save A Default Vending Machine
     *
     * @group json_file_vending_machine_repository
     * @group integration
     */
    public function testItShouldSaveADefaultVendingMachine(): void
    {
        $repository = new JsonFileVendingMachineRepository();
        $repository->save(VendingMachine::createDefault());
        $this->assertInstanceOf(VendingMachineRepository::class, $repository);
    }

    /**
     * It Should Be Able To Get A Default Vending Machine
     *
     * @group json_file_vending_machine_repository
     * @group integration
     */
    public function testItShouldBeAbleToGetADefaultVendingMachine(): void
    {
        $repository = new JsonFileVendingMachineRepository();
        $repository->save(VendingMachine::createDefault());
        $vendingMachine = $repository->get();
        $expectedDefaultVendingMachine = VendingMachineMother::defaultMachine();
        $this->assertEquals(
            expected: $expectedDefaultVendingMachine->status(),
            actual: $vendingMachine->status()
        );
    }

    /**
     * It Should Be Able To Get A Random Vending Machine
     *
     * @group json_file_vending_machine_repository
     * @group integration
     */
    public function testItShouldBeAbleToGetARandomVendingMachine(): void
    {
        $expectedRandomVendingMachine = VendingMachineMother::randomMachine();
        $repository = new JsonFileVendingMachineRepository();
        $repository->save($expectedRandomVendingMachine);
        $vendingMachine = $repository->get();
        $this->assertEquals(
            expected: $expectedRandomVendingMachine->status(),
            actual: $vendingMachine->status()
        );
    }
}
