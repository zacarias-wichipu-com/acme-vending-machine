<?php

declare(strict_types=1);

namespace Acme\Coin\Domain;

enum AmountInCents: int
{
    case FIVE = 5;
    case TEN = 10;
    case TWENTY_FIVE = 25;
    case ONE_HUNDRED = 100;
}
