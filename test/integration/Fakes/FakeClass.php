<?php
namespace Test\Integration\Fakes;

class FakeClass
{
    private $value = 'Some value';
    public function action($req, $res)
    {
        $res->getBody()->write($this->value);
        return $res;
    }
}