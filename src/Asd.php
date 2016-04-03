<?php
declare (strict_types = 1);

namespace Asd;

use InvalidArgumentException;
use RuntimeException;
use Closure;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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

    public function __construct(
        Router $router = null,
        RequestInterface $request = null,
        ResponseInterface $response = null
    )
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
        $route = $this->router->matchRequest($this->request);
        $response = $this->dispatch($route);
        $this->sendResponse($response);
    }

    public function addRoute(Route $route)
    {
        $this->router->addRoute($route);
    }

    public function setBasePath(string $basePath)
    {
        $this->router->setBasePath($basePath);
    }

    private function dispatch(Route $route) : ResponseInterface
    {
        $callback = $route->getCallback();

        if ($callback instanceof Closure) {
            return call_user_func_array($callback, array($this->request, $this->response));
        }
        
        $cb = explode('::', $callback);

        $className = $cb[0];
        $methodName = $cb[1];
        
        $class = new $className();

        return call_user_func_array(
            array($class, $methodName),
            array($this->request, $this->response)
        );
    }

    private function sendResponse(ResponseInterface $response)
    {
        $this->sendHeaders($response);
        
        echo (string)$response->getBody();
    }

    /**
     * @codeCoverageIgnore
     *
     * Cover when system tets are in place
     */
    private function sendHeaders(ResponseInterface $response)
    {
        if (!headers_sent()) {
            $protocol = $response->getProtocolVersion();
            $statusCode = $response->getStatusCode();
            $reasonPhrase = $response->getReasonPhrase();
            header('HTTP/' . $protocol . ' ' . $statusCode . ' ' . $reasonPhrase);
            
            foreach ($response->getHeaders() as $header => $values) {
                foreach ($values as $value) {
                    header($header . ':' . $value, false);
                }
            }
        }
    }
}
