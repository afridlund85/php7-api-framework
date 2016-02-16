<?php
declare(strict_types = 1);

namespace Asd\Router;

use InvalidArgumentException;
use Http\Request;

/**
 * Router handles the registration of routes from the user and such
 */
class Router{
  /**
   * array of route-objects
   * @var array
   */
  private $routes = [];

  /**
   * Add route
   * @param Route $route Route object
   */
  public function addRoute(Route $route)
  {
    $this->routes[] = $route;
  }

  /**
   * Returns array of routes 
   * @return Array of Route-instances
   */
  public function getRoutes() : Array
  {
    return $this->routes;
  }

  /**
   * Validate that a Request is in the collection of routes
   * @param  Request $req Request-object
   * @return boolean
   */
  public function isValidRoute(Request $req) : bool
  {
    foreach($this->routes as $route){
      if(
        $req->getMethod() === $route->getMethod() &&
        $req->getUrl() === $route->getPath()
      ){
        return true;
      }
    }
    return false;
  }
}