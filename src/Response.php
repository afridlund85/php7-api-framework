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
     * @var Array
     */
    private $headers;
    
    /**
     * @param string $body  Response body
     */
    public function __construct(string $body = null, int $statusCode = null)
    {
        $this->body = $body ?? '';
        $this->statusCode = $statusCode ?? 200;
        $this->headers = [];
    }
    
    /**
     * @return string   Response body
     */
    public function getBody() : string
    {
        return $this->body;
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
     * @return int  statusCode
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }
    
    /**
     * @param int   statusCode
     * @return void
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }
    
    /**
     * @return array
     */
    public function getHeaders() : Array
    {
        return $this->headers;
    }
    
    /**
     * @return void
     */
    public function addHeader($key, $value)
    {
        $this->headers[] = [$key => $value];
    }
}