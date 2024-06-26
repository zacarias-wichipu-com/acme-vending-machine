<?php

declare(strict_types=1);

namespace Acme\Shared\Domain;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * @template  T
 * @implements IteratorAggregate<T>
 */
abstract class Collection implements Countable, IteratorAggregate
{
    /**
     * @param  array<T>  $items
     */
    protected function __construct(private readonly array $items = [])
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
     * @return  class-string
     */
    abstract protected function type(): string;

    /**
     * @return  array<T>
     */
    protected function items(): array
    {
        return $this->items;
    }
}
