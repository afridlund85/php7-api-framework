<?php

namespace Test\Integration;

use InvalidArgumentException;
use Asd\{Asd, Controller};
use Asd\Http\{Request, Response, Uri};
use Asd\Router\{Router, Route};

class MyClass extends Controller
{
    public function jsonAction($req, $res)
    {
        return $this->json($res, 'Hello World!');
    }

    public function textAction($req, $res)
    {
        $body = $res->getBody();
        $body->write('Hello World!');
        $res = $res->withBody($body);
        return $res;
    }
}

class AsdTest extends \PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        $router = new Router();
        $uri = new Uri();
        $uri = $uri->withPath('path');
        $request = new Request('GET', $uri);
        $response = new Response();
        $this->app = new Asd($router, $request, $response);
    }

    /**
     * @test
     * @runInSeparateProcess
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::sendResponse
     */
    public function run_withControllerClass_jsonResponse()
    {
        $this->app->addRoute(new Route('GET', 'path', 'Test\Integration\MyClass::jsonAction'));
        $this->app->run();
        $this->expectOutputString(json_encode('Hello World!'));
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::sendResponse
     */
    public function run_withControllerClass_textResponse()
    {
        $this->app->addRoute(new Route('GET', 'path', 'Test\Integration\MyClass::textAction'));
        $this->app->run();
        $this->expectOutputString('Hello World!');
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::sendResponse
     */
    public function run_withAnonymusFunction()
    {
        $this->app->addRoute(new Route('GET', 'path', function($req, $res){
            $res->getBody()->write('Hi there');
            return $res;
        }));
        $this->app->run();
        $this->expectOutputString('Hi there');
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::sendResponse
     */
    public function run_anonymusFunction_withClassInstance()
    {
        $this->app->addRoute(new Route('GET', 'path', function($req, $res){
            $myClass = new MyClass();
            return $myClass->textAction($req, $res);
        }));
        $this->app->run();
        $this->expectOutputString('Hello World!');
    }

}