<?php

namespace Asd\Collections;

use Asd\Collections\CollectionInterface;
use Asd\Collections\IteratorTrait;

abstract class HashMap implements CollectionInterface
{
    /**
     * Trait with iterator methods
     */
    use IteratorTrait{
        rewind as traitRewind;
    }

    /**
     * Iterator rewind method, defines what collection to iterate by assigning
     * $this->array in the Iterator trait to the list
     * @return void
     */
    public function rewind()
    {
        $this->array = array_values($this->gerSource());
        $this->traitRewind();
    }

    private function gerSource() : array
    {
        throw new Exception('Not implemented! This method must return the associative array contained in the map');
    }
}
