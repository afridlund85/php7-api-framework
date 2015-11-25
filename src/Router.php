<?php
namespace Asd;

class Router implements iRouter
{
    private $factory;
    private $routes = [];
    
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
    
    public function addRoute(string $route = null)
    {
        if($route === null)
            throw new \Exception('Requires string argument.');
        $this->routes[] = $route;
    }
    
    public function getRoutes()
    {
        return $this->routes;
    }
}