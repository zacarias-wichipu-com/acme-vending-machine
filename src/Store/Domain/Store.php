<?php

declare(strict_types=1);

namespace Acme\Store\Domain;

final readonly class Store
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
}
