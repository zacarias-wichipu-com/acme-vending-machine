<?php

declare(strict_types=1);

namespace Acme\Coin\Domain;

use Acme\Shared\Domain\Bus\Event\DomainEvent;

final class CustomerHasInsertACoinEvent extends DomainEvent
{
    public function __construct(
        private readonly int $cointAmount,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($eventId, $occurredOn);
    }

    #[\Override]
    public static function eventName(): string
    {
        return 'customer.coin.add';
    }

    #[\Override]
    public static function fromPrimitives(
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent
    {
        return new static(
            $body['coinAmount'],
            $eventId,
            $occurredOn
        );
    }

    #[\Override]
    public function toPrimitives(): array
    {
        return [
            'coinAmount' => $this->name,
        ];
    }
}
