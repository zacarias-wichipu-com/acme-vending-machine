<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Create;

use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Domain\VendingMachineRepository;

final readonly class CreateVendingMachineCommandHandler implements CommandHandler
{
    public function __construct(
        private VendingMachineRepository $repository
    ) {}

    /**
     * @psalm-suppress UnusedParam
     */
    public function __invoke(CreateVendingMachineCommand $command): void
    {
        $this->repository->save(VendingMachine::createDefault());
    }
}
