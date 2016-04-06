<?php
namespace Test\Unit;

use Asd\Controller;
use Asd\Http\Response;

class ClassA extends Controller
{
    public function jsonAction()
    {
        $res = new Response();
        return $this->withJsonResponse($res, 'Hello World!');
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
        $controller = new ClassA();
        $response = $controller->jsonAction();
        $this->assertEquals(['application/json;charset=utf-8'], $response->getHeader('Content-Type'));
        $this->assertEquals(json_encode('Hello World!'), (string)$response->getBody());
    }


}