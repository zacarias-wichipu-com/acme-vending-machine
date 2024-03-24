<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application;

use Acme\Shared\Domain\Bus\Query\Query;
use Acme\Shared\Domain\Bus\Query\QueryHandler;
use Acme\Shared\Domain\Bus\Query\Response;
use Acme\VendingMachine\Domain\VendingMachineRepository;

final readonly class GetVendingMachineQueryHandler implements QueryHandler
{
    public function __construct(
        private VendingMachineRepository $repository
    ) {}

    public function __invoke(Query $query): ?Response
    {
        $vendingMachine = $this->repository->get();
        return new VendingMachineResponse(vendingMachine: $vendingMachine);
    }
}
