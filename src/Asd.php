<?php
declare (strict_types = 1);

namespace Asd;

use InvalidArgumentException;
use RuntimeException;
use ReflectionFunction;
use ReflectionClass;
use ReflectionFunctionAbstract;
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
            return $this->dispatchClosure($callback);
        }

        return $this->dispatchClass($callback);
    }

    private function dispatchClosure(Closure $callback) : ResponseInterface
    {
        
        $reflection = new ReflectionFunction($callback);
        $dependencies = array_merge(
            array($this->request, $this->response),
            $this->getDependencies($reflection)
        );
        return call_user_func_array($callback, $dependencies);
    }

    private function dispatchClass(string $callback) : ResponseInterface
    {
        $cb = explode('::', $callback);

        $className = $cb[0];
        $methodName = $cb[1];
        
        $reflection = new ReflectionClass($className);
        $constructor = $reflection->getConstructor();
        if($constructor === null){
            $class = new $className();
        } else {
            $dependencies = $this->getDependencies($constructor);
            $class = $reflection->newInstanceArgs($dependencies);
        }

        return call_user_func_array(
            array($class, $methodName),
            array($this->request, $this->response)
        );
    }

    private function getDependencies(ReflectionFunctionAbstract $reflection) : array
    {
        $dependencies = array();
        foreach ($reflection->getParameters() as $param) {
            $class = $param->getClass();
            if ($class !== null) {
                $className = $class->getName();
                array_push($dependencies, new $className());
            }
        }
        return $dependencies;
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
