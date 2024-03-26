<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Domain\Event;

use Acme\Shared\Domain\Bus\Event\DomainEvent;

final class BuyProductExchangeWasRefundedEvent extends DomainEvent
{
    public function __construct(
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($eventId, $occurredOn);
    }

    #[\Override]
    public static function eventName(): string
    {
        return 'wallet.redund.exchange';
    }

    #[\Override]
    public static function fromPrimitives(
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new static(
            $eventId,
            $occurredOn
        );
    }

    #[\Override]
    public function toPrimitives(): array
    {
        return [];
    }
}
