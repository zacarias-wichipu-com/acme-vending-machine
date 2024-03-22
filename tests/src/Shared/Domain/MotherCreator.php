<?php

declare(strict_types=1);

namespace Tests\Acme\Shared\Domain;

use Faker\Factory;
use Faker\Generator;

final class MotherCreator
{
    private static ?Generator $faker = null;

    public static function random(): Generator
    {
        return self::$faker ??= Factory::create();
    }
}
