<?php

declare(strict_types=1);

namespace Acme\Shared\Domain;

final class CurrencyUtils
{
    public static function toDecimal(int $amount): float
    {
        return $amount * 10 ** -2;
    }

    public static function toDecimalString(int $amount): string
    {
        return number_format(
            num: self::toDecimal($amount),
            decimals: 2,
            decimal_separator: '.',
            thousands_separator: ''
        );
    }
}
