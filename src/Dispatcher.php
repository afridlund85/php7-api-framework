<?php

namespace Asd;

/**
 *  @package Asd
 */
class Dispatcher
{
    /**
     * @var Response
     */
    private $response;
    
    /**
     *  @param iRequest     $req
     *
     *  @return Dispatcher  Instance of Asd\Dispatcher
     */
    public function __construct(iRequest $req = null, iResponse $res = null)
    {
        if($req === null || $res === null)
            throw new \Exception();
        $this->response = $res;
    }
    
    /**
     * @return void
     */
    public function dispatch()
    {
        echo $this->response->getBody();
    }
}