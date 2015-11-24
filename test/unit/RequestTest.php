<?php
namespace Test\Unit;

use Asd\Request;
use Asd\iRequest;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    
    protected function setUp()
    {
        $_SERVER['PATH_INFO'] = null;
        $_SERVER['QUERY_STRING'] = null;
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
     * @covers Asd\Request::parseUrl
     * @covers Asd\Request::getUri
     */
    public function parseUrl_readsUri_FromPathInfo()
    {
        $expected = '/some/path';
        $_SERVER['PATH_INFO'] = $expected;
        $req = new Request();
        
        $actual = $req->getUri();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Request::__construct
     * @covers Asd\Request::parseUrl
     * @covers Asd\Request::getUri
     */
    public function parseUrl_readsUri_FromRequestUri_IfPathInfoIsMissing()
    {
        $expected = '/some/other/path';
        $_SERVER['REQUEST_URI'] = $expected;
        $req = new Request();
        
        $actual = $req->getUri();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Request::__construct
     * @covers Asd\Request::parseUrl
     * @covers Asd\Request::getUri
     */
    public function parseUrl_removesTrailingQueryParameters()
    {
        $expected = '/some/other/path';
        $_SERVER['REQUEST_URI'] = $expected . '?query=value';
        $req = new Request();
        
        $actual = $req->getUri();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Request::__construct
     * @covers Asd\Request::parseUrl
     * @covers Asd\Request::getUri
     */
    public function parseUrl_setsUriToSlash_WhenRequestHasNoUri()
    {
        $expected = '/';
        $_SERVER['REQUEST_URI'] = '';
        $req = new Request();
        
        $actual = $req->getUri();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Request::__construct
     * @covers Asd\Request::parseUrl
     * @covers Asd\Request::getUri
     */
    public function parseUrl_addsInitialSlashToUri_IfMissing()
    {
        $uri = 'one/path';
        $expected = '/' . $uri;
        $_SERVER['REQUEST_URI'] = $uri;
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
    public function parseQueries_readsQueryValues_FromGet()
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
    public function parseQueries_readsQueryValues_FromServerQueryString()
    {
        $expected = ['param1' => 1, 'param2' => 'two'];
        $params = [];
        foreach($expected as $k => $v){
            $params[] = $k . '=' . $v;
        }
        $_SERVER['QUERY_STRING'] = implode('&', $params);
        
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
    public function parseQueries_readsQueryValues_FromUri()
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
    
    /**
     * @test
     * @covers Asd\Request::__construct
     * @covers Asd\Request::parseQueries
     * @covers Asd\Request::getQueries
     */
    public function parseQueries_mergesQueries_fromGetAndUri()
    {
        $expected = ['param1' => 'value1', 'param2' => 'two'];
        $_SERVER['REQUEST_URI'] = '?param1=value1';
        $_GET['param2'] = 'two';
        
        $req = new Request();
        
        $actual = $req->getQueries();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Request::GetQuery
     * @expectedException \Exception
     */
    public function getQuery_withNoArgument_throwsException()
    {
        $req = new Request();
        $req->getQuery();
    }
    
    /**
     * @test
     * @covers Asd\Request::GetQuery
     * @expectedException \Exception
     */
    public function getQuery_withNonExistingKey_throwsException()
    {
        $req = new Request();
        $req->getQuery('iDontExist');
    }
    
    /**
     * @test
     * @covers Asd\Request::GetQuery
     */
    public function getQuery_withValidKey_returnsQueryValue()
    {
        $key = 'theKey';
        $expected = 'theValue';
        $_GET[$key] = $expected;
        $req = new Request();
        
        $actual = $req->getQuery($key);
        
        $this->assertEquals($expected, $actual);
    }
}