<?php

namespace Asd;

/**
 *  @package Asd
 */
class Dispatcher
{
    
    /**
     *  @param iRequest     $req
     *
     *  @return Dispatcher  Instance of Asd\Dispatcher
     */
    public function __construct(iRequest $req = null, iResponse $res = null)
    {
        if($req === null || $res === null)
            throw new \Exception();
    }
}