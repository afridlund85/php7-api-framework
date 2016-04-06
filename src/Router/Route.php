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
     * Path
     * @var string
     */
    private $path;

    /**
     * Callback function
     * @var callable
     */
    private $callback;

    /**
     * Path parts
     * @var array
     */
    private $parts;

    /**
     * Parameters from the path
     * @var array
     */
    private $params = [];

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
        $this->path = trim($path, '/');
        $this->callback = $callback;
        $this->parts = $this->parsePath($this->path);
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
     * Returns route params
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
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
     * Splits the path into an array of parts. Looks for path parameters
     * @param string an uri path
     * @return array path split into parts
     */
    public function parsePath(string $path) : array
    {
        $path = explode('/', $path);
        $parts = [];
        foreach ($path as $part) {
            if (preg_match('/^{\S+}$/', $part)) {
                $parts[] = [substr($part, 1, -1)];
            } else {
                $parts[] = $part;
            }
        }
        return $parts;
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

    /**
     * match a request againt the route
     * @param  RequestInterface $req
     * @param  string $basePath
     * @return boolean
     */
    public function matchesRequest(RequestInterface $req, string $basePath = '') : bool
    {
        if ($this->method !== $req->getMethod()) {
            return false;
        }
        //trim slashes
        $reqPath = trim($req->getUri()->getPath(), '/');
        $basePath = trim($basePath, '/');

        //remove base path of request path
        if (stripos($reqPath, $basePath) === 0) {
            $reqPath = substr($reqPath, strlen($basePath));
            $reqPath = trim($reqPath, '/');
        }
        
        //compare parts of route to parts of request path
        $reqPath = explode('/', $reqPath);
        foreach ($this->parts as $i => $part) {
            if (is_array($part)) { //if part is array it is a parameter
                $this->params[$part[0]] = $reqPath[$i]; //save the param
            } else { // else the parts need to match
                if ($part !== $reqPath[$i]) {
                    return false;
                }
            }
        }

        return true;
    }
}
