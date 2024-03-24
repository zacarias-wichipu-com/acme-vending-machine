<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application;

use Acme\Shared\Domain\Bus\Query\Response;

final class VendingMachineResponse implements Response
{
    #[\Override]
    public function toArray(): array
    {
        return [];
    }
}
