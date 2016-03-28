<?php
namespace Test\Unit;


use Asd\Http\Stream;
use Psr\Http\Message\StreamInterface;

class StreamStub
{
  public $context;
  public $position = 0;
  public $data = '';

  public function stream_open($path, $mode, $options, &$opened_path) {
    return true;
  }

  public function stream_read($bytes) {
    $chunk = substr($this->data, $this->position, $bytes);
    $this->position += strlen($chunk);
    return $chunk;
  }

  public function stream_write($data) {
    $l = strlen($data);
    $this->data = 
      substr($this->data, 0, $this->position) . 
      $data . 
      substr($this->data, $this->position += $l);
    return $l;
    // $this->data .= $data;
    // $this->position += strlen($data);
    // return strlen($data);
  }

  public function stream_eof() {
    return $this->position >= strlen($this->data);
  }

  public function stream_tell() {
    return $this->position;
  }

  public function stream_close() {
    return null;
  }

  public function stream_seek($offset, $whence) {
    if($this->position + $offset >= strlen($this->data))
      return false;

    $this->position += $offset;
    return true;
  }

  public function stream_stat()
  {
    return ['size' => strlen($this->data)];
  }

  public function stream_metadata(string $path ,int $option, mixed $value){
    return true;
  }
}

class StreamTest extends \PHPUnit_Framework_TestCase
{

  protected $stream;
  protected $streamStub;
  
  public function setUp()
  {
    stream_wrapper_register('streamTester', '\Test\Unit\StreamStub');
    $this->streamStub = fopen('streamTester://whatever', 'w+');
    $this->stream = new Stream($this->streamStub);
  }

  public function tearDown()
  {
    fclose($this->streamStub);
    stream_wrapper_unregister('streamTester');
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
   */
  public function write()
  {
    $this->assertTrue(is_resource($this->streamStub));
    $this->assertTrue($this->stream->isWritable());
    $this->assertTrue($this->stream->isReadable());
    $this->assertTrue($this->stream->isSeekable());

    $written = $this->stream->write('hello');
    $this->assertEquals(5, $written);

    $this->assertEquals('hello', $this->stream->getContents());
  }

}
