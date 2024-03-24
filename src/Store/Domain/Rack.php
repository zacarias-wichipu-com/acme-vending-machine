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

    public static function create(Product $product, int $price, int $quantity): static
    {
        return new static(
            product: $product,
            quantity: $quantity,
            price: $price
        );
    }

    public function price(): int
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function product(): Product
    {
        return $this->product;
    }
}
