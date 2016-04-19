<?php

namespace Asd\Collections;

trait IteratorTrait
{
    private $iteratorArray;
    private $iteratorPosition;
    
    public function rewind()
    {
        $this->iteratorPosition = 0;
    }

    public function current()
    {
        return $this->iteratorArray[$this->iteratorPosition];
    }

    public function key()
    {
        return $this->iteratorPosition;
    }

    public function next()
    {
        $this->iteratorPosition += 1;
    }

    public function valid()
    {
        return isset($this->iteratorArray[$this->iteratorPosition]);
    }
}