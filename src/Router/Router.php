<?php
declare(strict_types = 1);

namespace Asd\Router;

use InvalidArgumentException;
use Asd\Http\Request;

/**
 * Router handles the registration of routes from the user and such
 */
class Router
{
  /**
   * array of route-objects
   * @var Route[]
   */
  private $routes = [];

  /**
   * Add route
   * @param Route $route Route object
   */
  public function addRoute(Route $route)
  {
    if($this->routeExists($route))
      throw new InvalidArgumentException('Route already defined.');
    $this->routes[] = $route;
  }

  /**
   * Returns array of routes 
   * @return Route[]
   */
  public function getRoutes() : Array
  {
    return $this->routes;
  }

  /**
   * @codeCoverageIgnore
   * Validate that a Request is in the collection of routes
   * @param  Request $req Request-object
   * @return boolean
   */
  public function requestIsRoute(Request $req) : bool
  {
    foreach($this->routes as $route){
      if($req->getMethod() !== $route->getMethod())
        return false;
      if($req->getUrl() !== $route->getPath())
        return false;
    }
    return true;
  }

  /**
   * Iterates all routes to see if it already exists
   * @param  Route  $route 
   * @return boolean
   */
  private function routeExists(Route $route) : bool
  {
    foreach($this->routes as $r){
      if($r->equals($route))
        return true;
    }
    return false;
  }
}