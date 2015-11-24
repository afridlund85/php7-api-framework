<?php
declare(strict_types = 1);

namespace Asd;

/**
 *  @package Asd
 */
class Dispatcher
{
    /**
     * @var iRequest
     */
    private $request;
    
    /**
     * @var iResponse
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
        $this->request = $req;
    }
    
    /**
     * @return string
     */
    public function getController() : string
    {
        $uri = trim($this->request->getUri(), '/');
        $paths = explode('/', $uri);
        return $paths[0] . 'Controller';
    }
    
    /**
     * @return void
     */
    public function dispatch()
    {
        echo $this->response->getBody();
    }
}