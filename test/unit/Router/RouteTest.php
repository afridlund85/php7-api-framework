<?php
namespace Test\Unit;

use Throwable;
use InvalidArgumentException;
use Asd\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    protected $uriStub;
    protected $requestMock;
    
    public function setUp()
    {
        $this->uriStub = $this->getMockBuilder('\\Asd\\Http\\Uri')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder('\\Asd\\Http\\Request')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     * @covers Asd\Router\Route::getPath
     */
    public function getPath()
    {
        $route = new Route('GET', '', function(){});
        $this->assertEquals('/', $route->getPath());
    }

    /**
     * @test
     * @covers Asd\Router\Route::getCallback
     */
    public function getCallback()
    {

        $route = new Route('GET', '', function(){});
        $this->assertTrue($route->getCallback() instanceof \Closure);
        $this->assertTrue(is_callable($route->getCallback()));
    }

    /**
     * @test
     * @covers Asd\Router\Route::__construct
     * @covers Asd\Router\Route::getPath
     */
    public function constructor_addsInitialSlashToPath()
    {
        $route = new Route('GET', 'my-path', function(){});
        $this->assertEquals('/my-path', $route->getPath());
    }

    /**
     * @test
     * @covers Asd\Router\Route::__construct
     * @covers Asd\Router\Route::getPath
     */
    public function constructor_removesUnnecessarySlashesFromPath()
    {
        $route = new Route('GET', '//my-path', function(){});
        $this->assertEquals('/my-path', $route->getPath());
        
        $route = new Route('GET', 'my-path//', function(){});
        $this->assertEquals('/my-path', $route->getPath());

        $route = new Route('GET', '//my-path//', function(){});
        $this->assertEquals('/my-path', $route->getPath());
    }

    /**
     * @test
     * @covers Asd\Router\Route::getMethod
     */
    public function getMethod_returnsMethodInUpperCase()
    {
        $route = new Route('get', '', function(){});
        $this->assertEquals('GET', $route->getMethod());
        
        $route = new Route('post', '', function(){});
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
        $r = new Route('NOT_VALID_METHOD', 'my-path', function(){});
    }

    /**
     * @test
     * @covers Asd\Router\Route::equals
     */
    public function equals_withSameProperties_returnsTrue()
    {
        $route1 = new Route('GET', 'my-path', function(){});
        $route2 = new Route('GET', 'my-path', function(){});
        $this->assertTrue($route1->equals($route2));
    }

    /**
     * @test
     * @covers Asd\Router\Route::equals
     */
    public function equals_wherePropertiesDiffer_returnsFalse()
    {
        $route1 = new Route('GET', 'path', function(){});
        $route2 = new Route('GET', 'other-path', function(){});
        $route3 = new Route('POST', 'path', function(){});
        $this->assertFalse($route1->equals($route2));
        $this->assertFalse($route1->equals($route3));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     */
    public function matchesRequest_withoutBase()
    {
        $route = new Route('GET', 'my-path', function(){});
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
        $route = new Route('GET', 'my-path', function(){});
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
        $route = new Route('GET', 'my-path', function(){});
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
        $route = new Route('GET', 'my-path', function(){});
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
        $route = new Route('GET', '/path', function(){});
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);
        $this->uriStub->method('getPath')->willReturn('the-base/path');

        $this->assertTrue($route->matchesRequest($this->requestMock, 'the-base'));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     */
    public function matchesRequest_withBase2()
    {
        $route = new Route('GET', '/my/long/path/', function(){});
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);
        $this->uriStub->method('getPath')->willReturn('/some/base/my/long/path/');

        $this->assertTrue($route->matchesRequest($this->requestMock, '/some/base'));
    }

    /**
     * @test
     * @covers Asd\Router\Route::matchesRequest
     */
    public function matchesRequest_withBase3()
    {
        $route = new Route('GET', '/my/long/path/', function(){});
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
        $route = new Route('GET', '/my/long/path/', function(){});
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
        $route = new Route('GET', 'my-path', function(){});
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
        $route = new Route('GET', 'my-path', function(){});
        $this->requestMock->method('getMethod')->willReturn('GET');
        $this->requestMock->method('getUri')->willReturn($this->uriStub);

        $this->uriStub->method('getPath')->willReturn('/other-path');
        $this->assertFalse($route->matchesRequest($this->requestMock));
    }


}