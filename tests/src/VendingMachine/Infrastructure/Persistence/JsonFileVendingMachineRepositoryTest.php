<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Infrastructure\Persistence;

use Acme\VendingMachine\Domain\VendingMachineRepository;
use Acme\VendingMachine\Infrastructure\Persistence\JsonFileVendingMachineRepository;
use Tests\Acme\Shared\Infrastructure\PhpUnit\AppContextInfrastructureTestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class JsonFileVendingMachineRepositoryTest extends AppContextInfrastructureTestCase
{
    /**
     * Sabe A Default Vending Machine
     *
     * @group json_file_vending_machine_repository
     * @group integration
     */
    public function testShouldSaveADefaultVendingMachine(): void
    {
        $repository = new JsonFileVendingMachineRepository();
        $repository->save(VendingMachineMother::defaultMachine());
        $this->assertInstanceOf(VendingMachineRepository::class, $repository);
    }
}
