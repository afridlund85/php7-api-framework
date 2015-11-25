<?php

namespace Test\Unit;

use Asd\Dispatcher;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    
    /**
    * @test
    * @expectedException    \Exception
    * @covers               \Asd\Dispatcher::__construct
    */
    public function constructor_withNoArguments_throwsException()
    {
        $dispatcher = new Dispatcher();
    }
    
    /**
     * @test
     * @expectedException   \Exception
     * @covers              \Asd\Dispatcher::__construct
     */
    public function constructor_withMissingReponseArgument_throwsException()
    {
        $requestStub = $this->getMockBuilder('Asd\Request')->getMock();
        $dispatcher = new Dispatcher($requestStub, null);
    }
    
    /**
     * @test
     * @expectedException   \Exception
     * @covers              \Asd\Dispatcher::__construct
     */
    public function constructor_withMissingRequestArgument_throwsException()
    {
        $responseStub = $this->getMockBuilder('Asd\Response')->getMock();
        $dispatcher = new Dispatcher(null, $responseStub);
    }
    
    /**
     * @test
     * @expectedException \Exception
     * @covers Asd\Dispather::__construct
     */
    public function constructor_withMissingRouterArgument_throwsException()
    {
        $requestStub = $this->getMockBuilder('Asd\Request')->getMock();
        $responseStub = $this->getMockBuilder('Asd\Response')->getMock();
        $dispatcher = new Dispatcher($requestStub, $responseStub);
    }
    
    /**
     * @test
     * @covers              \Asd\Dispatcher::__construct
     */
    public function constructor_withCorrectArguments_doesNotThrowException()
    {
        $requestStub = $this->getMockBuilder('Asd\Request')->getMock();
        $responseStub = $this->getMockBuilder('Asd\Response')->getMock();
        $routerStub = $this->getMockBuilder('Asd\Router')->getMock();
        $dispatcher = new Dispatcher($requestStub, $responseStub, $routerStub);
    }
    
    /**
     * @test
     * @covers Asd\Dispatcher::dispatch
     */
    public function dispatch_usesRouterToGetAController_echoItsResponse()
    {
        $route = '/MyResource/MyAction/';
        $expected = 'Some text response';
        $controller = new class($expected){
            private $s;
            public function __construct($s){
                $this->s = $s;
            }
            public function myAction(){
                return $this->s;
            }
        };
        $requestStub = $this->getMockBuilder('Asd\Request')
            ->getMock();
        $requestStub->method('getUri')
            ->willReturn($route);
        $routerMock = $this->getMockBuilder('Asd\Router')
            ->getMock();
        $routerMock->method('getController')
            ->willReturn($controller);
        $routerMock->expects($this->once())
            ->method('getController')
            ->with($this->equalTo($route));
        $responseStub = $this->getMockBuilder('Asd\Response')
            ->getMock();
            
        $dispatcher = new Dispatcher($requestStub, $responseStub, $routerMock);
        
        $dispatcher->dispatch();
    }
}