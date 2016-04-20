<?php

namespace Asd\Collections;

use OutOfBoundsException;
use Asd\Collections\CollectionInterface;
use Asd\Collections\MapInterface;
use Asd\Collections\IteratorTrait;

abstract class HashMap extends Collection implements MapInterface
{
    /**
     * Trait with iterator methods
     */
    use IteratorTrait{
        rewind as IteratorRewind;
    }

    /**
     * Iterator rewind method, defines what collection to iterate by assigning
     * $this->array in the Iterator trait to the list
     * @return void
     */
    public function rewind()
    {
        $this->iteratorArray = array_values($this->map);
        $this->IteratorRewind();
    }

    protected $map = [];
    protected $size = 0;

    public function put($key, $obj) : MapInterface
    {
        $clone = clone $this;
        $clone->map[$key] = $obj;
        $clone->size = $this->containsKey($key) ? $clone->size : $clone->size+1;
        return $clone;
    }

    public function get($key)
    {
        if (!$this->containsKey()) {
            throw new OutOfBoundsException('The key does not exists');
        }
        return $this->map[$key];
    }
    
    public function toArray() : array
    {
        return array_values($this->map);
    }
    
    public function remove($key) : MapInterface
    {
        if (!$this->containsKey()) {
            throw new OutOfBoundsException('The key does not exists');
        }
        $clone = clone $this;
        unset($clone->map[$key]);
        $clone->size--;
        return $clone;
    }
    
    public function clear() : MapInterface
    {
        $clone = clone $this;
        unset($clone->map);
        $clone->size = 0;
        return $clone;
    }

    public function containsKey($key) : bool
    {
        return array_key_exists($key, $this->map);
    }

    public function containsValue($obj) : bool
    {
        return in_array($obj, $this->toArray());
    }
}
