<?php
declare(strict_types = 1);

namespace Asd\Http;

class HttpStatus
{
    private $statusCode;
    private $phrase;
    public function __construct(int $statusCode, string $phrase)
    {
        $this->validateStatusCode($statusCode);
        $this->statusCode = $statusCode;
        $this->phrase = $phrase;
    }

    public function getStatusCode() : int
    {
        return $this->statusCode;
    }

    public function getPhrase() : string
    {
        return $this->phrase;
    }

    public function withStatusCode(int $statusCode)
    {
        $this->validateStatusCode($statusCode);
        $clone = clone $this;
        $clone->statusCode = $statusCode;
        return $clone;
    }

    public function withPhrase(string $phrase)
    {
        $clone = clone $this;
        $clone->phrase = $phrase;
        return $clone;
    }

    private function validateStatusCode(int $statusCode)
    {
        if (!is_integer($statusCode) || ($statusCode < 100 || $statusCode > 599)) {
            throw new InvalidArgumentException('status statusCode must be an integer value between 100 and 599.');
        }
    }
}