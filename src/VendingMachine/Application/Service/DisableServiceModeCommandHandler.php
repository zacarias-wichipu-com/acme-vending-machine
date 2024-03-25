<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Service;

use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Domain\Exception\ServiceModeUnavailable;
use Acme\VendingMachine\Domain\Status;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Domain\VendingMachineRepository;

final readonly class DisableServiceModeCommandHandler implements CommandHandler
{
    public function __construct(
        private VendingMachineRepository $repository,
    ) {}

    public function __invoke(DisableServiceModeCommand $command): void
    {
        $vendingMachine = $this->repository->get();
        $this->ensureCaseFrom($vendingMachine);
        $vendingMachine->putOperational();
        $this->repository->save($vendingMachine);
    }

    private function ensureCaseFrom(VendingMachine $vendingMachine): void
    {
        if ($vendingMachine->status() !== Status::IN_SERVICE) {
            throw new ServiceModeUnavailable(message: 'The vending machine is not in service.');
        }
    }
}
