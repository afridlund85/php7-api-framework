<?php
declare(strict_types = 1);

namespace Asd;

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
        $reflection = new ReflectionClass($this->namespace . $this->class);
        $constructor = $reflection->getConstructor();
        $dependencies = $constructor === null ? array() : $this->getDependencies($constructor);
        $class = $reflection->newInstanceArgs($dependencies);

        if (!method_exists($class, $this->method)) {
            throw new InvalidArgumentException('Method "' . $this->method
                . '" does not exist in class "' . $class . '"');
        }

        return call_user_func_array(
            array($class, $this->method),
            array($request, $response)
        );
    }
}
