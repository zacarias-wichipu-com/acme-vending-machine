<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Application\AddCoinToCustomerWallet;

use Acme\Shared\Domain\Bus\Command\Command;

final readonly class AddCoinToCustomerWalletCommand implements Command
{
    public function __construct(public int $amount) {}
}
