<?php

declare(strict_types=1);

namespace Acme\Shared\Domain;

use InvalidArgumentException;

final class Assert
{
    /**
     * @param  class-string  $class
     * @param  array<mixed>  $items
     */
    public static function arrayOf(string $class, array $items): void
    {
        foreach ($items as $item) {
            self::instanceOf($class, $item);
        }
    }

    public static function instanceOf(string $class, mixed $item): void
    {
        if (! $item instanceof $class) {
            throw new InvalidArgumentException(sprintf(
                'The object <%s> is not an instance of <%s>',
                $class,
                $item::class
            ));
        }
    }
}
