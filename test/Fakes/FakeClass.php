<?php
namespace Test\Fakes;

class FakeClass
{
    private $value = 'Some value';
    public function action($req, $res)
    {
        $res->getBody()->write($this->value);
        return $res;
    }
    public function jsonAction($req, $res)
    {
        return $res->withJson([$this->value]);
    }
}