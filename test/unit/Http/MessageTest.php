<?php
namespace Test\Unit;

use Asd\Http\Message;

class MessageTest extends \PHPUnit_Framework_TestCase
{

    protected $message;
    
    public function setUp()
    {
        $this->message = $this->getMockForAbstractClass('Asd\Http\Message');
    }

    /**
     * @test
     */
    public function implements_PSR7()
    {
        $this->assertInstanceOf('Psr\Http\Message\MessageInterface', $this->message);
    }

    /**
     * @test
     * @covers Asd\Http\Message::getProtocolVersion
     */
    public function getProtocolVersion()
    {
        $this->assertEquals('1.1', $this->message->getProtocolVersion());
    }

    /**
     * @test
     * @covers Asd\Http\Message::withProtocolVersion
     */
    public function withProtocolVersion()
    {
        $message = $this->message->withProtocolVersion('1.0');
        $this->assertEquals('1.0', $message->getProtocolVersion());

        $message = $message->withProtocolVersion('1.1');
        $this->assertEquals('1.1', $message->getProtocolVersion());

        $message = $this->message->withProtocolVersion(2.0);
        $this->assertEquals('2.0', $message->getProtocolVersion());

        $message = $this->message->withProtocolVersion(2);
        $this->assertEquals('2.0', $message->getProtocolVersion());
    }

    /**
     * @test
     * @covers Asd\Http\Message::withProtocolVersion
     */
    public function withProtocolVersion_isImmutable()
    {
        $message = $this->message->withProtocolVersion('1.0');
        $message2 = $message->withProtocolVersion('1.0');

        $this->assertNotSame($message, $message2);
    }

    /**
     * @test
     * @covers Asd\Http\Message::withProtocolVersion
     * @expectedException InvalidArgumentException
     */
    public function withProtocolVersion_withInvalidString()
    {
        $message = $this->message->withProtocolVersion('2.5');
    }

    /**
     * @test
     * @covers Asd\Http\Message::withProtocolVersion
     * @expectedException InvalidArgumentException
     */
    public function withProtocolVersion_withInvalidFloat()
    {
        $message = $this->message->withProtocolVersion(2.2);
    }

    /**
     * @test
     * @covers Asd\Http\Message::withProtocolVersion
     * @expectedException InvalidArgumentException
     */
    public function withProtocolVersion_withInvalidInt()
    {
        $message = $this->message->withProtocolVersion(3);
    }

    /**
     * @test
     * @covers Asd\Http\Message::getHeaders
     */
    public function getHeaders()
    {
        $this->assertEquals([], $this->message->getHeaders());
    }

    /**
     * @test
     * @covers Asd\Http\Message::withHeader
     * @covers Asd\Http\Message::getHeaders
     */
    public function withHeader()
    {
        $message = $this->message->withHeader('name', 'value');
        $expected = ['name' => ['value']];
        $this->assertEquals($expected, $message->getHeaders());

        $message = $message->withHeader('name', ['value1', 'value2']);
        $expected = ['name' => ['value1', 'value2']];
        $this->assertEquals($expected, $message->getHeaders());

        $message = $message->withHeader('x', ['y', 'z']);
        $expected = [
            'name' => ['value1', 'value2'],
            'x' => ['y', 'z'],
        ];
        $this->assertEquals($expected, $message->getHeaders());
    }

    /**
     * @test
     * @covers Asd\Http\Message::withHeader
     * @expectedException InvalidArgumentException
     */
    public function withHeader_invalidHeader()
    {
        $message = $this->message->withHeader(123, 'value');
    }

    /**
     * @test
     * @covers Asd\Http\Message::withHeader
     * @expectedException InvalidArgumentException
     */
    public function withHeader_intHeaderValue()
    {
        $message = $this->message->withHeader('x', 123);
    }

    /**
     * @test
     * @covers Asd\Http\Message::withHeader
     * @expectedException InvalidArgumentException
     */
    public function withHeader_intArrayHeaderValue()
    {
        $message = $this->message->withHeader('x', [123, 1, 234]);
    }

    /**
     * @test
     * @covers Asd\Http\Message::getHeader
     * @covers Asd\Http\Message::getHeaders
     */
    public function getHeader()
    {
        $message = $this->message->withHeader('x', 'y');
        $this->assertEquals(['y'], $message->getHeader('x'));

        $message = $message->withHeader('a', ['b', 'c']);
        $this->assertEquals(['b', 'c'], $message->getHeader('a'));

        $this->assertEquals([], $message->getHeader('invalidKey'));
        $this->assertEquals([], $message->getHeader(''));
    }

    /**
     * @test
     * @covers Asd\Http\Message::hasHeader
     */
    public function hasHeader()
    {
        $message = $this->message->withHeader('x', 'y');

        $this->assertTrue($message->hasHeader('x'));
        $this->assertFalse($message->hasHeader('invalidKey'));
    }

    /**
     * @test
     * @covers Asd\Http\Message::hasHeader
     */
    public function hasHeader_withInvalidArgument()
    {
        $this->assertFalse($this->message->hasHeader(123));
    }

    /**
     * @test
     * @covers Asd\Http\Message::getHeaderLine
     */
    public function getHeaderLine()
    {
        $message = $this->message->withHeader('x', 'y');
        $this->assertEquals('y', $message->getHeaderLine('x'));

        $message = $message->withHeader('a', ['b', 'c', 'def']);
        $this->assertEquals('b, c, def', $message->getHeaderLine('a'));

        $this->assertEquals('', $message->getHeaderLine('invalidKey'));
    }

    /**
     * @test
     * @covers Asd\Http\Message::withAddedHeader
     * @covers Asd\Http\Message::getHeaders
     */
    public function withAddedHeader()
    {
        $message = $this->message->withAddedHeader('x', 'y');
        $message = $message->withAddedHeader('x', 'z');

        $this->assertEquals(['x' => ['y', 'z']], $message->getHeaders());
        $this->assertEquals(['y', 'z'], $message->getHeader('x'));
        $this->assertEquals('y, z', $message->getHeaderLine('x'));

        $message = $message->withAddedHeader('x', ['a', 'b']);

        $this->assertEquals(['x' => ['y', 'z', 'a', 'b']], $message->getHeaders());
        $this->assertEquals(['y', 'z', 'a', 'b'], $message->getHeader('x'));
        $this->assertEquals('y, z, a, b', $message->getHeaderLine('x'));
    }

    /**
     * @test
     * @covers Asd\Http\Message::withAddedHeader
     * @expectedException InvalidArgumentException
     */
    public function withAddedHeader_invalidHeader()
    {
        $message = $this->message->withHeader('headername', 'y');
        $message->withAddedHeader([123], 'value');
    }

    /**
     * @test
     * @covers Asd\Http\Message::withAddedHeader
     * @expectedException InvalidArgumentException
     */
    public function withAddedHeader_invalidValue()
    {
        $message = $this->message->withHeader('headername', 'y');
        $message->withAddedHeader('headername', 123);
    }

    /**
     * @test
     * @covers Asd\Http\Message::withoutHeader
     * @covers Asd\Http\Message::getHeaders
     */
    public function withoutHeader()
    {
        $message = $this->message->withHeader('x', 'y');
        $message = $message->withHeader('a', ['b', 'c']);
        $expected = [
            'x' => ['y'],
            'a' => ['b', 'c']
        ];
        $this->assertEquals($expected, $message->getHeaders());

        $message = $message->withoutHeader('a');
        $expected = ['x' => ['y']];
        $this->assertEquals($expected, $message->getHeaders());

        $message2 = $message->withoutHeader('a');
        $this->assertEquals($expected, $message2->getHeaders());
        $this->assertEquals($message->getHeaders(), $message2->getHeaders());
    }

    /**
     * @test
     * @covers Asd\Http\Message::withBody
     * @covers Asd\Http\Message::getBody
     */
    public function withBody()
    {
        $requestBodyStub = $this->getMockBuilder('\\Asd\\Http\\RequestBody')
            ->disableOriginalConstructor()
            ->getMock();

        $message = $this->message->withBody($requestBodyStub);

        $this->assertEquals($requestBodyStub, $message->getBody());
    }

}