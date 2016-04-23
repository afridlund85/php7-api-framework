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
use Asd\Http\Uri;
use Asd\Router\Router;
use Asd\Router\Route;

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
        $uri = new Uri();
        $this->request = $request ?? new Request(null, $uri->withGlobals());
        $this->response = $response ?? new AsdResponse();
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
     * Dispatch the matched routes callback
     * @param  Asd\Router\Route $route
     * @return Psr\Http\Message\ResponseInterface
     */
    private function dispatch(Route $route) : ResponseInterface
    {
        $callback = $route->getCallback();
        return $callback->invoke($this->request, $this->response);
    }

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
