<?php

declare(strict_types=1);

namespace Tests\Acme\Shared\Infrastructure\PhpUnit;

use Acme\Ui\Cli\Kernel;

abstract class AppContextInfrastructureTestCase extends InfrastructureTestCase
{
    protected function kernelClass(): string
    {
        return Kernel::class;
    }
}
