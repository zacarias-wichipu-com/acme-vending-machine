<?php

declare(strict_types=1);

namespace Acme\Store\Domain;

use Acme\Product\Domain\Product;

final readonly class Rack
{
    private function __construct(
        private Product $product,
        private int $price,
        private int $quantity,
    ) {
    }
}
