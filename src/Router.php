<?php
namespace Asd;

class Router implements iRouter
{
    private $factory;
    
    public function __construct(ControllerFactory $factory = null)
    {
        if($factory === null)
            throw new \Exception();
        $this->factory = $factory;
    }
    
    public function getController(iRequest $req)
    {
        return $this->factory->createController($req->getUri());
    }
    
    public function getAction()
    {
        
    }
}