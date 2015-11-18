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
    private $headers = [];
    
    /**
     * @var string
     */
    private $protocol = 'HTTP/1.1';
    
    /**
     * @var string
     */
    private $contentType = 'application/json';
    
    /**
     * @var string
     */
    private $charset = 'UTF-8';
    
    /**
     * @param string|null $body Response body
     * @param int|null $statusCode HTTP status code
     */
    public function __construct(string $body = null, int $statusCode = null)
    {
        $this->body = $body ?? '';
        $this->statusCode = $statusCode ?? 200;
    }
    
    /**
     * @return string Response body
     */
    public function getBody() : string
    {
        return $this->body;
    }
    
    /**
     * @param string $body Response body
     * @return void
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }
    
    /**
     * @return int HTTP status code
     */
    public function getStatusCode() : int
    {
        return $this->statusCode;
    }
    
    /**
     * @param int HTTP status code
     * @throws \Exception
     * @return void
     */
    public function setStatusCode(int $statusCode)
    {
        if($statusCode < 100 || $statusCode > 999)
            throw new \Exception('$statusCode argument must be greater than 99');
        $this->statusCode = $statusCode;
    }
    
    /**
     * @return Array HTTP headers 
     */
    public function getHeaders() : Array
    {
        return $this->headers;
    }
    
    /**
     * @throws \Exception
     * @return void
     */
    public function addHeader(string $key = null, string $value = null)
    {
        if($key === null)
            throw new \Exception('Missing argument: $key');
        $this->headers[$key] = $value ?? '';
    }
    
    /**
     * @param string $key Header name to remove
     * @return void
     */
    public function removeHeader(string $key)
    {
        if(isset($this->headers[$key]))
            unset($this->headers[$key]);
    }
    
    /**
     * @return string The current protocol(Default: HTTP/1.1 )
     */
    public function getProtocol() : string
    {
        return $this->protocol;
    }
    
    /**
     * @param string $protocol Name of protocol to use
     * @throws \Exception
     * @return void 
     */
    public function setProtocol(string $protocol = null)
    {
        if($protocol === null)
            throw new \Exception('Missing argument: $protocol');
        $this->protocol = $protocol;
    }
    
    /**
     * @return string Content type(json, xml, html etc)
     */
    public function getContentType() : string
    {
        return $this->contentType;
    }
    
    /**
     * @param string|null $contentType What content type to be used.
     * @throws \Exception
     * @return void
     */
    public function setContentType(string $contentType = null)
    {
        if($contentType === null)
            throw new \Exception('Missing argument: $contentType');
        $this->contentType = $contentType;
    }
    
    /**
     * @return string Returns charset(default: UTF-8)
     */
    public function getCharset() : string
    {
        return $this->charset;
    }
    
    /**
     * @param string|null $charset What character set to use in response.
     * @throws \Exception
     * @return void
     */
    public function setCharset(string $charset = null)
    {
        if($charset === null)
            throw new \Exception('Missing argument: $charset');
        $this->charset = $charset;
    }
}