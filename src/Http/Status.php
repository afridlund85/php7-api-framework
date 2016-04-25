<?php
declare(strict_types = 1);

namespace Asd\Http;

use InvalidArgumentException;

class Status
{
    private $code;
    private $phrase;
    
    public function __construct(int $code, string $phrase = '')
    {
        $this->validateCode($code);
        $this->code = $code;
        $this->phrase = $phrase;
    }

    public function getCode() : int
    {
        return $this->code;
    }

    public function getPhrase() : string
    {
        return $this->phrase;
    }

    public function withCode(int $code) : self
    {
        $this->validateCode($code);
        $clone = clone $this;
        $clone->code = $code;
        return $clone;
    }

    public function withPhrase(string $phrase) : self
    {
        $clone = clone $this;
        $clone->phrase = $phrase;
        return $clone;
    }

    private function validateCode(int $code)
    {
        if (!is_integer($code) || ($code < 100 || $code > 599)) {
            throw new InvalidArgumentException('status statusCode must be an integer value between 100 and 599.');
        }
    }
}
