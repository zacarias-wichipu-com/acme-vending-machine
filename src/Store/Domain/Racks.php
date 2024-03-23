<?php

declare(strict_types=1);

namespace Acme\Store\Domain;

use Acme\Shared\Domain\Collection;
use Override;

final class Racks extends Collection
{
    /**
     * @return class-string
     */
    #[Override] protected function type(): string
    {
        return Rack::class;
    }
}
