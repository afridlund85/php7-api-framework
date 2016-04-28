<?php

namespace Asd\Collections;

/**
 * This trait is based on the PHP Iterator interface.
 * @link http://php.net/manual/en/class.iterator.php
 *
 * It can be included in Collections to allow them to work with foreach.
 * Depending on the structure of the collection, some methods may have to be
 * overridden and/or called from the collection implementation.
 */
trait IteratorTrait
{
    /**
     * The array that foreach will use when iterating
     * @var array
     */
    private $iteratorArray;

    /**
     * Current position of iteration.
     * @var [type]
     */
    private $position;
    
    /**
     * Reset iterator to start from beginning
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Return value of the current position of iteration
     * @return mixed
     */
    public function current()
    {
        return $this->iteratorArray[$this->position];
    }

    /**
     * Returns current possition of iteration.
     * It is always an int even if collection is a map, can be overridden in
     * implementing class to preserve kay value pairs.
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Set current position to next value in collection
     * @return void
     */
    public function next()
    {
        $this->position += 1;
    }

    /**
     * Validates that current position is set
     * @return bool
     */
    public function valid()
    {
        return isset($this->iteratorArray[$this->position]);
    }
}
