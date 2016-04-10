<?php
namespace Test\Unit;

use Asd\Asd;
use Asd\Exception\RouteNotFound;

class AsdTest extends \PHPUnit_Framework_TestCase
{

    protected $routeStub;
    protected $routerStub;
    protected $requestStub;
    protected $responseStub;

    public function setUp()
    {
        $this->routeStub = $this->getMockBuilder('\\Asd\\Router\\Route')
            ->disableOriginalConstructor()
            ->getMock();
        $this->requestStub = $this->getMockBuilder('\\Asd\\Http\\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $this->responseStub = $this->getMockBuilder('\\Asd\\Http\\Response')
            ->disableOriginalConstructor()
            ->getMock();
        $this->routerStub = $this->getMockBuilder('\\Asd\\Router\\Router')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     * @covers Asd\Asd::__construct
     * @covers Asd\Asd::addRoute
     */
    public function addRoute()
    {
        $routerMock = $this->getMockBuilder('\\Asd\\Router\\Router')
            ->setMethods(array('addRoute'))
            ->getMock();
        $routerMock->expects($this->once())
            ->method('addRoute')
            ->with($this->identicalTo($this->routeStub));

        $app = new Asd($routerMock, $this->requestStub, $this->responseStub);
        $app->addRoute($this->routeStub);
    }

    /**
     * @test
     * @covers Asd\Asd::__construct
     * @covers Asd\Asd::setBasePath
     */
    public function setBasePath()
    {
        $routerMock = $this->getMockBuilder('\\Asd\\Router\\Router')
            ->setMethods(array('setBasePath'))
            ->getMock();
        $routerMock->expects($this->once())
            ->method('setBasePath')
            ->with($this->identicalTo('base/path'));

        $app = new Asd($routerMock, $this->requestStub, $this->responseStub);
        $app->setBasePath('base/path');
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @expectedException Asd\Exception\RouteNotFound
     */
    public function run_withUndefinedRoute()
    {
        $app = new Asd($this->routerStub, $this->requestStub, $this->responseStub);
        $this->routerStub
            ->method('matchRequest')
            ->will($this->throwException(new RouteNotFound));

        $app->run();
    }

}