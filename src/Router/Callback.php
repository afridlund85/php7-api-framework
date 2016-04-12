<?php
declare(strict_types = 1);

namespace Asd\Router;

use Throwable;
use RuntimeException;
use InvalidArgumentException;
use Asd\Router\CallbackInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionFunctionAbstract;

abstract class Callback implements CallbackInterface
{
    /**
     * Using the reflection, looks for declared type parameters
     * @param  ReflectionFunctionAbstract $reflection
     * @return string[]
     */
    protected function resolveDependencies(ReflectionFunctionAbstract $reflection) : array
    {
        $dependencies = array();
        foreach ($reflection->getParameters() as $param) {
            $class = $param->getClass();
            if ($class !== null) {
                $className = $class->getName();
                $dependencies[] = new $className();
            }
        }
        return $dependencies;
    }

    /**
     * Abstract, implement in child classes
     * @param  RequestInterface  $request
     * @param  ResponseInterface $response
     * @param  array             $params
     * @return ResponseInterface
     */
    abstract public function invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $params = []
    ) : ResponseInterface;
}
