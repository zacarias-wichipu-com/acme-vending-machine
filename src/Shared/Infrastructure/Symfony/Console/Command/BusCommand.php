<?php

declare(strict_types=1);

namespace Acme\Shared\Infrastructure\Symfony\Console\Command;

use Acme\Shared\Domain\Bus\Command\Command;
use Acme\Shared\Domain\Bus\Query\Query;
use Acme\Shared\Domain\Bus\Query\Response;

interface BusCommand
{
    public function dispatch(Command $command): void;
    public function ask(Query $query): ?Response;
}
