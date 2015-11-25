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
        $requestStub = $this->getMockBuilder('Asd\iRequest')->getMock();
        $dispatcher = new Dispatcher($requestStub, null);
    }
    
    /**
     * @test
     * @expectedException   \Exception
     * @covers              \Asd\Dispatcher::__construct
     */
    public function constructor_withMissingRequestArgument_throwsException()
    {
        $responseStub = $this->getMockBuilder('Asd\iResponse')->getMock();
        $dispatcher = new Dispatcher(null, $responseStub);
    }
    
    /**
     * @test
     * @expectedException \Exception
     * @covers Asd\Dispather::__construct
     */
    public function constructor_withMissingRouterArgument_throwsException()
    {
        $requestStub = $this->getMockBuilder('Asd\iRequest')->getMock();
        $responseStub = $this->getMockBuilder('Asd\iResponse')->getMock();
        $dispatcher = new Dispatcher($requestStub, $responseStub);
    }
    
    /**
     * @test
     * @covers              \Asd\Dispatcher::__construct
     */
    public function constructor_withCorrectArguments_doesNotThrowException()
    {
        $requestStub = $this->getMockBuilder('Asd\iRequest')->getMock();
        $responseStub = $this->getMockBuilder('Asd\iResponse')->getMock();
        $routerStub = $this->getMockBuilder('Asd\iRouter')->getMock();
        $dispatcher = new Dispatcher($requestStub, $responseStub, $routerStub);
    }
    
    /**
     * @test
     * @covers  Asd\Dispatcher::dispatch
     */
    public function dispatch_generatesOutputBasedOnResponse()
    {
        $expected = 'the expected result';
        $this->expectOutputString($expected);
        
        $requestStub = $this->getMockBuilder('Asd\iRequest')->getMock();
        $responseStub = $this->getMockBuilder('Asd\iResponse')->getMock();
        $responseStub->method('getBody')->willReturn($expected);
        $routerStub = $this->getMockBuilder('Asd\iRouter')->getMock();
        $dispatcher = new Dispatcher($requestStub, $responseStub, $routerStub);
        
        $dispatcher->dispatch();
    }
    
    /**
     * @test
     * @covers Asd\Dispatcher::getController
     */
    public function getController_returnsNameOfController_basedOnRequest()
    {
        $expected = 'MyResourceController';
        $requestStub = $this->getMockBuilder('Asd\iRequest')->getMock();
        $responseStub = $this->getMockBuilder('Asd\iResponse')->getMock();
        $requestStub->method('getUri')->willReturn('/MyResource/');
        $routerStub = $this->getMockBuilder('Asd\iRouter')->getMock();
        $dispatcher = new Dispatcher($requestStub, $responseStub, $routerStub);
        
        $actual = $dispatcher->getController();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Dispatcher::getAction
     */
    public function getAction_returnsNameOfAction_basedOnRequest()
    {
        $expected = 'MyAction';
        $requestStub = $this->getMockBuilder('Asd\iRequest')->getMock();
        $responseStub = $this->getMockBuilder('Asd\iResponse')->getMock();
        $requestStub->method('getUri')->willReturn('/MyResource/MyAction');
        $routerStub = $this->getMockBuilder('Asd\iRouter')->getMock();
        $dispatcher = new Dispatcher($requestStub, $responseStub, $routerStub);
        
        $actual = $dispatcher->getAction();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Dispatcher::dispatch
     */
    public function dispatch_setsBodyOfResponse_basedOnRequest()
    {
        $expected = 'A response from MyAction in MyResourceController.';
        
        $requestStub = $this->getMockBuilder('Asd\Request')
            ->getMock();
        $requestStub->method('getUri')
            ->willReturn('/MyResource/MyAction/');
        $responseMock = $this->getMockBuilder('Asd\Response')
            ->setMethods(array('setBody'))
            ->getMock();
        $responseMock->expects($this->once())
            ->method('setBody')
            ->with($this->equalTo('A response from MyAction in MyResourceController.'));
        $routerStub = $this->getMockBuilder('Asd\iRouter')->getMock();
        
        $dispatcher = new Dispatcher($requestStub, $responseMock, $routerStub);
        
        $dispatcher->dispatch();
    }
    
    /**
     * @test
     * @covers Asd\Dispatcher::dispatch
     */
    public function dispatch_usesRouterToGetAController_echoItsResponse()
    {
        $route = '/MyResource/MyAction/';
        $expected = 'Some text response';
        $this->expectOutputString($expected);
        $requestStub = $this->getMockBuilder('Asd\Request')
            ->getMock();
        $requestStub->method('getUri')
            ->willReturn($route);
        $controllerStub = $this->getMockBuilder('stdClass')
            ->getMock();
        $controllerStub->method('myAction')
            ->willReturn($expected);
        $routerMock = $this->getMockBuilder('Asd\iRouter')
            ->getMock();
        $routerMock->method('getController')
            ->willReturn($controllerStub);
        $routerMock->expects($this->once())
            ->method('getController')
            ->with($this->equalTo($route));
        $responseStub = $this->getMockBuilder('Asd\iResponse')
            ->getMock();
            
        $dispatcher = new Dispatcher($requestStub, $responseStub, $routerMock);
        
        $dispatcher->dispatch();
    }
}