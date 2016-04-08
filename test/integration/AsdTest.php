<?php

namespace Test\Integration;

use InvalidArgumentException;
use Asd\{Asd, Controller};
use Asd\Http\{Request, Response, Uri};
use Asd\Router\{Router, Route};

class Dep
{
    public function __construct(){}
    public function getStuff(){return 'stuff';}
}

class ClassA extends Controller
{
    public function jsonAction($req, $res)
    {
        return $this->withJsonResponse($res, 'Hello World!');
    }

    public function textAction($req, $res)
    {
        $body = $res->getBody();
        $body->write('Hello World!');
        return $res->withBody($body);
    }
}

class ClassB extends Controller
{
    private $dep;
    public function __construct(Dep $dep)
    {
        $this->dep = $dep;
    }
    public function jsonAction($req, $res)
    {
        return $this->withJsonResponse($res, $this->dep->getStuff());
    }
}

class ClassC
{
    private $dep;
    public function __construct(Dep $dep)
    {
        $this->dep = $dep;
    }
    public function textAction($req, $res)
    {
        $body = $res->getBody();
        $body->write($this->dep->getStuff());
        return $res->withBody($body);
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
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::dispatchClass
     * @covers Asd\Asd::getDependencies
     * @covers Asd\Asd::sendResponse
     * @covers Asd\Asd::sendHeaders
     */
    public function run_withControllerClass_jsonResponse()
    {
        $this->app->addRoute(new Route('GET', 'path', array('Test\Integration\ClassA', 'jsonAction')));
        $this->app->run();
        $this->expectOutputString(json_encode('Hello World!'));
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::dispatchClosure
     * @covers Asd\Asd::getDependencies
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
     * @covers Asd\Asd::dispatchClosure
     * @covers Asd\Asd::getDependencies
     * @covers Asd\Asd::sendResponse
     */
    public function run_anonymusFunction_withClassInstance()
    {
        $this->app->addRoute(new Route('GET', 'path', function($req, $res){
            $ClassA = new ClassA();
            return $ClassA->textAction($req, $res);
        }));
        $this->app->run();
        $this->expectOutputString('Hello World!');
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::dispatchClosure
     * @covers Asd\Asd::getDependencies
     * @covers Asd\Asd::sendResponse
     */
    public function run_anonymusFunction_withDependency()
    {
        $this->app->addRoute(new Route('GET', 'path', function($req, $res, ClassA $class){
            return $class->textAction($req, $res);
        }));
        $this->app->run();
        $this->expectOutputString('Hello World!');
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::dispatchClass
     * @covers Asd\Asd::getDependencies
     * @covers Asd\Asd::sendResponse
     */
    public function run_class_withExtend_withDependency()
    {
        $this->app->addRoute(new Route('GET', 'path', array('Test\Integration\ClassB', 'jsonAction')));
        $this->app->run();
        $this->expectOutputString(json_encode('stuff'));
    }

    /**
     * @test
     * @covers Asd\Asd::run
     * @covers Asd\Asd::dispatch
     * @covers Asd\Asd::dispatchClass
     * @covers Asd\Asd::getDependencies
     * @covers Asd\Asd::sendResponse
     */
    public function run_class_withoutExtend_withDependency()
    {
        $this->app->addRoute(new Route('GET', 'path', array('Test\Integration\ClassC', 'textAction')));
        $this->app->run();
        $this->expectOutputString('stuff');
    }

}