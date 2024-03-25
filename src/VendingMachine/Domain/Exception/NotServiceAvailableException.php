<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Domain\Exception;

use DomainException;
use Throwable;

final class NotServiceAvailableException extends DomainException
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

{
}
