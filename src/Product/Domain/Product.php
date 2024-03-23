<?php

declare(strict_types=1);

namespace Acme\Product\Domain;

final readonly class Product
{
    private function __construct(
        private ProductType $type
    ) {
    }
}
