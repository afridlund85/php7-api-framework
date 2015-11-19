<?php
namespace Test;

use Asd\Request;
use Asd\iRequest;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test 
     */
    public function implements_iRequestInterface()
    {
        $req = new Request();
        $this->assertTrue($req instanceof iRequest);
    }
    
    /**
     * @test
     * @covers Asd\Request::__construct
     * @covers Asd\Request::getUrl
     */
    public function constructor_readsPathInfoFromServerVar()
    {
        $expected = 'some/path';
        $_SERVER['PATH_INFO'] = $expected;
        $req = new Request();
        
        $actual = $req->getUrl();
        
        $this->assertEquals($expected, $actual);
    }
}