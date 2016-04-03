<?php
declare (strict_types = 1);

namespace Asd\Router;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;

/**
 * Represents a single route
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
     * callback function
     * @var callable
     */
    private $callback;

    /**
     * Route object that represents a registered route in the application
     * @param string $method HTTP-method
     * @param string $path   path/uri
     */
    public function __construct(string $method, string $path, callable $callback)
    {
        if (!$this->isValidMethod($method)) {
            throw new InvalidArgumentException('"' . $method . '" is not a valid method.');
        }
        $this->method = strtoupper($method);
        $this->path = '/' . trim($path, '/');
        $this->callback = $callback;
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
     * Returns callback
     * @return callable
     */
    public function getCallback() : callable
    {
        return $this->callback;
    }

    /**
     * Validates that HTTP-method is valid and supported
     * @param  string  $method HTTP-method
     * @return boolean
     */
    private function isValidMethod(string $method) : bool
    {
        $valid = ['GET', 'POST', 'PUT', 'DELETE'];
        return in_array(strtoupper($method), $valid);
    }

    /**
     * Check if the route compared has the same properties
     * @param  Route  $route route to be compared
     * @return boolean
     */
    public function equals(Route $route) : bool
    {
        if ($this->method !== $route->getMethod()) {
            return false;
        }
        if ($this->path !== $route->getPath()) {
            return false;
        }
        return true;
    }

    public function matchesRequest(RequestInterface $req, string $basePath = '') : bool
    {
        if ($this->method !== $req->getMethod()) {
            return false;
        }
        $reqPath = trim($req->getUri()->getPath(), '/');
        $basePath = trim($basePath, '/');

        if (stripos($reqPath, $basePath) === 0) {
            $reqPath = substr($reqPath, strlen($basePath));
            $reqPath = trim($reqPath, '/');
        }
        
        if (trim($this->path, '/') === $reqPath) {
            return true;
        }

        return false;
    }

}
