<?php

declare(strict_types=1);

namespace Acme\Product\Domain\Exception;

use DomainException;
use Throwable;

final class InvalidProductException extends DomainException
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
