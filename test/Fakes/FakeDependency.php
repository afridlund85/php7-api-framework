<?php
namespace Test\Fakes;

class FakeDependency
{
    private $value;

    public function getValue()
    {
        return $this->value;
    }

    public function setValue(string $value)
    {
        return $this->value = $value;
    }
}