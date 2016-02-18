<?php
namespace Test\Unit;

use Throwable;
use InvalidArgumentException;
use Asd\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{

  protected $getRoute;
  protected $postRoute;
  
  /**
   * @before
   */
  public function setup()
  {
      $this->getRoute = new Route('GET', 'my-path');
      $this->postRoute = new Route('POST', 'my-path');
  }

  /**
   * @test
   * @covers Asd\Router\Route::__construct
   */
  public function constructor_withLowerCaseMethod_ConvertsToUpperCase()
  {
    $r1 = new Route('get', 'my-path');
    $r2 = new Route('post', 'my-path');
    $this->assertEquals('GET', $r1->getMethod());
    $this->assertEquals('POST', $r2->getMethod());
  }

  /**
   * @test
   * @covers Asd\Router\Route::__construct
   * @covers Asd\Router\Route::getPath
   */
  public function constructor_addsInitialSlashToPath()
  {
    $r = new Route('get', 'my-path');
    $this->assertEquals('/my-path', $r->getPath());
  }

  /**
   * @test
   * @covers Asd\Router\Route::__construct
   * @covers Asd\Router\Route::getPath
   */
  public function constructor_removesUnnecessarySlashesFromPath()
  {
    $r1 = new Route('get', '//my-path');
    $r2 = new Route('get', 'my-path//');
    $r3 = new Route('get', '//my-path//');
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
    $r = new Route('get', '  my-path  ');
    $r = new Route('get', '  /my-path/  ');
    $r = new Route('get', '/  my-path  /');
    $r = new Route('get', '  /  my-path  /  ');
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
    $r = new Route('NOT_VALID_METHOD', 'my-path');
  }

  /**
   * @test
   * @covers Asd\Router\Route::equals
   */
  public function equals_withSameProperties_returnsTrue()
  {
    $r1 = new Route('GET', 'my-path');
    $r2 = new Route('GET', 'my-path');
    $this->assertTrue($r1->equals($r2));
  }

  /**
   * @test
   * @covers Asd\Router\Route::equals
   */
  public function equals_wherePropertiesDiffer_returnsFalse()
  {
    $r1 = new Route('GET', 'my-path');
    $r2 = new Route('GET', 'my-other-path');
    $r3 = new Route('POST', 'my-path');
    $this->assertFalse($r1->equals($r2));
    $this->assertFalse($r1->equals($r3));
  }


}