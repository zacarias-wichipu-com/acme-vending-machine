<?php

declare(strict_types=1);

namespace Acme\Coin\Domain\Exception;

use DomainException;
use Throwable;

final class InvalidCoinException extends DomainException
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
