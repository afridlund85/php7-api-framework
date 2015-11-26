<?php
namespace Test\Unit;

use Asd\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers              Asd\Router::__construct
     * @expectedException   \Exception
     */
    public function constructor_withNoArgument_throwsException()
    {
        new Router();
    }
    
    /**
     * @test
     * @covers Asd\Router::__construct
     */
    public function constructor_withControllerFactory_throwsNoException()
    {
        $factoryStub = $this->getMockBuilder('Asd\ControllerFactory')
            ->getMock();
            
        new Router($factoryStub);
    }
    
    /**
     * @test
     * @covers              Asd\Router::addRoute
     * @expectedException   \Exception 
     */
    public function addRoute_withEmptyArgument_throwsException()
    {
        $factoryStub = $this->getMockBuilder('Asd\ControllerFactory')
            ->getMock();
        $router = new Router($factoryStub);
        
        $router->addRoute();
    }
    
    /**
     * @test
     * @covers              Asd\Router::addRoute
     * @expectedException   \Exception 
     */
    public function addRoute_withEmptyString_throwsException()
    {
        $factoryStub = $this->getMockBuilder('Asd\ControllerFactory')
            ->getMock();
        $router = new Router($factoryStub);
        
        $router->addRoute('');
    }
    
    /**
     * @test
     * @covers              Asd\Router::addRoute
     * @expectedException   \Exception 
     */
    public function addRoute_withoutControllerArgument_throwsException()
    {
        $factoryStub = $this->getMockBuilder('Asd\ControllerFactory')
            ->getMock();
        $router = new Router($factoryStub);
        
        $router->addRoute('/some/path');
    }
    
    /**
     * @test
     * @covers Asd\Router::addRoute
     * @covers Asd\Router::getRoutes
     */
    public function addRoute_withStringValues_createNewRoute()
    {
        $expected = array('/some/path', 'SomeController');
        $factoryStub = $this->getMockBuilder('Asd\ControllerFactory')
            ->getMock();
        $router = new Router($factoryStub);
        
        $router->addRoute($expected[0], $expected[1]);
        $routes = $router->getRoutes();
        $actual = $routes[0];
            
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers              Asd\Router::addRoute
     * @expectedException   \Exception
     */
    public function addRoute_withAlreadyExistingRoute_throwsException()
    {
        $toAdd = array('/some/path', 'SomeController');
        $factoryStub = $this->getMockBuilder('Asd\ControllerFactory')
            ->getMock();
        $router = new Router($factoryStub);
        
        $router->addRoute($toAdd[0], $toAdd[1]);
        $router->addRoute($toAdd[0], $toAdd[1]);
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
            ->setMethods(array('createController'))
            ->getMock();
        $factoryMock->method('createController')
            ->willReturn($expected);
        $factoryMock->expects($this->once())
            ->method('createController')
            ->with($this->equalTo($uri));
        $router = new Router($factoryMock);
        
        $actual = $router->getController($requestStub);
        
        $this->assertEquals($expected, $actual);
    }
}