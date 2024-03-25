<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Buy;

use Acme\Shared\Domain\Bus\Command\Command;

final readonly class BuyProductCommand implements Command
{
    public function __construct(public string $product) {}
}
