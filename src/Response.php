<?php

namespace Asd;

class Response implements iResponse
{
    private $body;
    
    public function __construct(string $body = '')
    {
        $this->body = $body;
    }
    
    public function toString() : string
    {
        return $this->body;
    }
}