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

  private $basePath = '';

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
  public function matchRequestRoute(Request $req) : bool
  {
    foreach($this->routes as $route){
      if($route->matchesRequest($req, $this->basePath))
        return $route;
    }
    return false;
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

  public function setBasePath(string $basePath)
  {
    $this->basePath = $basePath;
  }

}