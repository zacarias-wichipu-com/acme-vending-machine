<?php

declare(strict_types=1);

namespace Acme\Product\Domain;

final readonly class Product
{
    private function __construct(
        private ProductType $type
    ) {
    }

    public static function createFromType(ProductType $type): static
    {
        return new static(type: $type);
    }

    public function type(): ProductType
    {
        return $this->type;
    }
}
