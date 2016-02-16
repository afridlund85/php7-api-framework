<?php
declare(strict_types = 1);

namespace Asd\Router;

use Http\Request;

class Router{
  /**
   * array of route-objects
   * @var array
   */
  private $routes = [];

  /**
   * Add route
   * @param Route $route [description]
   */
  public function addRoute(Route $route)
  {
    $this->routes[] = $route;
  }

  /**
   * Validate that a Request is in the collection of routes
   * @param  Request $req [description]
   * @return boolean      [description]
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