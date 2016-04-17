<?php
declare(strict_types = 1);

namespace Asd\Http;

use InvalidArgumentException;

class HttpHeader
{
    private $headerName;
    private $values;

    public function __construct(string $headerName, array $values = [])
    {
        $this->headerName = $headerName;
        $this->values = $this->filterValues($values);
    }

    public function getHeaderName() : string
    {
        return $this->headerName;
    }

    public function getValues() : array
    {
        return $this->values;
    }

    public function getHeaderLine() : string
    {
        return implode(', ', $this->values);
    }

    public function withHeaderName(string $headerName) : self
    {
        $clone = clone $this;
        $clone->headerName = $headerName;
        return $clone;
    }

    public function withValues(array $values) : self
    {
        $clone = clone $this;
        $clone->values = $this->filterValues($values);
        return $clone;
    }

    public function withAddedValues(array $values) : self
    {
        $clone = clone $this;
        $clone->values = array_merge($this->values, $this->filterValues($values));
        return $clone;
    }

    public function __toString() : string
    {
        return $this->headerName . ': ' . $this->getHeaderLine();
    }

    private function filterValues(array $values) : array
    {
        foreach ($values as $value) {
            if (!is_string($value)) {
                throw new InvalidArgumentException();
            }
        }
        return $values;
    }
}
