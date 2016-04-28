<?php
declare(strict_types = 1);

namespace Asd\Http;

use InvalidArgumentException;

class Header
{
    /**
     * Http Header name
     * @var string
     */
    private $name;

    /**
     * Http Header values as an array of strings
     * @var string[]
     */
    private $values;

    /**
     * @param string $name header name
     * @param string[] $values array of strings with header values 
     */
    public function __construct(string $name, array $values = [])
    {
        $this->validateValues($values);
        $this->name = $name;
        $this->values = $values;
    }

    /**
     * Return name of header
     * @return string http header name
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * return array of header values
     * @return string[]
     */
    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * return header values as comma separated string
     * @return string 
     */
    public function getHeaderLine() : string
    {
        return implode(', ', $this->values);
    }

    /**
     * Changes the header name, returns clone
     * @param  string $name new name of header
     * @return self
     */
    public function withName(string $name) : self
    {
        $clone = clone $this;
        $clone->name = $name;
        return $clone;
    }

    /**
     * Adds and overwrites values of the header, returns clone
     * @param string[] $values array of strings
     * @return self
     */
    public function withValues(array $values) : self
    {
        $this->validateValues($values);
        $clone = clone $this;
        $clone->values = $values;
        return $clone;
    }

    /**
     * Adds value to header and keeps old values, returns clone
     * @param  string[] $values array of strings
     * @return self
     */
    public function withAddedValues(array $values) : self
    {
        $this->validateValues($values);
        $clone = clone $this;
        $clone->values = array_merge($this->values, $values);
        return $clone;
    }

    /**
     * returns string representation of header.
     * [headername]:[comma-separated values]
     * @return string
     */
    public function __toString() : string
    {
        return $this->name . ': ' . $this->getHeaderLine();
    }

    /**
     * Validates that array only contains strings
     * @param  array $values
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateValues(array $values)
    {
        foreach ($values as $value) {
            if (!is_string($value)) {
                throw new InvalidArgumentException();
            }
        }
    }
}
