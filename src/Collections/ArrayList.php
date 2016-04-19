<?php

namespace Asd\Collections;

use OutOfBoundsException;
use Asd\Collections\CollectionInterface;
use Asd\Collections\IteratorTrait;

/**
 * Immutable List of values, methods inspired from Java's List interface.
 * Uses Iterator trait which makes implementations work with foreach
 * Does not allow gaps in indexes.
 * Mutations always return cloned version with changes.
 */
abstract class ArrayList implements CollectionInterface
{
    /**
     * Trait with iterator methods
     */
    use IteratorTrait {
        rewind as traitRewind;
    }

    /**
     * Iterator rewind method, defines what collection to iterate by assigning
     * $this->array in the Iterator trait to the list
     * @return void
     */
    public function rewind()
    {
        $this->array = $this->list;
        $this->traitRewind();
    }

    protected $size = 0;
    protected $list = [];

    public function add($obj) : CollectionInterface
    {
        $clone = clone $this;
        $clone->list[$clone->size] = $obj;
        $clone->size++;
        return $clone;
    }

    public function addAt($obj, int $index) : CollectionInterface
    {
        if ($index < 0 && $index >= $this->size()) {
            throw new OutOfBoundsException('The index does not exists');
        }
        $clone = clone $this;
        $clone->list[] = $obj;
        $clone->size++;
        return $clone;
    }

    public function get($index)
    {
        if ($index < 0 && $index >= $this->size()) {
            throw new OutOfBoundsException('The index does not exists');
        }
        return $this->list[$this->size];
    }

    /**
     * Removed element should shift the elements folloing it to the left, hence
     * the unset into array_values
     * @param int $index
     * @return CollectionInterface
     */
    public function remove($index) : CollectionInterface
    {
        if ($index < 0 && $index >= $this->size()) {
            throw new OutOfBoundsException('The index does not exists');
        }
        $clone = clone $this;
        unset($clone->list[$index]);
        $clone->list = array_values($clone->list);
        $clone->size--;
        return $clone;
    }

    public function contains($obj) : bool
    {
        return in_array($obj, $this->list);
    }

    public function clear()
    {
        $clone = clone $this;
        unset($clone->list);
        return $clone;
    }

    public function size() : int
    {
        return $this->size;
    }

    public function isEmpty() : bool
    {
        return $this->size === 0;
    }

    public function indexOf($obj) : int
    {
        $pos = array_search($obj, $this->list);
        return $pos === false ? -1 : $pos;
    }
}
