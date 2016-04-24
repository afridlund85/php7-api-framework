<?php
namespace Test\Unit;

use Asd\Http\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{

    protected $request;
    protected $uriStub;
    protected $requestBodyStub;
    protected $headersStub;

    public function setUp()
    {
        $this->uriStub = $this->getMockBuilder('\\Asd\\Http\\Uri')
            ->disableOriginalConstructor()
            ->getMock();
        $this->requestBodyStub = $this->getMockBuilder('\\Asd\\Http\\RequestBody')
            ->disableOriginalConstructor()
            ->getMock();
        $this->headersStub = $this->getMockBuilder('\\Asd\\Http\\Headers')
            ->disableOriginalConstructor()
            ->getMock();
        $this->request = new Request('GET', $this->uriStub, $this->requestBodyStub, $this->headersStub);
    }

    /**
     * @test
     */
    public function implements_PSR7()
    {
        $this->assertInstanceOf('Asd\Http\Message', $this->request);
        $this->assertInstanceOf('Psr\Http\Message\MessageInterface', $this->request);
        $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $this->request);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function __construct_invalidMethod()
    {
        new Request('Not_a_http_method');
    }

    /**
     * @test
     * @covers Asd\Http\Request::getUri
     */
    public function getUri()
    {
        $this->assertEquals($this->uriStub, $this->request->getUri());
        $this->assertSame($this->uriStub, $this->request->getUri());
    }

    /**
     * @test
     * @covers Asd\Http\Request::withUri
     */
    public function withUri()
    {
        $headerStub = $this->getMockBuilder('\\Asd\\Http\\Header')
            ->disableOriginalConstructor()
            ->getMock();
        $headerStub->method('getValues')->willReturn(['host.com']);
        $this->headersStub->method('containsKey')->willReturn(false);
        $this->headersStub->method('put')->willReturn($this->headersStub);
        $this->headersStub->method('get')->willReturn($headerStub);

        $uriMock = $this->getMockBuilder('\\Asd\\Http\\Uri')
            ->disableOriginalConstructor()
            ->getMock();
        $uriMock->method('getHost')->willReturn('host.com');
        $request = $this->request->withUri($uriMock);

        $this->assertEquals($uriMock, $request->getUri());
        $this->assertSame($uriMock, $request->getUri());

        $this->assertEquals(['host.com'], $request->getHeader('Host'));
    }

    /**
     * @test
     * @covers Asd\Http\Request::withUri
     */
    public function withUri_preserveHost_noPreviousHost_uriWithHost()
    {
        $headerStub = $this->getMockBuilder('\\Asd\\Http\\Header')
            ->disableOriginalConstructor()
            ->getMock();
        $headerStub->method('getValues')->willReturn(['host.com']);
        $this->headersStub->method('containsKey')->willReturn(false);
        $this->headersStub->method('put')->willReturn($this->headersStub);
        $this->headersStub->method('get')->willReturn($headerStub);

        $uriMock = $this->getMockBuilder('\\Asd\\Http\\Uri')
            ->disableOriginalConstructor()
            ->getMock();
        $uriMock->method('getHost')->willReturn('host.com');

        $request = $this->request->withUri($uriMock, true);

        $this->assertFalse($this->request->hasHeader('Host'));
        $this->assertEquals(['host.com'], $request->getHeader('Host'));
    }

    /**
     * @test
     * @covers Asd\Http\Request::withUri
     */
    public function withUri_preserveHost_hasPreviousHost_uriWithHost()
    {
        $headerStub = $this->getMockBuilder('\\Asd\\Http\\Header')
            ->disableOriginalConstructor()
            ->getMock();
        $headerStub->method('withValues')->willReturn($headerStub);
        $headerStub->method('getName')->willReturn('Host');
        $headerStub->method('getValues')->willReturn(['firsthost.io']);

        $this->headersStub->method('containsKey')->willReturn(true);
        $this->headersStub->method('put')->willReturn($this->headersStub);
        $this->headersStub->method('get')->willReturn($headerStub);

        $uriMock = $this->getMockBuilder('\\Asd\\Http\\Uri')
            ->disableOriginalConstructor()->getMock();
        $uriMock->method('getHost')->willReturn('host.com');

        $request = $this->request->withHeader('Host', 'firsthost.io');
        $request = $request->withUri($uriMock, true);

        $this->assertEquals(['firsthost.io'], $request->getHeader('Host'));
    }

    /**
     * @test
     * @covers Asd\Http\Request::withUri
     */
    public function withUri_preserveHost_hasPreviousHost_uriWithoutHost()
    {
        $headerStub = $this->getMockBuilder('\\Asd\\Http\\Header')
            ->disableOriginalConstructor()
            ->getMock();
        $headerStub->method('withValues')->willReturn($headerStub);
        $headerStub->method('getName')->willReturn('Host');
        $headerStub->method('getValues')->willReturn(['firsthost.io']);
        
        $this->headersStub->method('containsKey')->willReturn(true);
        $this->headersStub->method('put')->willReturn($this->headersStub);
        $this->headersStub->method('get')->willReturn($headerStub);
        
        $uriMock = $this->getMockBuilder('\\Asd\\Http\\Uri')
            ->disableOriginalConstructor()->getMock();

        $request = $this->request->withHeader('Host', 'firsthost.io');
        $request = $request->withUri($uriMock, true);

        $this->assertTrue($request->hasHeader('Host'));
        $this->assertEquals(['firsthost.io'], $request->getHeader('Host'));
    }

    /**
     * @test
     * @covers Asd\Http\Request::withUri
     */
    public function withUri_isImmutable()
    {
        $uriStub = $this->getMockBuilder('\\Asd\\Http\\Uri')
            ->disableOriginalConstructor()
            ->getMock();
        $request = $this->request->withUri($uriStub);

        $this->assertNotSame($this->request->getUri(), $request->getUri());
        $this->assertNotSame($this->request, $request);
    }

    /**
     * @test
     * @covers Asd\Http\Request::getRequestTarget
     */
    public function getRequestTarget()
    {
        $this->assertEquals('/', $this->request->getRequestTarget());
    }

    /**
     * @test
     * @covers Asd\Http\Request::withRequestTarget
     * @covers Asd\Http\Request::getRequestTarget
     */
    public function withRequestTarget()
    {
        $request = $this->request->withRequestTarget('/the/target?is=true');
        $this->assertEquals('/the/target?is=true', $request->getRequestTarget());
    }

    /**
     * @test
     * @covers Asd\Http\Request::withRequestTarget
     */
    public function withRequestTarget_isImmutable()
    {
        $request = $this->request->withRequestTarget('/the/target?is=true');

        $this->assertNotSame($this->request->getRequestTarget(), $request->getRequestTarget());
        $this->assertNotSame($this->request, $request);
    }

    /**
     * @test
     * @covers Asd\Http\Request::getMethod
     */
    public function getMethod()
    {
        $this->assertEquals('GET', $this->request->getMethod());
    }

    /**
     * @test
     * @covers Asd\Http\Request::withMethod
     * @covers Asd\Http\Request::getMethod
     */
    public function withMethod()
    {
        $request = $this->request->withMethod('PUT');
        $this->assertEquals('PUT', $request->getMethod());

        $request = $this->request->withMethod('delete');
        $this->assertEquals('delete', $request->getMethod());
    }

    /**
     * @test
     * @covers Asd\Http\Request::withMethod
     */
    public function withMethod_isImmutable()
    {
        $request = $this->request->withMethod('PUT');
        
        $this->assertNotSame($this->request->getMethod(), $request->getMethod());
        $this->assertNotSame($this->request, $request);
    }
    
}