<?php

declare(strict_types=1);

namespace Acme\Wallet\Domain;

use Acme\Shared\Domain\Collection;
use Override;

final class Coins extends Collection
{
    /**
     * @return class-string
     */
    #[Override] protected function type(): string
    {
        return CoinBox::class;
    }
}
