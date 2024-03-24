<?php

declare(strict_types=1);

namespace Acme\Shared\Infrastructure\Symfony\Console\Command;

use Acme\Shared\Domain\Bus\Command\Command;
use Acme\Shared\Domain\Bus\Query\Query;
use Acme\Shared\Domain\Bus\Query\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class SymfonyMessengerBusCommand implements BusCommand
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private MessageBusInterface $queryBus,
    ) {}

    #[\Override]
    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }

    #[\Override]
    public function ak(Query $query): ?Response
    {
        /** @var HandledStamp $stamp */
        $stamp = $this->queryBus->dispatch($query)->last(HandledStamp::class);
        return $stamp->getResult();
    }
}
