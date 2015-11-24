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
     * @covers              \Asd\Dispatcher::__construct
     */
    public function constructor_withCorrectArguments_doesNotThrowException()
    {
        $requestStub = $this->getMockBuilder('Asd\iRequest')->getMock();
        $responseStub = $this->getMockBuilder('Asd\iResponse')->getMock();
        $dispatcher = new Dispatcher($requestStub, $responseStub);
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
        $dispatcher = new Dispatcher($requestStub, $responseStub);
        
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
        $dispatcher = new Dispatcher($requestStub, $responseStub);
        
        $actual = $dispatcher->getController();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Dispatcher::getController
     */
    public function getAction_returnsNameOfAction_basedOnRequest()
    {
        $expected = 'MyAction';
        $requestStub = $this->getMockBuilder('Asd\iRequest')->getMock();
        $responseStub = $this->getMockBuilder('Asd\iResponse')->getMock();
        $requestStub->method('getUri')->willReturn('/MyResource/MyAction');
        $dispatcher = new Dispatcher($requestStub, $responseStub);
        
        $actual = $dispatcher->getAction();
        
        $this->assertEquals($expected, $actual);
    }
}