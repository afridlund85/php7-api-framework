<?php

namespace Test\Integration;

use Asd\Controller;
use Asd\Http\Response;

class ClassD extends Controller
{
    public function jsonAction()
    {
        $res = new Response();
        return $this->withJsonResponse($res, 'Hello World!');
    }

    public function textAction()
    {
        $res = new Response();
        return $this->withTextResponse($res, 'Hello World!');
    }
}

class ControllerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @covers Asd\Controller::withJsonResponse
     */
    public function withJsonResponse()
    {
        $controller = new ClassD();
        $response = $controller->jsonAction();
        $this->assertEquals(['application/json;charset=utf-8'], $response->getHeader('Content-Type'));
        $this->assertEquals(json_encode('Hello World!'), (string)$response->getBody());
    }

    /**
     * @test
     * @covers Asd\Controller::withTextResponse
     */
    public function withTextResponse()
    {
        $controller = new ClassD();
        $response = $controller->textAction();
        $this->assertEquals(['text/html;charset=utf-8'], $response->getHeader('Content-Type'));
        $this->assertEquals('Hello World!', (string)$response->getBody());
    }

}