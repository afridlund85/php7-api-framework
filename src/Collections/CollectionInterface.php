<?php

namespace Asd\Collections;

use Iterator;
use Chainable;

interface CollectionInterface extends Iterator
{
    public function add($obj) : self;
    public function get($index);
    public function remove($index) : self;
    public function contains($obj) : bool;
    public function clear();
    public function size() : int;
    public function isEmpty() : bool;
}
