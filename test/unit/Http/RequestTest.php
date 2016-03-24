<?php
namespace Test\Unit;

use Asd\Http\Request;
use Asd\Http\Message;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\MessageInterface;

class RequestTest extends \PHPUnit_Framework_TestCase
{

  protected $request;

  /**
   * @before
   */
  public function setup()
  {
    $this->request = new Request();
  }

  /**
   * @test
   */
  public function implements_PSR7()
  {
    $this->assertInstanceOf('Asd\Http\Message', $this->request);
    $this->assertInstanceOf('Psr\Http\Message\MessageInterface', $this->request);
    $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $this->request);
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   */
  public function __construct_invalidMethod()
  {
    new Request('Not_a_http_method');
  }

  /**
   * @test
   * @covers Asd\Http\Request::getUri
   */
  public function getUri()
  {
    $uriStub = $this->getMockBuilder('\\Asd\\Http\\Uri')
      ->disableOriginalConstructor()
      ->getMock();
    $request = new Request('GET', $uriStub);

    $this->assertEquals($uriStub, $request->getUri());
    $this->assertSame($uriStub, $request->getUri());
  }
  
}