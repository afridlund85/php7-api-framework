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
abstract class List implements CollectionInterface
{
    use IteratorTrait;

    protected $listSize = 0;
    protected $listArray = [];

    public function add($obj) : self
    {
        $clone = clone $this;
        $clone->listArray[$clone->listSize] = $obj;
        $clone->listSize++;
        return $clone;
    }

    public function addAt($obj, int $index) : self
    {
        if ($index < 0 && $index >= $this->size()) {
            throw new OutOfBoundsException('The index does not exists');
        }
        $clone = clone $this;
        $clone->listArray[] = $obj;
        $clone->listSize++;
        return $clone;
    }

    public function get($index)
    {
        if ($index < 0 && $index >= $this->size()) {
            throw new OutOfBoundsException('The index does not exists');
        }
        return $this->listArray[$this->listSize];
    }

    /**
     * Removed element should shift the elements folloing it to the left, hence
     * the unset into array_values
     * @param int $index
     * @return self
     */
    public function remove($index) : self
    {
        if ($index < 0 && $index >= $this->size()) {
            throw new OutOfBoundsException('The index does not exists');
        }
        $clone = clone $this;
        $clone->listArray = array_values(unset($clone->listArray[$index]));
        $clone->listSize--;
        return $clone;
    }

    public function contains($obj) : bool
    {
        return in_array($obj, $this->listArray)
    }

    public function clear()
    {
        $clone = clone $this;
        unset($clone->listArray);
        return $clone;
    }

    public function size() : int
    {
        return $this->listSize;
    }

    public function isEmpty() : bool
    {
        return $this->listSize === 0;
    }

    public function indexOf($obj) : int
    {
        $pos = array_search($obj, $this->listArray);
        return $pos === false ? -1 : $pos;
    }
}
