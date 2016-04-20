<?php

namespace Asd\Collections;

use Asd\Collections\CollectionInterface;

interface MapInterface extends CollectionInterface
{
    public function get($key);
    public function put($key, $obj) : self;
    public function remove($key) : self;
    public function clear() : self;
    public function containsKey($key) : bool;
    public function containsValue($obj) : bool;
}
