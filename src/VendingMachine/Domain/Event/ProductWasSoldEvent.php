<?php

declare(strict_types=1);

namespace Acme\VendingMachine\Domain\Event;

use Acme\Shared\Domain\Bus\Event\DomainEvent;

final class ProductWasSoldEvent extends DomainEvent
{
    public function __construct(
        private readonly string $productName,
        private readonly int $productPrice,
        string $eventId = null,
        string $occurredOn = null
    ) {
        parent::__construct($eventId, $occurredOn);
    }

    #[\Override]
    public static function eventName(): string
    {
        return 'product.sold';
    }

    public function productName(): string
    {
        return $this->productName;
    }

    public function productPrice(): int
    {
        return $this->productPrice;
    }

    #[\Override]
    public static function fromPrimitives(
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new static(
            $body['productName'],
            $body['productPrice'],
            $eventId,
            $occurredOn
        );
    }

    #[\Override]
    public function toPrimitives(): array
    {
        return [
            'productName' => $this->productName,
            'productPrice' => $this->productPrice,
        ];
    }
}
