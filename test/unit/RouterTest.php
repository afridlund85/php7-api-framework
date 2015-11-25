<?php
namespace Test\Unit;

use Asd\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Asd\Router::__constructor
     * @expectedException \Exception
     */
    public function constructor_withNoArgument_throwsException()
    {
        new Router();
    }
    
    /**
     * @test
     * @covers Asd\Router::__constructor
     */
    public function constructor_withControllerFactory_throwsNoException()
    {
        $factoryStub = $this->getMockBuilder('Asd\ControllerFactory')
            ->getMock();
            
        new Router($factoryStub);
    }
    
    /**
     * @test
     * @covers Asd\Router::getController
     */
    public function getController_usesFactoryToGetControllerInstance()
    {
        $uri = '/MyController/MyAction';
        $expected = new class(){};
        $requestStub = $this->getMockBuilder('Asd\Request')
            ->getMock();
        $requestStub->method('getUri')
            ->willReturn($uri);
        $factoryMock = $this->getMockBuilder('Asd\ControllerFactory')
            ->getMock();
        $factoryMock->method('createController')
            ->willReturn($expected);
        $factoryMock->expects($this->once())
            ->method('createController')
            ->with($uri);
        $router = new Router($factoryMock);
        
        $actual = $router->getController($requestStub);
        
        $this->assertEquals($expected, $actual);
    }
}