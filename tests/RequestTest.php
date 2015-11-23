<?php
namespace Test;

use Asd\Request;
use Asd\iRequest;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    
    protected function setUp()
    {
        $_SERVER['PATH_INFO'] = null;
        $_SERVER['REQUEST_URI'] = null;
    }
    
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
    
    /**
     * @test
     * @covers Asd\Request::__construct
     * @covers Asd\Request::getUrl
     */
    public function constructor_readsUriFromRequestUriIfPathInfoIsUnset()
    {
        $expected = 'some/other/path';
        $_SERVER['REQUEST_URI'] = $expected;
        $req = new Request();
        
        $actual = $req->getUrl();
        
        $this->assertEquals($expected, $actual);
    }
}