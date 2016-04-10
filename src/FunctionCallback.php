<?php
declare(strict_types = 1);

namespace Asd;

use InvalidArgumentException;
use ReflectionFunction;
use Closure;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class FunctionCallback extends Callback
{
    private $callback;
    private $dependencies;

    public function __construct(Closure $callback, array $dependencies = [])
    {
        $this->callback = $callback;
        $this->dependencies = $dependencies;
    }

    public function invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $params = []
    ) : ResponseInterface {
        $reflection = new ReflectionFunction($this->callback);
        $dependencies = array_merge(
            array($request, $response),
            $this->getDependencies($reflection)
        );
        return call_user_func_array($this->callback, $dependencies);
    }
}
