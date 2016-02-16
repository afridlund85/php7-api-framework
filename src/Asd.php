<?php
declare(strict_types = 1);

namespace Asd;

use InvalidArgumentException;
use Asd\Router;
use Asd\Http\Request;

class Asd{

  private $req;
  private $router;

  public function __construct(Request $req, Router $router)
  {
    $this->req = $req ?? new Request();
    $this->router = $router ?? new Router();
  }

  private function dump($v)
  {
    echo '<pre>';
    var_dump($v);
    exit;
  }

  public function run()
  {
    $this->dump($this->req);
    if(!$this->router->isValidRoute($this->req)){
      echo '404 not found.';
    }
    else{
      $this->dump($this->req);
    }
  }

  public function addRoute(Route $route)
  {
    $this->router->addRoute($route);
  }

}