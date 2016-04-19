<?php
declare(strict_types = 1);

namespace Asd\Http;

use OutOfBoundsException;
use Asd\Http\Header;

class Headers
{
    /**
     * Collection of HttpHeaders
     * @var Header[]
     */
    private $headers = [];

    /**
     * Fetch HttpHeader from collection by its header name, case insensitive
     * @param  string $name header name
     * @return Header
     */
    public function get(string $name) : Header
    {
        if (!$this->contains($name)) {
            throw new OutOfBoundsException('There is no "' . $name . '" header');
        }
        return $this->headers[strtolower($name)];
    }

    /**
     * Returns all headers
     * @return Header[] array of all http headers
     */
    public function all() : array
    {
        return array_values($this->headers);
    }

    /**
     * Adds or overwrites existing header
     * @param HttpHeader $header HttpHeader to add or overwrite
     * @return self
     */
    public function add(Header $header) : self
    {
        $clone = clone $this;
        $clone->headers[strtolower($header->getName())] = $header;
        return $clone;
    }

    /**
     * Remove a header from collection
     * @param  string $name Http Header name
     * @return self
     */
    public function remove(string $name) : self
    {
        $clone = clone $this;
        unset($clone->headers[strtolower($name)]);
        return $clone;
    }

    public function contains(string $name) : bool
    {
        return array_key_exists(strtolower($name), $this->headers);
    }
}
