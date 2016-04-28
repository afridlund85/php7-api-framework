<?php

namespace Asd\Collections;

use OutOfBoundsException;
use Asd\Collections\CollectionInterface;
use Asd\Collections\MapInterface;
use Asd\Collections\IteratorTrait;

class HashMap extends Collection implements MapInterface
{
    /**
     * Trait with iterator methods, must "override" rewrite in order to supply
     * an array that can be iterated. Wont work with key value array. 
     */
    use IteratorTrait{
        rewind as IteratorRewind;
    }

    /**
     * Iterator rewind method, defines what collection to iterate by assigning
     * $this->map in the Iterator trait to the list
     * @return void
     */
    public function rewind()
    {
        $this->iteratorArray = array_values($this->map);
        $this->IteratorRewind();
    }

    protected $map = [];

    public function put($key, $obj) : MapInterface
    {
        $size = $this->containsKey($key) ? $this->size : $this->size + 1;
        $clone = clone $this;
        $clone->map[$key] = $obj;
        $clone->size = $size;
        return $clone;
    }

    public function get($key)
    {
        if (!$this->containsKey($key)) {
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
        if (!$this->containsKey($key)) {
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
        $clone->map = [];
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
