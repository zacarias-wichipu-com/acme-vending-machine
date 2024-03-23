<?php

declare(strict_types=1);

namespace Acme\Shared\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/** @template-implements IteratorAggregate<mixed> */
abstract class Collection implements Countable, IteratorAggregate
{
    /**
     * @param  array  $items
     */
    public function __construct(private readonly array $items = [])
    {
        Assert::arrayOf($this->type(), $items);
    }

    final public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items());
    }

    final public function count(): int
    {
        return count($this->items());
    }

    /**
     * @return class-string
     */
    abstract protected function type(): string;

    protected function items(): array
    {
        return $this->items;
    }
}
