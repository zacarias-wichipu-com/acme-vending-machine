<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Buy;

use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Exception;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class RefundBuyExchangeCommandHandler implements CommandHandler
{
    public function __construct(
        private VendingMachineRepository $repository,
        private MessageBusInterface $eventBus
    ) {}

    /**
     * @throws Exception
     */
    public function __invoke(RefundBuyExchangeCommand $command): void
    {
        $vendingMachine = $this->repository->get();
        $vendingMachine->refundBuyExchange();
        $this->repository->save($vendingMachine);
        foreach ($vendingMachine->pullDomainEvents() as $domainEvent) {
            $this->eventBus->dispatch($domainEvent);
        }
    }
}
