<?php
declare(strict_types = 1);

namespace Asd;

use InvalidArgumentException;
use RuntimeException;
use Asd\Exception\RouteNotFound;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Asd\Http\Request;
use Asd\Http\Response;
use Asd\Router\Router;
use Asd\Router\Route;

// use ReflectionFunction;
// use ReflectionClass;
// use ReflectionFunctionAbstract;
// use Closure;
// use Asd\Controller;

/**
 * Main class, where the magic happends
 */
class Asd
{
    /**
     * Router instance
     * @var Asd\Router\Router
     */
    private $router;

    /**
     * Initial request object
     * @var Psr\Http\Message\RequestInterface
     */
    private $request;

    /**
     * Initial response object
     * @var Psr\Http\Message\ResponseInterface
     */
    private $response;

    /**
     * @param Router|null $router
     * @param Psr\Http\Message\RequestInterface|null $request
     * @param Psr\Http\Message\ResponseInterface|null $response
     */
    public function __construct(
        Router $router = null,
        RequestInterface $request = null,
        ResponseInterface $response = null
    ) {
        $this->router = $router ?? new Router();
        $this->request = $request ?? new Request();
        $this->response = $response ?? new Response();
    }

    /**
     * Start the application and process the request
     * @return void
     */
    public function run()
    {
        $route = $this->router->matchRequest($this->request);
        $response = $this->dispatch($route);
        $this->sendResponse($response);
    }

    /**
     * Add a route to the router
     * @param Asd\Router\Route $route
     * @return void
     */
    public function addRoute(Route $route)
    {
        $this->router->addRoute($route);
    }

    /**
     * Set a base path for the application, useful if it does not run from root
     * @param string $basePath
     * @return void
     */
    public function setBasePath(string $basePath)
    {
        $this->router->setBasePath($basePath);
    }

    /**
     * Dispatch the matched routes callback
     * @param  Asd\Router\Route $route
     * @return Psr\Http\Message\ResponseInterface
     */
    private function dispatch(Route $route) : ResponseInterface
    {
        $callback = $route->getCallback();
        return $callback->invoke($this->request, $this->response);

        // if ($callback instanceof Closure) {
        //     return $this->dispatchClosure($callback);
        // }

        // return $this->dispatchClass($callback);
    }

    /**
     * Dispatch anonymus / Closure function
     * @param  Closure $callback
     * @return Psr\Http\Message\ResponseInterface
     */
    // private function dispatchClosure(Closure $callback) : ResponseInterface
    // {
        
    //     $reflection = new ReflectionFunction($callback);
    //     $dependencies = array_merge(
    //         array($this->request, $this->response),
    //         $this->getDependencies($reflection)
    //     );
    //     return call_user_func_array($callback, $dependencies);
    // }

    /**
     * Dispatch class method
     * @param  string $callback string in "namespace\class::method"-format
     * @return Psr\Http\Message\ResponseInterface
     */
    // private function dispatchClass(string $callback) : ResponseInterface
    // {
    //     $cb = explode('::', $callback);
    //     $className = $cb[0];
    //     $methodName = $cb[1];
        
    //     $reflection = new ReflectionClass($className);
    //     $constructor = $reflection->getConstructor();
    //     if ($constructor === null) {
    //         $class = new $className();
    //     } else {
    //         $dependencies = $this->getDependencies($constructor);
    //         $class = $reflection->newInstanceArgs($dependencies);
    //     }

    //     return call_user_func_array(
    //         array($class, $methodName),
    //         array($this->request, $this->response)
    //     );
    // }

    /**
     * Using the reflection, looks for declared type parameters
     * @param  ReflectionFunctionAbstract $reflection
     * @return string[]
     */
    // private function getDependencies(ReflectionFunctionAbstract $reflection) : array
    // {
    //     $dependencies = array();
    //     foreach ($reflection->getParameters() as $param) {
    //         $class = $param->getClass();
    //         if ($class !== null) {
    //             $className = $class->getName();
    //             array_push($dependencies, new $className());
    //         }
    //     }
    //     return $dependencies;
    // }

    /**
     * Start output of the response object
     * @param  Psr\Http\Message\ResponseInterface $response
     * @return void
     */
    private function sendResponse(ResponseInterface $response)
    {
        $this->sendHeaders($response);
        
        echo (string)$response->getBody();
    }

    /**
     * Send response headers from response object
     * @param  Psr\Http\Message\ResponseInterface $response
     * @return void
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
