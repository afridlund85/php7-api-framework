<?php
namespace Test\Unit;

use Throwable;
use InvalidArgumentException;
use Asd\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{

  protected $getRoute;
  protected $postRoute;
  protected $uriStub;
  protected $requestMock;
  
  public function setUp()
  {
    $this->getRoute = new Route('GET', 'my-path', 'controller@method');
    $this->postRoute = new Route('POST', 'my-path', 'controller@method');
    $this->uriStub = $this->getMockBuilder('\\Asd\\Http\\Uri')
      ->disableOriginalConstructor()
      ->getMock();

    $this->requestMock = $this->getMockBuilder('\\Asd\\Http\\Request')
      ->disableOriginalConstructor()
      ->getMock();
  }

  /**
   * @test
   * @covers Asd\Router\Route::__construct
   */
  public function constructor_withLowerCaseMethod_ConvertsToUpperCase()
  {
    $r1 = new Route('get', '', 'class@method');
    $r2 = new Route('post', '', 'class@method');
    $this->assertEquals('GET', $r1->getMethod());
    $this->assertEquals('POST', $r2->getMethod());
  }

  /**
   * @test
   * @covers Asd\Router\Route::parseCallback
   * @expectedException InvalidArgumentException
   */
  public function parseCallback_emptyCallback()
  {
    new Route('get', '', '');
  }

  /**
   * @test
   * @covers Asd\Router\Route::parseCallback
   * @expectedException InvalidArgumentException
   */
  public function parseCallback_missingMethod()
  {
    new Route('get', '', 'class');
  }

  /**
   * @test
   * @covers Asd\Router\Route::getPath
   */
  public function getPath()
  {
    $r = new Route('get', '', 'class@method');
    $this->assertEquals('/', $r->getPath());
  }

  /**
   * @test
   * @covers Asd\Router\Route::__construct
   * @covers Asd\Router\Route::getPath
   */
  public function constructor_addsInitialSlashToPath()
  {
    $r = new Route('get', 'my-path', 'class@method');
    $this->assertEquals('/my-path', $r->getPath());
  }

  /**
   * @test
   * @covers Asd\Router\Route::__construct
   * @covers Asd\Router\Route::getPath
   */
  public function constructor_removesUnnecessarySlashesFromPath()
  {
    $r1 = new Route('get', '//my-path', 'class@method');
    $r2 = new Route('get', 'my-path//', 'class@method');
    $r3 = new Route('get', '//my-path//', 'class@method');
    $this->assertEquals('/my-path', $r1->getPath());
    $this->assertEquals('/my-path', $r2->getPath());
    $this->assertEquals('/my-path', $r3->getPath());
  }

  /**
   * @test
   * @covers Asd\Router\Route::__construct
   * @covers Asd\Router\Route::getPath
   */
  public function constructor_trimsWhitespaceFromPath()
  {
    $r = new Route('get', '  my-path  ', 'class@method');
    $r = new Route('get', '  /my-path/  ', 'class@method');
    $r = new Route('get', '/  my-path  /', 'class@method');
    $r = new Route('get', '  /  my-path  /  ', 'class@method');
    $this->assertEquals('/my-path', $r->getPath());
  }

  /**
   * @test
   * @covers Asd\Router\Route::getMethod
   */
  public function getMethod_returnsMethod()
  {
    $this->assertEquals('GET', $this->getRoute->getMethod());
    $this->assertEquals('POST', $this->postRoute->getMethod());
  }

  /**
   * @test
   * @covers Asd\Router\Route::__construct
   * @covers Asd\Router\Route::isValidMethod
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage "NOT_VALID_METHOD" is not a valid method.
   */
  public function constructor_withInvalidMethod_ThrowsException()
  {
    $r = new Route('NOT_VALID_METHOD', 'my-path', 'class@method');
  }

  /**
   * @test
   * @covers Asd\Router\Route::equals
   */
  public function equals_withSameProperties_returnsTrue()
  {
    $r1 = new Route('GET', 'my-path', 'class@method');
    $r2 = new Route('GET', 'my-path', 'class@method');
    $this->assertTrue($r1->equals($r2));
  }

  /**
   * @test
   * @covers Asd\Router\Route::equals
   */
  public function equals_wherePropertiesDiffer_returnsFalse()
  {
    $r1 = new Route('GET', 'my-path', 'class@method');
    $r2 = new Route('GET', 'my-other-path', 'class@method');
    $r3 = new Route('POST', 'my-path', 'class@method');
    $this->assertFalse($r1->equals($r2));
    $this->assertFalse($r1->equals($r3));
  }

  /**
   * @test
   * @covers Asd\Router\Route::getController
   * @covers Asd\Router\Route::parseCallback
   */
  public function getController()
  {
    $r = new Route('GET', 'my-path', 'class@method');
    $this->assertEquals('class', $r->getController());
  }

  /**
   * @test
   * @covers Asd\Router\Route::getAction
   * @covers Asd\Router\Route::parseCallback
   */
  public function getAction()
  {
    $r = new Route('GET', 'my-path', 'class@method');
    $this->assertEquals('method', $r->getAction());
  }

  /**
   * @test
   * @covers Asd\Router\Route::matchesRequest
   */
  public function matchesRequest_withoutBase()
  {
    $this->requestMock->method('getMethod')->willReturn('GET');
    $this->requestMock->method('getUri')->willReturn($this->uriStub);
    $this->uriStub->method('getPath')->willReturn('my-path');

    $this->assertTrue($this->getRoute->matchesRequest($this->requestMock));
  }

  /**
   * @test
   * @covers Asd\Router\Route::matchesRequest
   */
  public function matchesRequest_withoutBase2()
  {
    $this->requestMock->method('getMethod')->willReturn('GET');
    $this->requestMock->method('getUri')->willReturn($this->uriStub);
    $this->uriStub->method('getPath')->willReturn('/my-path');

    $this->assertTrue($this->getRoute->matchesRequest($this->requestMock));
  }

  /**
   * @test
   * @covers Asd\Router\Route::matchesRequest
   */
  public function matchesRequest_withoutBase3()
  {
    $this->requestMock->method('getMethod')->willReturn('GET');
    $this->requestMock->method('getUri')->willReturn($this->uriStub);
    $this->uriStub->method('getPath')->willReturn('/my-path/');

    $this->assertTrue($this->getRoute->matchesRequest($this->requestMock));
  }

  /**
   * @test
   * @covers Asd\Router\Route::matchesRequest
   */
  public function matchesRequest_withoutBase4()
  {
    $this->requestMock->method('getMethod')->willReturn('GET');
    $this->requestMock->method('getUri')->willReturn($this->uriStub);
    $this->uriStub->method('getPath')->willReturn('my-path/');

    $this->assertTrue($this->getRoute->matchesRequest($this->requestMock));
  }

  /**
   * @test
   * @covers Asd\Router\Route::matchesRequest
   */
  public function matchesRequest_withBase()
  {
    $route = new Route('GET', '/path', 'c@a');
    $this->requestMock->method('getMethod')->willReturn('GET');
    $this->requestMock->method('getUri')->willReturn($this->uriStub);
    $this->uriStub->method('getPath')->willReturn('the-base/path');

    $this->assertTrue($route->matchesRequest($this->requestMock, 'the-base'));
  }

  /**
   * @test
   * @covers Asd\Router\Route::matchesRequest
   */
  public function matchesRequest_withBase2()
  {
    $route = new Route('GET', '/my/long/path/', 'c@a');
    $this->requestMock->method('getMethod')->willReturn('GET');
    $this->requestMock->method('getUri')->willReturn($this->uriStub);
    $this->uriStub->method('getPath')->willReturn('/some/base/my/long/path/');

    $this->assertTrue($route->matchesRequest($this->requestMock, '/some/base'));
  }

  /**
   * @test
   * @covers Asd\Router\Route::matchesRequest
   */
  public function matchesRequest_withBase3()
  {
    $route = new Route('GET', '/my/long/path/', 'c@a');
    $this->requestMock->method('getMethod')->willReturn('GET');
    $this->requestMock->method('getUri')->willReturn($this->uriStub);
    $this->uriStub->method('getPath')->willReturn('/base/my/long/path');

    $this->assertTrue($route->matchesRequest($this->requestMock, '/base/'));
  }

  /**
   * @test
   * @covers Asd\Router\Route::matchesRequest
   */
  public function matchesRequest_withBase4()
  {
    $route = new Route('GET', '/my/long/path/', 'c@a');
    $this->requestMock->method('getMethod')->willReturn('GET');
    $this->requestMock->method('getUri')->willReturn($this->uriStub);
    $this->uriStub->method('getPath')->willReturn('/with/base/my/long/path/');

    $this->assertTrue($route->matchesRequest($this->requestMock, 'with/base/'));
  }

  /**
   * @test
   * @covers Asd\Router\Route::matchesRequest
   */
  public function matchesRequest_incorrectMethod()
  {
    $this->requestMock->method('getMethod')->willReturn('POST');
    $this->requestMock->method('getUri')->willReturn($this->uriStub);
    $this->uriStub->method('getPath')->willReturn('/my-path');

    $this->assertFalse($this->getRoute->matchesRequest($this->requestMock));
  }

  /**
   * @test
   * @covers Asd\Router\Route::matchesRequest
   */
  public function matchesRequest_nonMatchingRequest()
  {
    $this->requestMock->method('getMethod')->willReturn('GET');
    $this->requestMock->method('getUri')->willReturn($this->uriStub);

    $this->uriStub->method('getPath')->willReturn('/other-path');
    $this->assertFalse($this->getRoute->matchesRequest($this->requestMock));
  }


}