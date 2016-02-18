<?php
namespace Test\Unit;

use Throwable;
use Asd\Asd;
use Asd\Http\Request;
use Asd\Router\Router;
use Asd\Router\Route;

class AsdTest extends \PHPUnit_Framework_TestCase
{
  protected $asd;
  protected $routeStub;
  protected $routerStub;
  protected $requestStub;
  /**
   * @before
   */
  public function setup()
  {
    $this->routeStub = $this->getMockBuilder('\\Asd\\Router\\Route')->disableOriginalConstructor()->getMock();
    $this->requestStub = $this->getMockBuilder('\\Asd\\Http\\Request')->disableOriginalConstructor()->getMock();
    $this->routerStub = $this->getMockBuilder('\\Asd\\Router\\Router')->disableOriginalConstructor()->getMock();
    $this->asd = new Asd($this->requestStub, $this->routerStub);
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

    $asd = new Asd($this->requestStub, $routerMock);
    $asd->addRoute($this->routeStub);
  }
}