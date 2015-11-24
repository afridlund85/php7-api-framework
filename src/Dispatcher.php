<?php
declare(strict_types = 1);

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
     * @param iRequest|null $req Request object
     * @param iResponse|null $res Response object
     */
    public function __construct(iRequest $req = null, iResponse $res = null)
    {
        if($req === null || $res === null)
            throw new \Exception();
        $this->response = $res;
    }
    
    /**
     * @return string
     */
    public function getController() : string
    {
        
    }
    
    /**
     * @return void
     */
    public function dispatch()
    {
        echo $this->response->getBody();
    }
}