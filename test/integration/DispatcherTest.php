<?php
namespace Test\Integration;

use Asd\Dispatcher;
use Asd\Request;
use Asd\Response;
use Asd\Router;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Not a real test, just testing setup for future integration tests.
     * @test
     */
    public function dispatchernotARealTest()
    {
        $_SERVER['REQUEST_URI'] = 'bla/MyAction';
        $expected = '';
        $controller = new class(){
            public function __construct(){}
            public function myAction(){return '';}  
        };
        $factoryMock = $this->getMockBuilder('Asd\ControllerFactory')
            ->setMethods(array('createController'))
            ->getMock();
        $factoryMock->method('createController')
            ->willReturn($controller);
        $this->expectOutputString($expected);
        
        $req = new Request();
        $res = new Response();
        $router = new Router($factoryMock);
        $dispatcher = new Dispatcher($req, $res, $router);
        
        $dispatcher->dispatch();
    }
}