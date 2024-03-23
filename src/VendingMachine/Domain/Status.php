<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Domain;

enum Status: string
{
    case OPERATIONAL = 'operational';
    case IN_SERVICE = 'in service';
    case SELLING = 'selling';
    case WITHOUT_PRODUCTS = 'without products';
    case WITHOUT_CHANGE = 'no change';
}
