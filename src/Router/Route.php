<?php
declare(strict_types = 1);

namespace Asd\Router;

use InvalidArgumentException;
use Asd\Http\Request;

/**
 * Represents a single route and its settings
 */
class Route
{
  /**
   * HTTP-method
   * @var string
   */
  private $method;
  
  /**
   * path
   * @var string
   */
  private $path;

  /**
   * Route object that represents a registered route in the application
   * @param string $method HTTP-method
   * @param string $path   path/uri
   */
  public function __construct(string $method, string $path)
  {
    if(!$this->isValidMethod($method))
      throw new InvalidArgumentException('"' . $method . '" is not a valid method.');
    $this->method = strtoupper($method);
    $this->path = '/' . trim((trim(trim($path), '/')));
  }
  /**
   * Return HTTP-method as string
   * @return string HTTP-method as uppercase string
   */
  public function getMethod() : string
  {
    return $this->method;
  }

  /**
   * Returns path as string
   * @return string path
   */
  public function getPath() : string
  {
    return $this->path;
  }

  /**
   * Validates that HTTP-method is valid and supported
   * @param  string  $method HTTP-method
   * @return boolean
   */
  private function isValidMethod(string $method) : bool
  {
    $valid = ['GET', 'POST'];
    return in_array(strtoupper($method), $valid);
  }

  /**
   * Check if the route compared has the same properties
   * @param  Route  $route route to be compared
   * @return boolean
   */
  public function equals(Route $route) : bool
  {
    if($this->method !== $route->getMethod())
      return false;
    if($this->path !== $route->getPath())
      return false;
    return true;
  }

  public function matchesRequest(Request $req, string $basePath) : bool
  {
    if($this->method !== $req->getMethod())
      return false;
    $reqPath = trim($req->getUri()->getPath(), '/');
    $basePath = trim($basePath, '/');

    if(stripos($reqPath, $basePath) === 0){
      $reqPath = substr($reqPath, strlen($basePath));
      $reqPath = trim($reqPath, '/');
    }
    
    return trim($this->path, '/') === $reqPath;
  }

}