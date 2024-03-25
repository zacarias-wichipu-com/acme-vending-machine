<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Refund;

use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Domain\Exception\NotServiceAvailableException;
use Acme\VendingMachine\Domain\Status;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Domain\VendingMachineRepository;

final readonly class RefundCustomerWalletCommandHandler implements CommandHandler
{
    public function __construct(
        private VendingMachineRepository $repository
    ) {
    }

    public function __invoke(RefundCustomerWalletCommand $command): void
    {
        $vendingMachine = $this->repository->get();
        $this->ensureRefund($vendingMachine);
    }

    private function ensureRefund(VendingMachine $vendingMachine): void
    {
        if ($vendingMachine->status() !== Status::IN_SERVICE) {
            throw new NotServiceAvailableException(message: 'Not service to refund.');
        }
    }
}
