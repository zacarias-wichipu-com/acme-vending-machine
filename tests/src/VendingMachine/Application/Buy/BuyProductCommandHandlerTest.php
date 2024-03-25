<?php

declare(strict_types=1);

namespace Tests\Acme\VendingMachine\Application\Buy;

use Acme\Product\Domain\ProductType;
use Acme\Shared\Domain\Bus\Command\CommandHandler;
use Acme\VendingMachine\Application\Buy\BuyProductCommand;
use Acme\VendingMachine\Application\Buy\BuyProductCommandHandler;
use Acme\VendingMachine\Domain\Exception\NotInSellingModeException;
use Acme\VendingMachine\Domain\VendingMachineRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Acme\Shared\Infrastructure\Bus\Event\InMemoryEventBus;
use Tests\Acme\VendingMachine\Domain\VendingMachineMother;

class BuyProductCommandHandlerTest extends TestCase implements CommandHandler
{
    private VendingMachineRepository&MockObject $repository;
    private BuyProductCommandHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(VendingMachineRepository::class);
        $this->handler = new BuyProductCommandHandler(
            repository: $this->repository,
            eventBus: new InMemoryEventBus(),
        );
    }

    /**
     * It Should Throw A Not In Selling Mode Exception For Invalid States
     *
     * @group buy_product_command_handler
     * @group unit
     */
    public function testItShouldThrowANotInSellingModeExceptionForInvalidStates(): void
    {
        $this->expectException(NotInSellingModeException::class);
        $vendingMachine = VendingMachineMother::defaultMachine();
        $this->repository->expects($this->once())->method('get')->willReturn($vendingMachine);
        $this->repository->expects($this->never())->method('save')->with($vendingMachine);
        ($this->handler)(new BuyProductCommand(product: ProductType::WATER->value));
    }
}
