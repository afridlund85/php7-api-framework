<?php
namespace Test\Fakes;

class FakeClassDep
{
    private $dep;
    private $value = 'Some value';
    public function __construct(FakeDependency $dep)
    {
        $this->dep = $dep;
    }
    public function action($req, $res)
    {
        $this->dep->setValue($this->value);
        $res->getBody()->write($this->dep->getValue());
        return $res;
    }
}