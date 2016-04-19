<?php
declare(strict_types = 1);

namespace Asd\Http;

use InvalidArgumentException;

class Header
{
    private $name;
    private $values;

    public function __construct(string $name, array $values = [])
    {
        $this->validateValues($values);
        $this->name = $name;
        $this->values = $values;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getValues() : array
    {
        return $this->values;
    }

    public function getHeaderLine() : string
    {
        return implode(', ', $this->values);
    }

    public function withName(string $name) : self
    {
        $clone = clone $this;
        $clone->name = $name;
        return $clone;
    }

    public function withValues(array $values) : self
    {
        $this->validateValues($values);
        $clone = clone $this;
        $clone->values = $values;
        return $clone;
    }

    public function withAddedValues(array $values) : self
    {
        $this->validateValues($values);
        $clone = clone $this;
        $clone->values = array_merge($this->values, $values);
        return $clone;
    }

    public function __toString() : string
    {
        return $this->name . ': ' . $this->getHeaderLine();
    }

    private function validateValues(array $values)
    {
        foreach ($values as $value) {
            if (!is_string($value)) {
                throw new InvalidArgumentException();
            }
        }
    }
}
