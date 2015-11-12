<?php

namespace Asd;

/**
 * @package Asd
 */
class Response implements iResponse
{
    /**
     * @var string
     */
    private $body;
    
    /**
     * @param string $body  Response body
     */
    public function __construct(string $body = '')
    {
        $this->body = $body;
    }
    
    /**
     * @return string   Response body
     */
    public function toString() : string
    {
        return $this->body;
    }
}