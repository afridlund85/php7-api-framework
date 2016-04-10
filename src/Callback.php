<?php
declare(strict_types = 1);

namespace Asd;

use InvalidArgumentException;
use Asd\CallbackInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionFunctionAbstract;

abstract class Callback implements CallbackInterface
{
    protected function resolveDependencies(array $dependencies) : array
    {
        $resolved = [];
        foreach ($dependencies as $dependency) {
            if (!class_exists($dependency)) {
                throw new InvalidArgumentException('Dependency "' . $dependency . '" does not exist.');
            }
            $resolved[] = new $dependency();
        }
        return $resolved;
    }

    /**
     * Using the reflection, looks for declared type parameters
     * @param  ReflectionFunctionAbstract $reflection
     * @return string[]
     */
    protected function getDependencies(ReflectionFunctionAbstract $reflection) : array
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

    abstract public function invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $params = []
    ) : ResponseInterface;
}
