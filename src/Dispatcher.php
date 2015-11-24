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
     * @var string
     */
    private $controller;
    
    /**
     * @var string
     */
    private $action;
    
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
        $this->parseRequest();
    }
    
    /**
     * @return string
     */
    public function getController() : string
    {
        return $this->controller;
    }
    
    /**
     * @return string
     */
    public function getAction() : string
    {
        return $this->action;
    }
    
    /**
     * @return void
     */
    public function dispatch()
    {
        $this->response->setBody('A response from ' . $this->action . ' in ' . $this->controller .'.');
        echo $this->response->getBody();
    }
    
    /**
     * @return void
     */
    private function parseRequest()
    {
        $uri = trim($this->request->getUri(), '/');
        $path = explode('/', $uri);
        $this->controller = $path[0] . 'Controller';
        $this->action = $path[1] ?? '';
    }
}