<?php
namespace Test\Unit;

use Asd\Http\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{

    protected $response;
    
    public function setUp()
    {
        $this->response = new Response();
    }

    /**
     * @test
     */
    public function implements_PSR7()
    {
        $this->assertInstanceOf('Asd\Http\Message', $this->response);
        $this->assertInstanceOf('Psr\Http\Message\MessageInterface', $this->response);
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $this->response);
    }

    /**
     * @test
     * @covers Asd\Http\Response::getStatusCode
     */
    public function getStatusCode()
    {
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * @test
     * @covers Asd\Http\Response::getReasonPhrase
     */
    public function getReasonPhrase()
    {
        $this->assertEquals('OK', $this->response->getReasonPhrase());
    }

    /**
     * @test
     * @covers Asd\Http\Response::withStatus
     */
    public function withStatus()
    {
        $this->response = $this->response->withStatus(300);
        $this->assertEquals(300, $this->response->getStatusCode());

        $this->response = $this->response->withStatus(400, 'a phrase');
        $this->assertEquals(400, $this->response->getStatusCode());
        $this->assertEquals('a phrase', $this->response->getReasonPhrase());
    }

    /**
     * @test
     * @covers Asd\Http\Response::withStatus
     */
    public function withStatus_isImmutable()
    {
        $response1 = $this->response->withStatus(300);
        $response2 = $response1->withStatus(404);

        $this->assertEquals(300, $response1->getStatusCode());
        $this->assertEquals(404, $response2->getStatusCode());
        $this->assertNotEquals($response1, $response2);
        $this->assertNotSame($response1, $response2);
    }

    /**
     * @test
     * @covers Asd\Http\Response::withStatus
     * @expectedException InvalidArgumentException
     */
    public function withStatus_statusCodeTooSmall()
    {
        $this->response->withStatus(99);
    }

    /**
     * @test
     * @covers Asd\Http\Response::withStatus
     * @expectedException InvalidArgumentException
     */
    public function withStatus_statusCodeTooBig()
    {
        $this->response->withStatus(600);
    }

    /**
     * @test
     * @covers Asd\Http\Response::withJson
     */
    public function withJsonResponse()
    {
        $response = $this->response->withJson(['Some value']);
        $this->assertEquals(['application/json;charset=utf-8'], $response->getHeader('Content-Type'));
        $this->assertEquals(json_encode(['Some value']), (string)$response->getBody());
    }

    /**
     * @test
     * @covers Asd\Http\Response::withText
     */
    public function withTextResponse()
    {
        $response = $this->response->withText('Some value');
        $this->assertEquals(['text/html;charset=utf-8'], $response->getHeader('Content-Type'));
        $this->assertEquals('Some value', (string)$response->getBody());
    }
}
