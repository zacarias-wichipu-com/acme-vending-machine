<?php

declare(strict_types=1);

namespace Acme\Product\Domain;

enum ProductType: string
{
    case WATER = 'water';
    case JUICE = 'juice';
    case SODA = 'soda';
}
