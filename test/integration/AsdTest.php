<?php

namespace Test\Integration;

use InvalidArgumentException;
use Asd\Asd;
use Asd\Http\{Request, Response, Uri};
use Asd\Router\{Router, Route, FunctionCallback, MethodCallback};
use Test\Integration\Fakes\{FakeClass, FakeClassDep, FakeDependency};

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
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::sendResponse
     * @covers Asd\Asd::sendHeaders
     */
    public function run_method()
    {
        $this->app->addRoute(new Route('GET', 'path', new MethodCallback('Test\Integration\Fakes', 'FakeClass', 'action')));
        $this->app->run();
        $this->expectOutputString('Some value');
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::sendResponse
     * @covers Asd\Asd::sendHeaders
     */
    public function run_method_withDependency()
    {
        $this->app->addRoute(new Route('GET', 'path', new MethodCallback('Test\Integration\Fakes', 'FakeClassDep', 'action')));
        $this->app->run();
        $this->expectOutputString('Some value');
    }

    /**
     * @test
     * @runInSeparateProcess
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::sendResponse
     * @covers Asd\Asd::sendHeaders
     */
    public function run_method_withJsonResponse()
    {
        $this->app->addRoute(new Route('GET', 'path', new MethodCallback('Test\Integration\Fakes', 'FakeClass', 'jsonAction')));
        $this->app->run();
        $this->expectOutputString(json_encode(['Some value']));
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::sendResponse
     * @covers Asd\Asd::sendHeaders
     */
    public function run_closure()
    {
        $this->app->addRoute(new Route('GET', 'path', new FunctionCallback(function($req, $res){
            $res->getBody()->write('Hi there');
            return $res;
        })));

        $this->app->run();
        $this->expectOutputString('Hi there');
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::sendResponse
     * @covers Asd\Asd::sendHeaders
     */
    public function run_closure_withClassInstance()
    {
        $this->app->addRoute(new Route('GET', 'path', new FunctionCallback(function($req, $res){
            $fake = new FakeClass();
            return $fake->action($req, $res);
        })));
        $this->app->run();
        $this->expectOutputString('Some value');
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::sendResponse
     * @covers Asd\Asd::sendHeaders
     */
    public function run_closure_withDependency()
    {
        $this->app->addRoute(new Route('GET', 'path', new FunctionCallback(function($req, $res, FakeDependency $dep){
            $dep->setValue('Hi');
            $res->getBody()->write($dep->getValue());
            return $res;
        })));
        $this->app->run();
        $this->expectOutputString('Hi');
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::sendResponse
     * @covers Asd\Asd::sendHeaders
     */
    public function run_closure_withJsonResponse()
    {
        $this->app->addRoute(new Route('GET', 'path', new FunctionCallback(function($req, $res){
            return $res->withJson(['Hi']);
        })));
        $this->app->run();
        $this->expectOutputString(json_encode(['Hi']));
    }

}
