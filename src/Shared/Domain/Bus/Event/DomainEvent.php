<?php

declare(strict_types=1);

namespace Acme\Shared\Domain\Bus\Event;

use Acme\Shared\Domain\DateUtils;
use Acme\Shared\Domain\UuidUtils;
use DateTimeImmutable;

abstract class DomainEvent
{
	private readonly string $eventId;
	private readonly string $occurredOn;

	public function __construct(string $eventId = null, string $occurredOn = null)
	{
		$this->eventId = $eventId ?: UuidUtils::uuid4();
		$this->occurredOn = $occurredOn ?: DateUtils::dateToString(new DateTimeImmutable());
	}

	abstract public static function fromPrimitives(
		array $body,
		string $eventId,
		string $occurredOn
	): self;

	abstract public static function eventName(): string;

	abstract public function toPrimitives(): array;

	final public function eventId(): string
	{
		return $this->eventId;
	}

	final public function occurredOn(): string
	{
		return $this->occurredOn;
	}
}
