<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Service;

use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Domain\VendingMachineRepository;

final readonly class EnableServiceModeCommandHandler implements CommandHandler
{
    public function __construct(
        private VendingMachineRepository $repository,
    ) {}

    public function __invoke(EnableServiceModeCommand $command): void
    {
        $vendingMachine = $this->repository->get();
        $vendingMachine->putInService();
        $this->repository->save($vendingMachine);
    }
}
