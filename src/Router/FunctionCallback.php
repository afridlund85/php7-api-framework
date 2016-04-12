<?php
declare(strict_types = 1);

namespace Asd\Router;

use ReflectionFunction;
use Closure;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class FunctionCallback extends Callback
{
    private $callback;

    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    public function invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $params = []
    ) : ResponseInterface {
        $reflection = new ReflectionFunction($this->callback);
        $dependencies = array_merge(
            array($request, $response),
            $this->resolveDependencies($reflection)
        );
        return call_user_func_array($this->callback, $dependencies);
    }
}
