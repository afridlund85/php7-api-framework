<?php
namespace Test\Unit;

use Asd\Http\HttpHeaders;

class HttpHeadersTest extends \PHPUnit_Framework_TestCase
{
    protected $httpHeaders;
    protected $httpHeaderStub;

    public function setUp()
    {
        $this->httpHeaderStub = $this->getMockBuilder('\\Asd\\Http\\HttpHeader')
            ->disableOriginalConstructor()
            ->getMock();
        $this->httpHeaderStub->method('getHeaderName')->willReturn('myHeader');
        $this->httpHeaderStub->method('getValues')->willReturn(['myValue']);
        $this->httpHeaders = new HttpHeaders();
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeaders::getHeader
     * @covers Asd\Http\HttpHeaders::hasHeader
     */
    public function getHeader()
    {
        $httpHeaders = new HttpHeaders(['myHeader' => $this->httpHeaderStub]);
        $this->assertEquals($this->httpHeaderStub, $httpHeaders->getHeader('myHeader'));
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeaders::__construct
     * @covers Asd\Http\HttpHeaders::getHeader
     * @covers Asd\Http\HttpHeaders::hasHeader
     * @expectedException OutOfBoundsException
     */
    public function getHeader_whenEmpty()
    {
        $httpHeaders = new HttpHeaders(['myHeader' => $this->httpHeaderStub]);
        $httpHeaders->getHeader('invalidKey');
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeaders::__construct
     * @covers Asd\Http\HttpHeaders::getHeader
     * @covers Asd\Http\HttpHeaders::hasHeader
     * @expectedException OutOfBoundsException
     */
    public function getHeader_withInvalidKey()
    {
        $httpHeaders = new HttpHeaders(['myHeader' => $this->httpHeaderStub]);
        $httpHeaders->getHeader('invalidKey');
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeaders::getAllHeaders
     */
    public function getAllHeaders_whenEmpty()
    {
        $this->assertEquals([], $this->httpHeaders->getAllHeaders());
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeaders::__construct
     * @covers Asd\Http\HttpHeaders::getAllHeaders
     */
    public function getAllHeaders()
    {
        $httpHeaders = new HttpHeaders(['myHeader' => $this->httpHeaderStub]);
        $this->assertEquals([$this->httpHeaderStub], $httpHeaders->getAllHeaders());
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeaders::__construct
     * @covers Asd\Http\HttpHeaders::addHeader
     * @covers Asd\Http\HttpHeaders::getHeader
     * @covers Asd\Http\HttpHeaders::getAllHeaders
     */
    public function addHeader()
    {
        $httpHeaderStub = $this->getMockBuilder('\\Asd\\Http\\HttpHeader')
            ->disableOriginalConstructor()
            ->getMock();
        $httpHeaderStub->method('getHeaderName')->willReturn('otherHeader');
        $httpHeaderStub->method('getValues')->willReturn(['otherValue']);
        
        $httpHeaders = new HttpHeaders(['myHeader' => $this->httpHeaderStub]);
        $httpHeaders = $httpHeaders->addHeader($httpHeaderStub);

        $this->assertEquals($httpHeaderStub, $httpHeaders->getHeader('otherHeader'));
        $this->assertEquals([$this->httpHeaderStub, $httpHeaderStub], $httpHeaders->getAllHeaders());
        $this->assertNotSame($this->httpHeaders, $httpHeaders);
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeaders::__construct
     * @covers Asd\Http\HttpHeaders::addHeader
     * @covers Asd\Http\HttpHeaders::getHeader
     * @covers Asd\Http\HttpHeaders::getAllHeaders
     */
    public function addHeader_withExistingKey()
    {
        $httpHeaderStub = $this->getMockBuilder('\\Asd\\Http\\HttpHeader')
            ->disableOriginalConstructor()
            ->getMock();
        $httpHeaderStub->method('getHeaderName')->willReturn('myHeader');
        $httpHeaderStub->method('getValues')->willReturn(['otherValue']);
        
        $httpHeaders = new HttpHeaders(['myHeader' => $this->httpHeaderStub]);
        $httpHeaders = $this->httpHeaders->addHeader($httpHeaderStub);

        $this->assertEquals($httpHeaderStub, $httpHeaders->getHeader('myHeader'));
        $this->assertEquals([$httpHeaderStub], $httpHeaders->getAllHeaders());
        $this->assertNotSame($this->httpHeaders, $httpHeaders);
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeaders::removeHeader
     * @covers Asd\Http\HttpHeaders::hasHeader
     */
    public function removeHeader()
    {
        $httpHeaders = new HttpHeaders(['myHeader' => $this->httpHeaderStub]);
        $newHttpHeaders = $httpHeaders->removeHeader('myHeader');
        $this->assertEquals([], $newHttpHeaders->getAllHeaders());
        $this->assertNotSame($httpHeaders, $newHttpHeaders);
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeaders::removeHeader
     * @covers Asd\Http\HttpHeaders::hasHeader
     */
    public function removeHeader_onEmpty()
    {
        $newHttpHeaders = $this->httpHeaders->removeHeader('myHeader');
        $this->assertEquals([], $newHttpHeaders->getAllHeaders());
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeaders::removeHeader
     * @covers Asd\Http\HttpHeaders::hasHeader
     */
    public function removeHeader_invalidKey()
    {
        $httpHeaders = new HttpHeaders(['myHeader' => $this->httpHeaderStub]);
        $newHttpHeaders = $httpHeaders->removeHeader('invalidKey');
        $this->assertEquals([$this->httpHeaderStub], $newHttpHeaders->getAllHeaders());
    }
}