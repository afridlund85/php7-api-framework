<?php

namespace Asd;

class Response
{
    public function __construct(string $body = '')
    {
        if($body === '')
            throw new \Exception();
    }
}