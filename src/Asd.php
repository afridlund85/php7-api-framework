<?php
declare(strict_types = 1);

namespace Asd;

use InvalidArgumentException;
use Asd\Router\Router;
use Asd\Router\Route;
use Asd\Http\Request;
use Asd\Http\Response;

class Asd
{

  private $router;
  private $req;
  private $res;

  public function __construct(Router $router = null, Request $req = null, Response $res = null)
  {
    $this->router = $router ?? new Router();
    $this->req = $req ?? new Request();
    $this->res = $res ?? new Response();
  }

  /**
   * @codeCoverageIgnore
   */
  private function dump($v)
  {
    echo '<pre>';
    var_dump($v);
    exit;
  }
  /**
   * @codeCoverageIgnore
   */
  public function run()
  {
    try{
      $route = $this->router->matchRequest($this->req);
      $this->dump($route);
    }catch(OutOfBoundsException $e){
      echo '404';
    }catch(Throwable $t){
      echo 'error';
    }
  }

  public function addRoute(Route $route)
  {
    $this->router->addRoute($route);
  }

  public function setBasePath(string $basePath)
  {
    $this->router->setBasePath($basePath);
  }
}