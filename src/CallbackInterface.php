<?php
declare(strict_types = 1);

namespace Asd;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface CallbackInterface
{
    public function invoke(
        RequestInterface $request,
        ResponseInterface $response,
        array $params = []
    ) : ResponseInterface;
}
