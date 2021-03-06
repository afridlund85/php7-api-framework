<?php

namespace Asd\Collections;

use OutOfRangeException;
use Asd\Collections\CollectionInterface;
use Asd\Collections\ListInterface;
use Asd\Collections\Collection;
use Asd\Collections\IteratorTrait;

/**
 * Immutable List of values, methods inspired from Java's List interface.
 * Uses Iterator trait which makes implementations work with foreach
 * Does not allow gaps in indexes.
 * Mutations always return cloned version with changes.
 */
class ArrayList extends Collection implements ListInterface
{
    /**
     * Trait with iterator methods
     */
    use IteratorTrait {
        rewind as IteratorRewind;
    }

    /**
     * Iterator rewind method, defines what collection to iterate by assigning
     * $this->array in the Iterator trait to the list
     * @return void
     */
    public function rewind()
    {
        $this->iteratorArray = $this->list;
        $this->IteratorRewind();
    }

    protected $list = [];

    public function add($obj) : ListInterface
    {
        $clone = clone $this;
        $clone->list[$clone->size] = $obj;
        $clone->size++;
        return $clone;
    }

    public function addAt($obj, int $index) : ListInterface
    {
        if ($index < 0 || $index >= $this->size()) {
            throw new OutOfRangeException('The index does not exists');
        }
        $clone = clone $this;
        $clone->list = array_slice($this->list, 0, $index, true);
        $clone->list[] = $obj;
        if (isset($this->list[$index])) {
            $clone->list = array_merge($clone->list, array_slice($this->list, $index));
        }
        $clone->size++;
        return $clone;
    }

    public function get(int $index)
    {
        if ($index < 0 || $index >= $this->size()) {
            throw new OutOfRangeException('The index does not exists');
        }
        return $this->list[$index];
    }

    public function toArray() : array
    {
        return $this->list;
    }

    /**
     * Removed element should shift the elements folloing it to the left, hence
     * the unset into array_values
     * @param int $index
     * @return ListInterface
     */
    public function remove(int $index) : ListInterface
    {
        if ($index < 0 || $index >= $this->size()) {
            throw new OutOfRangeException('The index does not exists');
        }
        $clone = clone $this;
        unset($clone->list[$index]);
        $clone->list = array_values($clone->list);
        $clone->size--;
        return $clone;
    }

    public function clear() : ListInterface
    {
        $clone = clone $this;
        $clone->list = [];
        $clone->size = 0;
        return $clone;
    }

    public function contains($obj) : bool
    {
        return in_array($obj, $this->list);
    }

    public function indexOf($obj) : int
    {
        $pos = array_search($obj, $this->list);
        return $pos === false ? -1 : $pos;
    }
}
