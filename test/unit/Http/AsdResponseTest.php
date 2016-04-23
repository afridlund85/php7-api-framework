<?php
namespace Test\Unit;

use Asd\Http\AsdResponse;

class AsdResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @covers Asd\Http\AsdResponse::__construct
     * @covers Asd\Http\AsdResponse::withJson
     */
    public function withJsonResponse()
    {
        $response = new AsdResponse();
        $response = $response->withJson(['Some value']);
        $this->assertEquals(['application/json;charset=utf-8'], $response->getHeader('Content-Type'));
        $this->assertEquals(json_encode(['Some value']), (string)$response->getBody());
    }

    /**
     * @test
     * @covers Asd\Http\AsdResponse::__construct
     * @covers Asd\Http\AsdResponse::withText
     */
    public function withTextResponse()
    {
        $response = new AsdResponse();
        $response = $response->withText('Some value');
        $this->assertEquals(['text/html;charset=utf-8'], $response->getHeader('Content-Type'));
        $this->assertEquals('Some value', (string)$response->getBody());
    }
}
