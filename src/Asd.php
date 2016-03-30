<?php
declare(strict_types = 1);

namespace Asd;

use InvalidArgumentException;
use Asd\Exception\RouteNotFound;
use Asd\Router\Router;
use Asd\Router\Route;
use Asd\Http\Request;
use Asd\Http\Response;
use Asd\Controller;

class Asd
{

  private $router;
  private $request;
  private $response;

  public function __construct(Router $router = null, Request $request = null, Response $response = null)
  {
    $this->router = $router ?? new Router();
    $this->request = $request ?? new Request();
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
      $route = $this->router->matchRequest($this->request);
      $response = $this->dispatch($route);
      $this->sendResponse($response);
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

  private function dispatch(Route $route)
  {
    $controller = $route->getController();
    $action = $route->getAction();
    if(!class_exists($controller))
      throw new InvalidArgumentException('Class: "' . $controller . '" not found');
    
    $controller = new $controller();
    if(!$controller instanceof Controller)
      throw new InvalidArgumentException('Class: "' . $controller . '" does not extend from Asd\\Controller');
    $controller->setRequest($this->request);
    $controller->setResponse($this->response);

    if(!method_exists($controller, $action))
      throw new InvalidArgumentException('Method: "' . $action . '" in controller class: "' . $controller . '" not found');

    call_user_func(array($controller, $action));

    return $controller->getResponse();
  }

  private function sendResponse(Response $response)
  {
    // if(!headers_sent()){
    //   $protocol = $this->response->getProtocolVersion();
    //   $statusCode = $this->response->getStatusCode();
    //   $reasonPhrase = $this->response->getReasonPhrase();
    //   header('HTTP/' . $protocol . ' ' . $statusCode . '' . $reasonPhrase;
    
    //   foreach($this->response->getHeaders() as $header => $values){
    //     foreach($values as $value)
    //       header($header . ':' . $value, false);
    //   }
    // }
    
    echo (string)$response->getBody();
  }

}