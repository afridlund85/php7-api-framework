<?php

namespace Asd\Collections;

use Asd\Collections\CollectionInterface;

/**
 * Base class for all collections.
 */
abstract class Collection implements CollectionInterface
{
    /**
     * CollectionInterface extends PHP's Iterator Interface thus it is needed
     * for collections.
     */
    use IteratorTrait;
    /**
     * Size of collection.
     * @var integer
     */
    protected $size = 0;

    /**
     * Return size of collection.
     * @return int
     */
    public function size() : int
    {
        return $this->size;
    }
    
    /**
     * Check if collection is empty.
     * @return boolean
     */
    public function isEmpty() : bool
    {
        return $this->size === 0;
    }
}
