<?php

namespace Asd\Collections;

use Asd\Collections\CollectionInterface;
use Asd\Collections\IteratorTrait;

abstract class Map implements CollectionInterface
{

    use IteratorTrait {rewind as TraitRewind};

    public function rewind()
    {
        $this->array = array_values($this->gerSource());
        $this->TraitRewind();
    }

    private function gerSource() : array
    {
        throw new Exception('Not implemented! This method must return the associative array contained in the map');
    }

}