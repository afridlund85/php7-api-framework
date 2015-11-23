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
        $_SERVER['PHP_SELF'] = null;
        $_GET = null;
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
     * @covers Asd\Request::parseUri
     * @covers Asd\Request::getUri
     */
    public function constructor_readsUri_FromPathInfo()
    {
        $expected = 'some/path';
        $_SERVER['PATH_INFO'] = $expected;
        $req = new Request();
        
        $actual = $req->getUri();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Request::__construct
     * @covers Asd\Request::parseUri
     * @covers Asd\Request::getUri
     */
    public function constructor_readsUri_FromRequestUri_IfPathInfoIsMissing()
    {
        $expected = 'some/other/path';
        $_SERVER['REQUEST_URI'] = $expected;
        $req = new Request();
        
        $actual = $req->getUri();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Request::__construct
     * @covers Asd\Request::parseQueries
     * @covers Asd\Request::getQueries
     */
    public function constructor_readsQueryValues_FromGet()
    {
        $expected = ['first' => 1, 'second' => 'two'];
        foreach($expected as $k => $v){
            $_GET[$k] = $v;
        }
        $req = new Request();
        
        $actual = $req->getQueries();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Request::__construct
     * @covers Asd\Request::parseQueries
     * @covers Asd\Request::getQueries
     */
    public function constructor_readsQueryValues_FromUri()
    {
        $expected = ['param1' => 1, 'param2' => 'two'];
        $params = [];
        foreach($expected as $k => $v){
            $params[] = $k . '=' . $v;
        }
        $_SERVER['REQUEST_URI'] = '?' . implode('&', $params);
        
        $req = new Request();
        
        $actual = $req->getQueries();
        
        $this->assertEquals($expected, $actual);
    }
}