<?php

declare(strict_types=1);

namespace Acme\Store\Domain;

use Acme\Product\Domain\Product;
use Acme\Product\Domain\ProductType;
use Acme\Shared\Domain\Collection;
use Override;

final class Racks extends Collection
{
    public static function createDefault(): static
    {
        return new static([
            Rack::createDefault(
                product: Product::createFromType(ProductType::JUICE),
                price: 100,
                quantity: 2
            ),
            Rack::createDefault(
                product: Product::createFromType(ProductType::SODA),
                price: 150,
                quantity: 2
            ),
            Rack::createDefault(
                product: Product::createFromType(ProductType::WATER),
                price: 65,
                quantity: 2
            ),
        ]);
    }

    /**
     * @return class-string
     */
    #[Override]
    protected function type(): string
    {
        return Rack::class;
    }
}
