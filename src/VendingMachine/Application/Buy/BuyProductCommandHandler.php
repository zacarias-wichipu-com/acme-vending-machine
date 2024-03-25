<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Buy;

use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Domain\Exception\NotInSellingModeException;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class BuyProductCommandHandler implements CommandHandler
{

    public function __construct(
        private VendingMachineRepository $repository,
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(BuyProductCommand $command): void
    {
        $vendingMachine = $this->repository->get();
        throw new NotInSellingModeException(message: 'There are no sales processes in progress, please insert coins before.');
    }
}
