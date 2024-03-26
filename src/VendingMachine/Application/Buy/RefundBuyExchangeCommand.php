<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\Buy;

use Acme\Shared\Domain\Bus\Command\Command;

final readonly class RefundBuyExchangeCommand implements Command
{
    public function __construct() {}
}
