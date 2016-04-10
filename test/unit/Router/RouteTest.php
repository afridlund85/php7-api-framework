<?php
namespace Test\Unit;

use Throwable;
use InvalidArgumentException;
use Asd\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    protected $uriStub;
    protected $requestMock;
    protected $callbackStub;
    
    public function setUp()
    {
        $this->uriStub = $this->getMockBuilder('\\Asd\\Http\\Uri')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder('\\Asd\\Http\\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->callbackStub = $this->getMockBuilder('\\Asd\\Router\\FunctionCallback')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     * @covers Asd\Router\Route::getPath
     * @covers Asd\Router\Route::parsePath
     */
    public function getPath()
    {
        $route = new Route('GET', '', $this->callbackStub);
        $this->assertEquals('', $route->getPath());
    }

    /**
     * @test
     * @covers Asd\Router\Route::getCallback
     */
    public function getCallback()
    {

        $route = new Route('GET', '', $this->callbackStub);
        $this->assertTrue($route->getCallback() instanceof \Asd\Router\FunctionCallback);
    }

    /**
     * @test
     * @covers Asd\Router\Route::__construct
     * @covers Asd\Router\Route::getPath
     * @covers Asd\Router\Route::parsePath
     */
    public function constructor_regularValue()
    {
        $route = new Route('GET', 'my-path', $this->callbackStub);
        $this->assertEquals('my-path', $route->getPath());
    }

    /**
     * @test
     * @covers Asd\Router\Route::__construct
     * @covers Asd\Router\Route::getPath
     */
    public function constructor_removesUnnecessarySlashesFromPath()
    {
        $route = new Route('GET', '//my-path', $this->callbackStub);
        $this->assertEquals('my-path', $route->getPath());
        
        $route = new Route('GET', 'my-path//', $this->callbackStub);
        $this->assertEquals('my-path', $route->getPath());

        $route = new Route('GET', '//my-path//', $this->callbackStub);
        $this->assertEquals('my-path', $route->getPath());
    }

    /**
     * @test
     * @covers Asd\Router\Route::getMethod
     */
    public function getMethod_returnsMethodInUpperCase()
    {
        $route = new Route('get', '', $this->callbackStub);
        $this->assertEquals('GET', $route->getMethod());
        
        $route = new Route('post', '', $this->callbackStub);
        $this->assertEquals('POST', $route->getMethod());
    }

    /**
     * @test
     * @covers Asd\Router\Route::__construct
     * @covers Asd\Router\Route::isValidMethod
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage "NOT_VALID_METHOD" is not a valid method.
     */
    public function constructor_withInvalidMethod_ThrowsException()
    {
        $r = new Route('NOT_VALID_METHOD', 'my-path', $this->callbackStub);
    }

    /**
     * @test
     * @covers Asd\Router\Route::equals
     */
    public function equals_withSameProperties_returnsTrue()
    {
        $route1 = new Route('GET', 'my-path', $this->callbackStub);
        $route2 = new Route('GET', 'my-path', $this->callbackStub);
        $this->assertTrue($route1->equals($route2));
    }

    /**
     * @test
     * @covers Asd\Router\Route::equals
     */
    public function equals_wherePropertiesDiffer_returnsFalse()
    {
        $route1 = new Route('GET', 'path', $this->callbackStub);
        $route2 = new Route('GET', 'other-path', $this->callbackStub);
        $route3 = new Route('POST', 'path', $this->callbackStub);
        $this->assertFalse($route1->equals($route2));
        $this->assertFalse($route1->equals($route3));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     */
    public function matchesRequest_withoutBase()
    {
        $route = new Route('GET', 'my-path', $this->callbackStub);
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);
        $this->uriStub->method('getPath')->willReturn('my-path');

        $this->assertTrue($route->matchesRequest($this->requestMock));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     */
    public function matchesRequest_withoutBase2()
    {
        $route = new Route('GET', 'my-path', $this->callbackStub);
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);
        $this->uriStub->method('getPath')->willReturn('/my-path');

        $this->assertTrue($route->matchesRequest($this->requestMock));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     */
    public function matchesRequest_withoutBase3()
    {
        $route = new Route('GET', 'my-path', $this->callbackStub);
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);
        $this->uriStub->method('getPath')->willReturn('/my-path/');

        $this->assertTrue($route->matchesRequest($this->requestMock));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     */
    public function matchesRequest_withoutBase4()
    {
        $route = new Route('GET', 'my-path', $this->callbackStub);
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);
        $this->uriStub->method('getPath')->willReturn('my-path/');

        $this->assertTrue($route->matchesRequest($this->requestMock));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     */
    public function matchesRequest_withBase()
    {
        $route = new Route('GET', '/path', $this->callbackStub);
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);
        $this->uriStub->method('getPath')->willReturn('the-base/path');

        $this->assertTrue($route->matchesRequest($this->requestMock, 'the-base'));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     * @covers Asd\Router\Route::parsePath
     */
    public function matchesRequest_withBase2()
    {
        $route = new Route('GET', '/my/long/path/', $this->callbackStub);
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);
        $this->uriStub->method('getPath')->willReturn('/some/base/my/long/path/');

        $this->assertTrue($route->matchesRequest($this->requestMock, '/some/base'));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     * @covers Asd\Router\Route::parsePath
     */
    public function matchesRequest_withBase3()
    {
        $route = new Route('GET', '/my/long/path/', $this->callbackStub);
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);
        $this->uriStub->method('getPath')->willReturn('/base/my/long/path');

        $this->assertTrue($route->matchesRequest($this->requestMock, '/base/'));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     */
    public function matchesRequest_withBase4()
    {
        $route = new Route('GET', '/my/long/path/', $this->callbackStub);
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);
        $this->uriStub->method('getPath')->willReturn('/with/base/my/long/path/');

        $this->assertTrue($route->matchesRequest($this->requestMock, 'with/base/'));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     */
    public function matchesRequest_incorrectMethod()
    {
        $route = new Route('GET', 'my-path', $this->callbackStub);
        $this->requestMock->method('getMethod')->willReturn('POST');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);
        $this->uriStub->method('getPath')->willReturn('/my-path');

        $this->assertFalse($route->matchesRequest($this->requestMock));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     */
    public function matchesRequest_nonMatchingRequest()
    {
        $route = new Route('GET', 'my-path', $this->callbackStub);
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);

        $this->uriStub->method('getPath')->willReturn('/other-path');
        $this->assertFalse($route->matchesRequest($this->requestMock));
    }

    /**
     * @test
     * @covers Asd\Router\Route::parsePath
     * @covers Asd\Router\Route::getParams
     * @covers Asd\Router\Route::matchesRequest
     */
    public function parsePath_handlesNamedParameter()
    {
        $route = new Route('GET', 'my-path/{name}', $this->callbackStub);
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);

        $this->uriStub->method('getPath')->willReturn('my-path/JohnDoe');
        $this->assertTrue($route->matchesRequest($this->requestMock));
        $this->assertEquals(['name' => 'JohnDoe'], $route->getParams());
    }

}