<?php

declare(strict_types=1);

namespace Acme\Store\Domain;

use Acme\Shared\Domain\Collection;

final readonly class Store
{
    /**
     * @template  T
     * @param  Racks<Collection<T>>  $racks
     */
    private function __construct(
        private Racks $racks
    ) {}

    public static function create(Racks $racks): static
    {
        return new static(racks: $racks);
    }

    public static function createDefault(): static
    {
        return static::create(racks: Racks::createDefault());
    }
}
