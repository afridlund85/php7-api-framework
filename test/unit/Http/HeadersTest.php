<?php
namespace Test\Unit;

use Asd\Http\Headers;

class HeadersTest extends \PHPUnit_Framework_TestCase
{
    protected $headers;
    
    public function setUp()
    {
        $this->headers = new Headers();
    }

    /**
    * @test
    * @covers Asd\Http\Headers::withGlobals
    */
    public function withGlobals_contentVariables()
    {
        $env = [
            'CONTENT_LENGTH' => '123',
            'CONTENT_TYPE' => 'text/html',
            'CONTENT_MD5' => md5('hello'),
        ];
        $headers = $this->headers->withGlobals($env);
        $this->assertEquals('123', $headers->get('Content-Length')->getHeaderLine());
        $this->assertEquals('text/html', $headers->get('Content-Type')->getHeaderLine());
        $this->assertEquals(md5('hello'), $headers->get('Content-Md5')->getHeaderLine());
    }

    /**
    * @test
    * @covers Asd\Http\Headers::withGlobals
    */
    public function withGlobals_RedirectHttpAuthVarialbes()
    {
        $env = [
            'REDIRECT_HTTP_AUTHORIZATION' => 'Some Auth String'
        ];
        $headers = $this->headers->withGlobals($env);
        $this->assertEquals('Some Auth String', $headers->get('Authorization')->getHeaderLine());
    }

    /**
    * @test
    * @covers Asd\Http\Headers::withGlobals
    */
    public function withGlobals_Digest()
    {
        $env = [
            'PHP_AUTH_DIGEST' => 'Some Digest String'
        ];
        $headers = $this->headers->withGlobals($env);
        $this->assertEquals('Some Digest String', $headers->get('Authorization')->getHeaderLine());
    }

    /**
    * @test
    * @covers Asd\Http\Headers::withGlobals
    */
    public function withGlobals_BasicAuth()
    {
        $env = [
            'PHP_AUTH_USER' => 'username',
            'PHP_AUTH_PW' => 'password'
        ];
        $expected = 'Basic ' . base64_encode('username:password');
        $headers = $this->headers->withGlobals($env);
        $this->assertEquals($expected, $headers->get('Authorization')->getHeaderLine());
    }

    /**
    * @test
    * @covers Asd\Http\Headers::withGlobals
    */
    public function withGlobals_HttpPrefixedHeaders()
    {
        $env = [
            'HTTP_HOST' => 'domain.io',
            'HTTP_CACHE_CONTROL' => 'max-age=0'
        ];
        $headers = $this->headers->withGlobals($env);
        $this->assertEquals('domain.io', $headers->get('Host')->getHeaderLine());
        $this->assertEquals('max-age=0', $headers->get('Cache-Control')->getHeaderLine());
    }
}