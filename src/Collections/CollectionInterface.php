<?php

namespace Asd\Collections;

use Iterator;
use Chainable;

interface CollectionInterface extends Iterator
{
    public function size() : int;
    public function isEmpty() : bool;
    public function toArray() : array;
}
