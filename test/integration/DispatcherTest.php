<?php
namespace Test\Integration;

use Asd\Dispatcher;
use Asd\Request;
use Asd\Response;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Not a real test, just testing setup for future integration tests.
     * @test
     */
    public function dispatchernotARealTest()
    {
        $expected = '';
        $this->expectOutputString($expected);
        $req = new Request();
        $res = new Response();
        $dispatcher = new Dispatcher($req, $res);
        
        $dispatcher->dispatch();
    }
}