<?php

declare(strict_types=1);

namespace Acme\Shared\Domain;

use DateTimeImmutable;
use DateTimeInterface;

final class DateUtils
{
    public static function dateToString(DateTimeInterface $date): string
    {
        return $date->format(DateTimeInterface::ATOM);
    }

    public static function stringToDate(string $date): DateTimeImmutable
    {
        return new DateTimeImmutable($date);
    }
}
