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
     * @var int
     */
    private $statusCode;
    
    /**
     * @param string $body  Response body
     */
    public function __construct(string $body = '', int $statusCode = null)
    {
        $this->body = $body;
        $this->statusCode = $statusCode ?? 200;
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
    
    /**
     * @return int  statusCode
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }
}