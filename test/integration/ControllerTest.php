<?php

namespace Test\Integration;

use Asd\Controller;
use Test\Integration\Fakes\FakeController;
use Asd\Http\{Request, Response};

class ControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @covers Asd\Controller::withJsonResponse
     */
    public function withJsonResponse()
    {
        $request = new Request();
        $response = new Response();
        $controller = new FakeController();
        $response = $controller->jsonAction($request, $response);
        $this->assertEquals(['application/json;charset=utf-8'], $response->getHeader('Content-Type'));
        $this->assertEquals(json_encode('Some value'), (string)$response->getBody());
    }

    /**
     * @test
     * @covers Asd\Controller::withTextResponse
     */
    public function withTextResponse()
    {
        $request = new Request();
        $response = new Response();
        $controller = new FakeController();
        $response = $controller->textAction($request, $response);
        $this->assertEquals(['text/html;charset=utf-8'], $response->getHeader('Content-Type'));
        $this->assertEquals('Some value', (string)$response->getBody());
    }

}