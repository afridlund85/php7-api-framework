<?php
namespace Test\Unit;

use Asd\Http\Stream;
use Test\Unit\StreamStub;

class StreamTest extends \PHPUnit_Framework_TestCase
{

    protected $stream;
    protected $streamStub;
    
    public function setUp()
    {
        stream_wrapper_register('streamTester', '\Test\Unit\StreamStub');
        $this->streamStub = fopen('streamTester://whatever', 'r+');
        $this->stream = new Stream($this->streamStub);
    }

    public function tearDown()
    {
        if(in_array("streamTester", stream_get_wrappers())){
            if(is_resource($this->streamStub))
                fclose($this->streamStub);
            stream_wrapper_unregister('streamTester');
        }
    }

    /**
     * @test
     */
    public function implements_PSR7()
    {
        $this->assertInstanceOf('Psr\Http\Message\StreamInterface', $this->stream);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function requires_streamResource()
    {
        new Stream('asd');
    }

    /**
     * @test
     * @covers Asd\Http\Stream::detach
     */
    public function detach()
    {
        $this->assertSame($this->streamStub, $this->stream->detach());
    }

    /**
     * @test
     * @covers Asd\http\Stream::close
     */
    public function close()
    {
        $this->stream->close();
        $this->assertFalse(is_resource($this->stream->detach()));
    }

    /**
     * @test
     * @covers Asd\Http\Stream::getSize
     * @covers Asd\Http\Stream::getMetadata
     */
    public function getSize_()//can be named only getSize
    {
        $this->assertEquals(0, $this->stream->getSize());
    }

    /**
     * @test
     * @covers Asd\Http\Stream::getSize
     */
    public function getSize_afterDetach()
    {
        $this->stream->detach();
        $this->assertEquals(null, $this->stream->getSize());
    }

    /**
     * @test
     * @covers Asd\Http\Stream::isWritable
     * @covers Asd\Http\Stream::getMetadata
     */
    public function isWritable()
    {
        $this->assertTrue($this->stream->isWritable());
        $this->stream->detach();
        $this->assertFalse($this->stream->isWritable());
    }

    /**
     * @test
     * @covers Asd\Http\Stream::isReadable
     * @covers Asd\Http\Stream::getMetadata
     */
    public function isReadable()
    {
        $this->assertTrue($this->stream->isReadable());
        $this->stream->detach();
        $this->assertFalse($this->stream->isReadable());
    }

    /**
     * @test
     * @covers Asd\Http\Stream::isSeekable
     */
    public function isSeekable()
    {
        $this->assertTrue($this->stream->isSeekable());
        $this->stream->detach();
        $this->assertFalse($this->stream->isSeekable());
    }

    /**
     * @test
     * @covers Asd\Http\Stream::tell
     */
    public function tell()
    {
        $this->assertEquals(0, $this->stream->tell());
    }

    /**
     * @test
     * @covers Asd\Http\Stream::seek
     * @expectedException RuntimeException
     */
    public function seek_outOfBounds()
    {
        $this->assertTrue($this->stream->isSeekable());
        $this->stream->seek(3);
    }

    /**
     * @test
     * @covers Asd\Http\Stream::seek
     * @expectedException RuntimeException
     */
    public function seek_afterDetach()
    {
        $this->assertTrue($this->stream->isSeekable());
        $this->stream->detach();
        $this->stream->seek(3);
    }

    /**
     * @test
     * @covers Asd\Http\Stream::eof
     */
    public function eof()
    {
        $this->assertTrue($this->stream->eof());
    }

    /**
     * @test
     * @covers Asd\Http\Stream::tell
     * @expectedException RuntimeException
     */
    public function tell_afterDetach()
    {
        $this->stream->detach();
        $this->stream->tell();
    }

    /**
     * @test
     * @covers Asd\Http\Stream::write
     * @covers Asd\Http\Stream::getSize
     * @covers Asd\Http\Stream::tell
     * @covers Asd\Http\Stream::eof
     * @covers Asd\Http\Stream::seek
     */
    public function write()
    {
        $written = $this->stream->write('hello');
        $this->assertEquals(5, $written);
        $this->assertEquals(5,  $this->stream->getSize());
        $this->assertEquals(5,  $this->stream->tell());
        $this->assertTrue($this->stream->eof());
    }

    /**
     * @test
     * @covers Asd\Http\Stream::write
     * @expectedException RuntimeException
     */
    public function write_afterDetach()
    {
        $this->stream->detach();
        $this->stream->write('will not be written');
    }

    /**
     * @test
     * @covers Asd\Http\Stream::read
     * @covers Asd\Http\Stream::rewind
     */
    public function read()
    {
        $this->stream->write('hello');
        $read = $this->stream->read(3);
        $this->assertEquals('', $read);

        $this->stream->rewind();
        $read = $this->stream->read(3);
        $this->assertEquals('hel', $read);
    }

    /**
     * @test
     * @covers Asd\Http\Stream::read
     * @expectedException RuntimeException
     */
    public function read_afterDetach()
    {
        $this->stream->detach();
        $this->stream->read(4);
    }

    /**
     * @test
     * @covers Asd\Http\Stream::getMetadata
     */
    public function getMetadata()
    {
        $meta = $this->stream->getMetadata();
        $this->assertEquals('r+', $meta['mode']);
        $this->assertTrue(true, $meta['eof']);
    }

    /**
     * @test
     * @covers Asd\Http\Stream::getMetadata
     */
    public function getMetadata_withKey()
    {
        $this->assertEquals('r+', $this->stream->getMetadata('mode'));
    }

    /**
     * @test
     * @covers Asd\Http\Stream::getMetadata
     */
    public function getMetadata_withInvalidKey()
    {
        $this->assertEquals(null, $this->stream->getMetadata('invalid-key'));
    }

    /**
     * @test
     * @covers Asd\Http\Stream::write
     * @covers Asd\Http\Stream::seek
     * @covers Asd\Http\Stream::rewind
     * @covers Asd\Http\Stream::getContents
     */
    public function getContents()
    {
        $this->stream->write('hello');
        $this->assertEquals('', $this->stream->getContents());

        $this->stream->rewind();
        $this->assertEquals('hello', $this->stream->getContents());

        $this->stream->seek(2);
        $this->assertEquals('llo', $this->stream->getContents());
    }

    /**
     * @test
     * @covers Asd\Http\Stream::write
     * @covers Asd\Http\Stream::detach
     * @covers Asd\Http\Stream::getContents
     * @expectedException RuntimeException
     */
    public function getContents_afterDetach()
    {
        $this->stream->write('hello');
        $this->stream->detach();
        $this->stream->getContents();
    }

    /**
     * @test
     * @covers Asd\Http\Stream::write
     * @covers Asd\Http\Stream::seek
     * @covers Asd\Http\Stream::rewind
     * @covers Asd\Http\Stream::__toString
     */
    public function toString()
    {
        $this->assertEquals('', (string)$this->stream);

        $this->stream->write('hello');
        $this->assertEquals('hello', (string)$this->stream);

        $this->stream->rewind();
        $this->assertEquals('hello', (string)$this->stream);

        $this->stream->seek(2);
        $this->assertEquals('hello', (string)$this->stream);
    }

    /**
     * @test
     * @covers Asd\Http\Stream::write
     * @covers Asd\Http\Stream::detach
     * @covers Asd\Http\Stream::__toString
     */
    public function toString_afterDetach()
    {
        $this->stream->write('hello');

        $this->assertEquals('hello', (string)$this->stream);

        $this->stream->detach();
        $this->assertEquals('', (string)$this->stream);
    }

}
