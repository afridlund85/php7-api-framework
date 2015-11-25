<?php
namespace Asd;

class Router implements iRouter
{
    public function __construct(ControllerFactory $factory = null)
    {
        if($factory === null)
            throw new \Exception();
    }
    
    public function getController(iRequest $req)
    {
        
    }
    
    public function getAction()
    {
        
    }
}