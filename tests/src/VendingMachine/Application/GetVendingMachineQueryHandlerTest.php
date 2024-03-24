<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application;

use Acme\Shared\Domain\Bus\Query\Query;
use Acme\Shared\Domain\Bus\Query\QueryHandler;
use Acme\Shared\Domain\Bus\Query\Response;
use Acme\VendingMachine\Application\GetVendingMachineQuery;
use Acme\VendingMachine\Application\GetVendingMachineQueryHandler;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use PHPUnit\Framework\TestCase;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class GetVendingMachineQueryHandlerTest extends TestCase
{
    /**
     * It Should Get The Vending Machine
     *
     * @group get_vending_machine_query_handler
     * @group unit
     */
    public function testItShouldGetTheVendingMachine(): void
    {
        $repository = $this->createMock(VendingMachineRepository::class);
        $repository->expects($this->once())->method('get')->with()->willReturn(VendingMachineMother::randomMachine());
        $query = new GetVendingMachineQuery();
        $handler = new GetVendingMachineQueryHandler(repository: $repository);
        $response = ($handler)(query: $query);
        $this->assertInstanceOf(expected: Query::class, actual: $query);
        $this->assertInstanceOf(expected: QueryHandler::class, actual: $handler);
        $this->assertInstanceOf(expected: Response::class, actual: $response);
    }
}
