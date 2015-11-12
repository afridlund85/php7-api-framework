<?php
declare(strict_types = 1);

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
     * @param string $body  ResponseBody
     * @return void
     */
    public function setBody(string $body)
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