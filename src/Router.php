<?php
namespace Asd;

class Router implements iRouter
{
    /**
     * @var ControllerFactory
     */
    private $factory;
    
    /**
     * @var array
     */
    private $routes = [];
    
    public function __construct(ControllerFactory $factory = null)
    {
        if($factory === null)
            throw new \Exception();
        $this->factory = $factory;
    }
    
    /**
     * @param iRequest $req
     * @return Object will be instance of Asd\Controller later
     */
    public function getController(iRequest $req)
    {
        return $this->factory->createController($req->getUri());
    }
    
    public function getAction()
    {
        return 'myAction';
    }
    
    /**
     * @param string $route
     * @return null
     */
    public function addRoute(string $route = null, string $controller = null)
    {
        if($route === null || $route === '')
            throw new \Exception('Requires string argument.');
        if($controller === null)
            throw new \Exception('Missing controller');
        $newRoute = array($route, $controller);
        if(in_array($newRoute, $this->routes))
            throw new \Exception('Route already set.');
        $this->routes[] = $newRoute;
    }
    
    /**
     * @return array
     */
    public function getRoutes() : array
    {
        return $this->routes;
    }
}