<?php
namespace Test\Unit;

use Throwable;
use Asd\Router\Router;

class MyController
{
  public function myAction()
  {
    return 'Hello World!';
  }
}

class RouterTest extends \PHPUnit_Framework_TestCase
{

  protected $router;

  public function setUp()
  {
      $this->router = new Router();
  }

  /**
   * @test
   * @covers Asd\Router\Router::addRoute
   * @expectedException Throwable 
   */
  public function addRoute_withoutArgument()
  {
    $this->router->addRoute();
  }

  /**
   * @test
   * @covers Asd\Router\Router::getRoutes
   */
  public function getRoutes_whenEmpty()
  {
    $routes = $this->router->getRoutes();
    $this->assertEquals(sizeof($routes), 0);
  }
  
  /**
   * @test
   * @covers Asd\Router\Router::addRoute
   * @covers Asd\Router\Router::routeExists
   */
  public function addRoute_routeObject()
  {
    $routeStub = $this->getMockBuilder('\\Asd\\Router\\Route')
      ->disableOriginalConstructor()
      ->getMock();

    $this->router->addRoute($routeStub);
    $routes = $this->router->getRoutes();
        
    $this->assertEquals($routeStub, $routes[0]);
    $this->assertEquals(sizeof($routes), 1);
  }

  /**
   * @test
   * @covers Asd\Router\Router::addRoute
   * @covers Asd\Router\Router::routeExists
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage Route already defined.
   */
  public function addRoute_existingRoute()
  {
    $routeStub1 = $this->getMockBuilder('\\Asd\\Router\\Route')
      ->disableOriginalConstructor()
      ->getMock();
    $routeStub2 = $this->getMockBuilder('\\Asd\\Router\\Route')
      ->disableOriginalConstructor()
      ->getMock();

    $routeStub1->method('equals')
      ->will($this->returnValue(true));
    
    $this->router->addRoute($routeStub1);
    $this->router->addRoute($routeStub2);
  }

  /**
   * @test
   * @covers Asd\Router\Router::matchRequest
   * @expectedException Asd\Exception\RouteNotFound
   */
  public function matchRequest_withoutMatch()
  {
    $routeStub = $this->getMockBuilder('\\Asd\\Router\\Route')
      ->disableOriginalConstructor()
      ->getMock();
    $routeStub->method('matchesRequest')
      ->will($this->returnValue(false));
    $requestStub = $this->getMockBuilder('\\Asd\\Http\\Request')
      ->disableOriginalConstructor()
      ->getMock();

    $this->router->addRoute($routeStub);
    $this->router->matchRequest($requestStub);
  }

  /**
   * @test
   * @covers Asd\Router\Router::matchRequest
   */
  public function matchRequest_withMatch()
  {
    $routeStub1 = $this->getMockBuilder('\\Asd\\Router\\Route')
      ->disableOriginalConstructor()
      ->getMock();
    $routeStub1->method('matchesRequest')
      ->will($this->returnValue(false));
    $routeStub2 = $this->getMockBuilder('\\Asd\\Router\\Route')
      ->disableOriginalConstructor()
      ->getMock();
    $routeStub2->method('matchesRequest')
      ->will($this->returnValue(true));
    $requestStub = $this->getMockBuilder('\\Asd\\Http\\Request')
      ->disableOriginalConstructor()
      ->getMock();

    $this->router->addRoute($routeStub1);
    $this->router->addRoute($routeStub2);
    
    $this->assertSame($routeStub2, $this->router->matchRequest($requestStub));
  }

  /**
   * @test
   * @covers Asd\Router\Router::dispatch
   */
  public function dispatch()
  {
    $routeMock = $this->getMockBuilder('\\Asd\\Router\\Route')
      ->disableOriginalConstructor()
      ->getMock();
    $routeMock->method('getController')
      ->will($this->returnValue('Test\Unit\MyController'));
    $routeMock->method('getAction')
      ->will($this->returnValue('myAction'));

    $this->assertEquals('Hello World!', $this->router->dispatch($routeMock));
  }

  /**
   * @test
   * @covers Asd\Router\Router::setBasePath
   */
  public function setBasePath()
  {
    $basePath = 'some/base';
    $this->router->setBasePath($basePath);

    $requestStub = $this->getMockBuilder('\\Asd\\Http\\Request')
      ->disableOriginalConstructor()
      ->getMock();

    $routeMock = $this->getMockBuilder('\\Asd\\Router\\Route')
      ->disableOriginalConstructor()
      ->setMethods(array('matchesRequest'))
      ->getMock();
    $routeMock->method('matchesRequest')
      ->will($this->returnValue(true));
    $routeMock->expects($this->once())
      ->method('matchesRequest')
      ->with(
        $this->identicalTo($requestStub),
        $this->identicalTo($basePath)
      );

    $this->router->addRoute($routeMock);
    $this->router->matchRequest($requestStub);
  }
  
}