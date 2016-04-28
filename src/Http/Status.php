<?php
declare(strict_types = 1);

namespace Asd\Http;

use InvalidArgumentException;

/**
 * Represent a HTTP status with a status code and a reason phrase
 * ex: 200 OK or 403 Forbidden
 */
class Status
{
    /**
     * Http status code
     * @var int
     */
    private $code;

    /**
     * Http reason phrase
     * @var string
     */
    private $phrase;
    
    public function __construct(int $code, string $phrase = '')
    {
        $this->validateCode($code);
        $this->code = $code;
        $this->phrase = $phrase;
    }

    /**
     * return http status code
     * @return int
     */
    public function getCode() : int
    {
        return $this->code;
    }

    /**
     * Return reason phrase
     * @return string
     */
    public function getPhrase() : string
    {
        return $this->phrase;
    }

    /**
     * Change status code.
     * Is immutable, returns clone
     * @param int $code http status code
     * @return self
     */
    public function withCode(int $code) : self
    {
        $this->validateCode($code);
        $clone = clone $this;
        $clone->code = $code;
        return $clone;
    }

    /**
     * Change reason phrase.
     * Is imutable, retuns clone.
     * @param string $phrase Reason phrase
     * @return self
     */
    public function withPhrase(string $phrase) : self
    {
        $clone = clone $this;
        $clone->phrase = $phrase;
        return $clone;
    }

    /**
     * Validate that status code is within accepted range
     * @param int $code int value between 100 and 599
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateCode(int $code)
    {
        if (!is_integer($code) || ($code < 100 || $code > 599)) {
            throw new InvalidArgumentException('status statusCode must be an integer value between 100 and 599.');
        }
    }
}
