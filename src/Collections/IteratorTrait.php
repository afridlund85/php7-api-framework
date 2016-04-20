<?php

namespace Asd\Collections;

trait IteratorTrait
{
    private $iteratorArray;
    private $position;
    
    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->iteratorArray[$this->position];
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
        return isset($this->iteratorArray[$this->position]);
    }
}
