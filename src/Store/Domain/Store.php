<?php

declare(strict_types=1);

namespace Acme\Store\Domain;

final readonly class Store
{
    private function __construct(
        private Racks $racks
    ) {
    }
}
