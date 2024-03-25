<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Domain\Event;

use Acme\Shared\Domain\Bus\Event\DomainEvent;

final class CustomerCoinsWasRefundedEvent extends DomainEvent
{
    public function __construct(
        private readonly int $coinsAmount,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($eventId, $occurredOn);
    }

    #[\Override]
    public static function eventName(): string
    {
        return 'customer.coins.refund';
    }

    public function coinAmount(): int
    {
        return $this->coinsAmount;
    }

    #[\Override]
    public static function fromPrimitives(
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new static(
            $body['coinsAmount'],
            $eventId,
            $occurredOn
        );
    }

    #[\Override]
    public function toPrimitives(): array
    {
        return [
            'coinsAmount' => $this->coinsAmount,
        ];
    }
}
