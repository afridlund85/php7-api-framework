<?php
declare(strict_types = 1);

namespace Asd\Router;

use Throwable;
use RuntimeException;
use InvalidArgumentException;
use ReflectionClass;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MethodCallback extends Callback
{

    private $namespace;
    private $class;
    private $method;

    public function __construct(string $namespace, string $class, string $method)
    {
        $namespace = rtrim($namespace, '\\') . '\\';
        if (!class_exists($namespace . $class)) {
            throw new InvalidArgumentException('class "' . $namespace . $class . '"does not exists');
        }
        $this->namespace = $namespace;
        $this->class = $class;
        $this->method = $method;
    }

    public function invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $params = []
    ) : ResponseInterface {
        $className = $this->namespace . $this->class;
        $reflection = new ReflectionClass($className);
        $constructor = $reflection->getConstructor();

        try {
            $dependencies = $constructor === null ?
                array() : $this->resolveDependencies($constructor);
        } catch (Throwable $e) {
            throw new RuntimeException('Could not resolve dependencies for "'
                . $className . '"');
        }
        $class = $reflection->newInstanceArgs($dependencies);

        if (!method_exists($class, $this->method)) {
            throw new InvalidArgumentException('Method "' . $this->method
                . '" does not exist in class "' . $className . '"');
        }

        return call_user_func_array(
            array($class, $this->method),
            array($request, $response)
        );
    }
}
