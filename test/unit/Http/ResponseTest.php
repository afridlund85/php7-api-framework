<?php
namespace Test\Unit;

use Asd\Http\Response;
use Asd\Http\Message;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\MessageInterface;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
  
  /**
   * @test
   */
  public function implements_PSR7()
  {
    $response = new Response();
    $this->assertTrue($response instanceof Message);
    $this->assertTrue($response instanceof MessageInterface);
    $this->assertTrue($response instanceof ResponseInterface);
  }
}