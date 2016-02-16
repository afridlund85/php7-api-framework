<?php
declare(strict_types = 1)

namespace Asd\Router;

use InvalidArgumentException;

class Route{
  private $method;
  private $path;

  public function __construct(string $method, string $path)
  {
    if(!$this->isValidMethod($method))
      throw new InvalidArgumentException('"' . $method . '" is not a valid method.');
    $this->method = $method;
    $this->path = trim($path, '/');
  }

  public function getMethod() : string
  {
    return $this->method;
  }

  public function getPath() : string
  {
    return $this->path;
  }

  private function isValidMethod(string $method) : bool
  {
    $valid = ['GET', 'POST'];
    return in_array(strtoupper($method), $valid);
  }

}