<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Buy;

use Acme\Product\Domain\Exception\InvalidProductException;
use Acme\Product\Domain\ProductType;
use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Domain\Exception\NotInSellingModeException;
use Acme\VendingMachine\Domain\Status;
use Acme\VendingMachine\Domain\VendingMachine;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final readonly class BuyProductCommandHandler implements CommandHandler
{
    public function __construct(
        private VendingMachineRepository $repository,
        private MessageBusInterface $eventBus
    ) {}

    public function __invoke(BuyProductCommand $command): void
    {
        $vendingMachine = $this->repository->get();
        $this->ensureCaseFrom(vendingMachine: $vendingMachine);
        $product = $this->productFrom(product: $command->product);
        $vendingMachine->buyProduct(product: $product);
    }

    private function ensureCaseFrom(VendingMachine $vendingMachine): void
    {
        if ($vendingMachine->status() !== Status::SELLING) {
            throw new NotInSellingModeException(message: 'There are no sales processes in progress, please insert coins before.');
        }
    }

    private function productFrom(string $product): ProductType
    {
        try {
            return ProductType::from($product);
        } catch (Throwable) {
            throw new InvalidProductException(
                message: sprintf(
                    '%1$s i not a valid product.',
                    $product,
                )
            );
        }
    }
}
