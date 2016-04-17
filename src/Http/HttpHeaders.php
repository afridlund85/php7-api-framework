<?php
declare(strict_types = 1);

namespace Asd\Http;

use OutOfBoundsException;
use Asd\Http\HttpHeader;

class HttpHeaders
{
    /**
     * Collection of HttpHeaders
     * @var HttpHeader[]
     */
    private $httpHeaders;

    /**
     * @param array $httpHeaders HttpHeader-array
     */
    public function __construct(array $httpHeaders = [])
    {
        $this->httpHeaders = [];
        foreach ($httpHeaders as $key => $value) {
            $this->httpHeaders[strtolower($key)] = $value;
        }
    }

    /**
     * Fetch HttpHeader from collection by its header name, case insensitive
     * @param  string $headerName header name
     * @return HttpHeader
     */
    public function getHeader(string $headerName) : HttpHeader
    {
        if (!$this->hasHeader($headerName)) {
            throw new OutOfBoundsException('There is no "' . $headerName . '" header');
        }
        return $this->httpHeaders[strtolower($headerName)];
    }

    /**
     * Returns all headers
     * @return HttpHader[] array of all http headers
     */
    public function getAllHeaders() : array
    {
        return array_values($this->httpHeaders);
    }

    /**
     * Adds or overwrites existing header
     * @param HttpHeader $httpHeader HttpHeader to add or overwrite
     * @return self
     */
    public function addHeader(HttpHeader $httpHeader) : self
    {
        $clone = clone $this;
        $clone->httpHeaders[strtolower($httpHeader->getHeaderName())] = $httpHeader;
        return $clone;
    }

    /**
     * Remove a header from collection
     * @param  string $headerName Http Header name
     * @return self
     */
    public function removeHeader(string $headerName) : self
    {
        $clone = clone $this;
        unset($clone->httpHeaders[strtolower($headerName)]);
        return $clone;
    }

    public function hasHeader(string $headerName) : bool
    {
        return array_key_exists(strtolower($headerName), $this->httpHeaders);
    }
}
