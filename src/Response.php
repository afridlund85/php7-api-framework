<?php

namespace Asd;

class Response
{
    public function __construct(string $str = null)
    {
        if($str === null)
            throw new \Exception();
    }
}