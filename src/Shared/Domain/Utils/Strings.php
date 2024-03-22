<?php

declare(strict_types=1);

namespace Acme\Shared\Domain\Utils;

final class Strings
{
    public static function toSnakeCase(string $text): string
    {
        return ctype_lower($text) ? $text : strtolower((string) preg_replace('/([^A-Z\s])([A-Z])/', "$1_$2", $text));
    }
}
