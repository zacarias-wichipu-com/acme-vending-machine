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

    public static function createDefault(): static
    {
        return new static(racks: Racks::createDefault());
    }
}
