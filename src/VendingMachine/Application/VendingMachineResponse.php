<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application;

use Acme\Shared\Domain\Bus\Query\Response;
use Acme\VendingMachine\Domain\VendingMachine;

final readonly class VendingMachineResponse implements Response
{
    public function __construct(
        private VendingMachine $vendingMachine,
        private VendingMachineResponseSerializer $serializer
    ) {}

    #[\Override]
    public function toArray(): array
    {
        return $this->serializer->normalize($this->vendingMachine);
    }
}
