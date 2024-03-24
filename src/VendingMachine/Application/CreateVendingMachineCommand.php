<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application;

use Acme\Shared\Domain\Bus\Command\Command;

final readonly class CreateVendingMachineCommand implements Command {}
