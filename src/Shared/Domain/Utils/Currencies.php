<?php

declare(strict_types=1);

namespace Acme\Shared\Domain\Utils;

use function round;

final class Currencies
{
    public static function calculateRateFromAmount(float $amount, float $taxRate): float
    {
        return self::round(
            num: ($taxRate / 100) * $amount
        );
    }

    public static function round(float $num): float
    {
        return round(
            num: $num,
            precision: 2
        );
    }
}
