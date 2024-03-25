<?php

declare(strict_types=1);

namespace Tests\Acme\Shared\Infrastructure\Bus\Event;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class InMemoryEventBus implements MessageBusInterface
{
    #[\Override]
    public function dispatch(object $message, array $stamps = []): Envelope
    {
        return new Envelope($message);
    }
}
