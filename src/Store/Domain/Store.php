<?php

declare(strict_types=1);

namespace Acme\Store\Domain;

use Acme\Product\Domain\ProductType;
use Acme\Store\Domain\Exception\InsufficientStockException;
use Exception;

final class Store
{
    /**
     * @template  T  of  Racks
     * @param  T  $racks
     */
    private function __construct(
        private Racks $racks
    ) {}

    /**
     * @template  T  of  Racks
     * @param  T  $racks
     */
    public static function create(Racks $racks): static
    {
        return new static(racks: $racks);
    }

    public static function createDefault(): static
    {
        return static::create(racks: Racks::createDefault());
    }

    public function racks(): Racks
    {
        return $this->racks;
    }

    /**
     * @throws Exception
     */
    public function priceFrom(ProductType $product): int
    {
        /** @var Rack $rack */
        foreach ($this->racks()->getIterator() as $rack) {
            if ($rack->product()->type() === $product) {
                return $rack->price();
            }
        }
        return 0;
    }

    public function updateOnBuy(ProductType $product): void
    {
        $racks = (array) $this->racks()->getIterator();
        /** @var Rack $rack */
        foreach ($racks as $index => $rack) {
            if ($rack->product()->type() === $product) {
                unset($racks[$index]);
                if ($rack->quantity() > 1) {
                    $racks[] = Rack::create($rack->product(), $rack->price(), $rack->quantity() - 1);
                }
                $this->racks = Racks::create(array_values($racks));
                return;
            }
        }
        throw new InsufficientStockException(message: sprintf('There has been an error, the requested product %1$s is not available.', $product->value));
    }
}
