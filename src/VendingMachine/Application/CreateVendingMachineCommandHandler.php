<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application;

use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Domain\VendingMachineRepository;

final readonly class CreateVendingMachineCommandHandler
{
    public function __construct(
        private VendingMachineRepository $repository
    ) {}

    public function __invoke(CreateVendingMachineCommand $command): void
    {
        $this->repository->save(VendingMachine::createDefault());
    }
}
