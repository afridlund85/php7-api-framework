<?php
namespace Test\Unit;

use RuntimeException;
use InvalidArgumentException;
use Asd\Router\MethodCallback;

class FakeClass
{
    public function fakeMethod($req, $res)
    {
        return $res;
    }
}

class FakeDep
{
    public $value = 1;
}

class FakeClassDep
{
    private $dep;
    public function __construct(FakeDep $dep)
    {
        $this->dep = $dep;
    }
    public function fakeMethod($req, $res)
    {
        $this->dep->value = 2;
        return $res;
    }
}

class FakeClassInvDep
{
    private $dep;
    public function __construct(Dep $dep)
    {
        $this->dep = $dep;
    }
    public function fakeMethod($req, $res)
    {
        $this->dep->value = 2;
        return $res;
    }
}

class MethodCallbackTest extends \PHPUnit_Framework_TestCase
{
    protected $requestStub;
    protected $responseStub;
    /**
     * @before
     */
    public function setUp()
    {
        $this->requestStub = $this->getMockBuilder('\\Asd\\Http\\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $this->responseStub = $this->getMockBuilder('\\Asd\\Http\\Response')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     * @covers Asd\Router\MethodCallback::__construct
     * @expectedException InvalidArgumentException
     */
    public function withInvalidNamespace()
    {
        $mcb = new MethodCallback('Wrong\Namespace', 'FakeClass', 'fakeMethod');
    }

    /**
     * @test
     * @covers Asd\Router\MethodCallback::__construct
     * @expectedException InvalidArgumentException
     */
    public function withInvalidClassName()
    {
        $mcb = new MethodCallback('Test\Unit', 'WrongClass', 'fakeMethod');
    }

    /**
     * @test
     * @covers Asd\Router\MethodCallback::invoke
     * @expectedException InvalidArgumentException
     */
    public function invoke_withInvalidMethod()
    {
        $mcb = new MethodCallback('Test\Unit', 'FakeClass', 'wrongMethod');
        $mcb->invoke($this->requestStub, $this->responseStub);
    }

    /**
     * @test
     * @covers Asd\Router\MethodCallback::invoke
     * @expectedException \RuntimeException
     */
    public function invoke_withInvalidDependency()
    {
        $mcb = new MethodCallback('Test\Unit', 'FakeClassInvDep', 'fakeMethod');
        $mcb->invoke($this->requestStub, $this->responseStub);
    }

    /**
     * @test
     * @covers Asd\Router\MethodCallback::__construct
     * @covers Asd\Router\MethodCallback::invoke
     */
    public function invoke()
    {
        $mcb = new MethodCallback('Test\Unit', 'FakeClass', 'fakeMethod');
        $this->assertEquals($this->responseStub, $mcb->invoke($this->requestStub, $this->responseStub));
    }

    /**
     * @test
     * @covers Asd\Router\MethodCallback::__construct
     * @covers Asd\Router\MethodCallback::invoke
     * @covers Asd\Router\Callback::getDependencies
     */
    public function invoke_withDependency()
    {
        $mcb = new MethodCallback('Test\Unit', 'FakeClassDep', 'fakeMethod');
        $this->assertEquals($this->responseStub, $mcb->invoke($this->requestStub, $this->responseStub));
    }
}