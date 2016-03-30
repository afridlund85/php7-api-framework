<?php
declare(strict_types = 1);

namespace Asd;

use InvalidArgumentException;
use Asd\Exception\RouteNotFound;
use Asd\Router\Router;
use Asd\Router\Route;
use Asd\Http\Request;
use Asd\Http\Response;

class Asd
{

  private $router;
  private $req;
  private $response;

  public function __construct(Router $router = null, Request $req = null, Response $response = null)
  {
    $this->router = $router ?? new Router();
    $this->req = $req ?? new Request();
    $this->response = $response ?? new Response();
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

  public function run()
  {
    try{
      $route = $this->router->matchRequest($this->req);
      $result = $this->router->dispatch($route);
      echo $result;
      exit;
      //$responseBody = $this->response->getBody();
      //$this->response->withBody($responseBody->write($result));
    }catch(RouteNotFound $e){
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