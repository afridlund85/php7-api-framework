<?php
namespace Test\Unit;

use Throwable;
use Asd\Router\Router;
use Asd\Router\Route;

class RouterTest extends \PHPUnit_Framework_TestCase
{

  protected $router;
  
  /**
   * @before
   */
  public function setup()
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

    $routeStub1->method('equals')->will($this->returnValue(true));
    
    $this->router->addRoute($routeStub1);
    $this->router->addRoute($routeStub2);
  }
}