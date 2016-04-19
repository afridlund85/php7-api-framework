<?php

namespace Asd\Collections;

trait IteratorTrait
{
    private $array;
    private $position;
    
    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->array[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position += 1;
    }

    public function valid()
    {
        return isset($this->array[$this->position]);
    }
}
