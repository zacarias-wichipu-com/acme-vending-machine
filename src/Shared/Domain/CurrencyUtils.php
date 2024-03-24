<?php

declare(strict_types=1);

namespace Acme\Shared\Domain;

final class CurrencyUtils
{
    public static function toDecimal(int $amount): float
    {
        return $amount * 10 ** -2;
    }
}
