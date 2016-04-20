<?php

namespace Asd\Collections;

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
        $this->size++;
    }

    public function get($key)
    {

    }
    
    public function toArray() : array
    {
        return array_values($this->map);
    }
    
    public function remove($key) : MapInterface
    {
        $clone = clone $this;
        $clone->size--;
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

    }

    public function containsValue($obj) : bool
    {

    }
    
}
