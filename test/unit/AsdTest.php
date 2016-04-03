<?php
namespace Test\Unit;

use Asd\Asd;

class AsdTest extends \PHPUnit_Framework_TestCase
{
    protected $app;
    protected $routeStub;
    protected $routerStub;
    protected $requestStub;
    protected $responseStub;
    /**
     * @before
     */
    public function setup()
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
        $this->app = new Asd($this->routerStub, $this->requestStub, $this->responseStub);
    }

    /**
     * @test
     * @covers Asd\Asd::__construct
     * @covers Asd\Asd::addRoute
     */
    public function addRoute_callsRoutersAddRouteMethod()
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

}