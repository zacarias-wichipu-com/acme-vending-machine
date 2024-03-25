<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Infrastructure\Persistence;

use Acme\VendingMachine\Domain\Exception\VendorRepositoryException;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Acme\VendingMachine\Infrastructure\Persistence\JsonFileVendingMachineRepository;
use Tests\Acme\Shared\Infrastructure\PhpUnit\AppContextInfrastructureTestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class JsonFileVendingMachineRepositoryTest extends AppContextInfrastructureTestCase
{
    private VendingMachineRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new JsonFileVendingMachineRepository(
            persistenceFilePath: dirname(__DIR__) . '/../../../../var/persistence/test_vending_machine.json',
            filesystem: $this->service('filesystem'),
        );
    }

    /**
     * It Should Save A Default Vending Machine
     *
     * @group json_file_vending_machine_repository
     * @group integration
     */
    public function testItShouldSaveADefaultVendingMachine(): void
    {
        $this->repository->save(vendingMachine: VendingMachine::createDefault());
        $this->assertInstanceOf(expected: VendingMachineRepository::class, actual: $this->repository);
    }

    /**
     * It Should Throw A Vending Repository Exception If The Persistence File Does Not Exists
     *
     * @group json_file_vending_machine_repository
     * @group integration
     */
    public function testItShouldThrowAVendingRepositoryExceptionIfThePersistenceFileDoesNotExists(): void
    {
        $this->expectException(VendorRepositoryException::class);
        $this->repository = new JsonFileVendingMachineRepository(
            persistenceFilePath: 'invalid/test_vending_machine.json',
            filesystem: $this->service('filesystem'),
        );
        $this->repository->get();
    }

    /**
     * It Should Be Able To Get A Default Vending Machine
     *
     * @group json_file_vending_machine_repository
     * @group integration
     */
    public function testItShouldBeAbleToGetADefaultVendingMachine(): void
    {
        $this->repository->save(vendingMachine: VendingMachine::createDefault());
        $vendingMachine = $this->repository->get();
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
        $this->repository->save(vendingMachine: VendingMachine::createDefault());
        $this->repository->save($expectedRandomVendingMachine);
        $vendingMachine = $this->repository->get();
        $this->assertEquals(
            expected: [
                $expectedRandomVendingMachine->status(),
                $expectedRandomVendingMachine->exchangeAmount(),
            ],
            actual: [
                $vendingMachine->status(),
                $vendingMachine->exchangeAmount(),
            ]
        );
    }
}
