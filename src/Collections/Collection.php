<?php

namespace Asd\Collections;

use Asd\Collections\CollectionInterface;

abstract class Collection implements CollectionInterface
{
    protected $size = 0;
    public function size() : int
    {
        return $this->size;
    }
    
    public function isEmpty() : bool
    {
        return $this->size === 0;
    }
}
