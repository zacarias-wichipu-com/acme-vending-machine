<?php

declare(strict_types=1);

namespace Acme\Store\Domain;

use Acme\Product\Domain\Product;
use Acme\Product\Domain\ProductType;
use Acme\Shared\Domain\Collection;
use Override;

/**
 * @template  T  of  Rack
 * @extends Collection<T>
 */
final class Racks extends Collection
{
    /**
     * @param  array<int, Rack>  $racks
     */
    public static function create(array $racks): static
    {
        return new static($racks);
    }

    public static function createDefault(): static
    {
        return static::create([
            Rack::create(
                product: Product::createFromType(ProductType::JUICE),
                price: 100,
                quantity: 2
            ),
            Rack::create(
                product: Product::createFromType(ProductType::SODA),
                price: 150,
                quantity: 2
            ),
            Rack::create(
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
