<?php
declare(strict_types = 1);

namespace Asd\Http;

use InvalidArgumentException;
use RuntimeException;
use Psr\Http\Message\StreamInterface;

/**
 * Describes a data stream.
 *
 * Typically, an instance will wrap a PHP stream; this interface provides
 * a wrapper around the most common operations, including serialization of
 * the entire stream to a string.
 */
class Stream implements StreamInterface
{

  protected $resource;

  /**
   * @link http://php.net/manual/en/language.types.resource.php
   * @param php_resource $resource 
   */
  public function __construct($resource)
  {
    if(!is_resource($resource))
      throw new InvalidArgumentException('provided resource must be a valid php resource.');
    $this->resource = $resource;
  }

  /**
   * Reads all data from the stream into a string, from the beginning to end.
   *
   * This method MUST attempt to seek to the beginning of the stream before
   * reading data and read the stream until the end is reached.
   *
   * Warning: This could attempt to load a large amount of data into memory.
   *
   * This method MUST NOT raise an exception in order to conform with PHP's
   * string casting operations.
   *
   * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
   * @return string
   */
  public function __toString() : string
  {
    if(!is_resource($this->resource))
      return '';
    try{
      $this->rewind();
      return $this->getContents();
    }
    catch(RuntimeException $e){
      return '';
    }
  }

  /**
   * Closes the stream and any underlying resources.
   *
   * @return void
   */
  public function close()
  {
    fclose($this->resource);
  }

  /**
   * Separates any underlying resources from the stream.
   *
   * After the stream has been detached, the stream is in an unusable state.
   *
   * @return resource|null Underlying PHP stream, if any
   */
  public function detach()
  {
    $resource = $this->resource;
    $this->resource = null;
    return $resource;
  }

  /**
   * Get the size of the stream if known.
   *
   * @return int|null Returns the size in bytes if known, or null if unknown.
   */
  public function getSize()
  {
    if(!is_resource($this->resource))
      return null;

    $stats = fstat($this->resource);
    return isset($stats['size']) ? $stats['size'] : null;
  }

  /**
   * Returns the current position of the file read/write pointer
   *
   * @return int Position of the file pointer
   * @throws \RuntimeException on error.
   */
  public function tell() : int
  {
    if(is_resource($this->resource)){
      $pos = ftell($this->resource);
      if($pos !== false)
        return $pos;
    }
    throw new RuntimeException('Could not get pointer position.');
  }

  /**
   * Returns true if the stream is at the end of the stream.
   *
   * @return bool
   */
  public function eof() : bool
  {
    return feof($this->resource);
  }

  /**
   * Returns whether or not the stream is seekable.
   *
   * @return bool
   */
  public function isSeekable() : bool
  {
    if(!is_resource($this->resource))
      return false;
    return $this->getMetadata('seekable') ?? false;
  }

  /**
   * Seek to a position in the stream.
   *
   * @link http://www.php.net/manual/en/function.fseek.php
   * @param int $offset Stream offset
   * @param int $whence Specifies how the cursor position will be calculated
   *     based on the seek offset. Valid values are identical to the built-in
   *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
   *     offset bytes SEEK_CUR: Set position to current location plus offset
   *     SEEK_END: Set position to end-of-stream plus offset.
   * @throws \RuntimeException on failure.
   */
  public function seek($offset, $whence = SEEK_SET)
  {
    if(!$this->isSeekable() || fseek($this->resource, $offset, $whence) === -1)
      throw new RuntimeException('Could not perform seek');
  }

  /**
   * Seek to the beginning of the stream.
   *
   * If the stream is not seekable, this method will raise an exception;
   * otherwise, it will perform a seek(0).
   *
   * @see seek()
   * @link http://www.php.net/manual/en/function.fseek.php
   * @throws \RuntimeException on failure.
   */
  public function rewind()
  {
    $this->seek(0);
  }

  /**
   * Returns whether or not the stream is writable.
   *
   * @return bool
   */
  public function isWritable() : bool
  {
    if(!is_resource($this->resource))
      return false;

    $modes = ['r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+'];
    return in_array($this->getMetadata('mode'), $modes);
  }

  /**
   * Write data to the stream.
   *
   * @param string $string The string that is to be written.
   * @return int Returns the number of bytes written to the stream.
   * @throws \RuntimeException on failure.
   */
  public function write($string) : int
  {
    if($this->isWritable()){
      $written = fwrite($this->resource, $string);
      if($written !== false)
        return $written;
    }
    throw new RuntimeException('Could not write');
  }

  /**
   * Returns whether or not the stream is readable.
   *
   * @return bool
   */
  public function isReadable() : bool
  {
    if(!is_resource($this->resource))
      return false;
    
    $modes = ['r', 'r+', 'w+', 'a+', 'x+', 'c+'];
    return in_array($this->getMetadata('mode'), $modes);
  }

  /**
   * Read data from the stream.
   *
   * @param int $length Read up to $length bytes from the object and return
   *     them. Fewer than $length bytes may be returned if underlying stream
   *     call returns fewer bytes.
   * @return string Returns the data read from the stream, or an empty string
   *     if no bytes are available.
   * @throws \RuntimeException if an error occurs.
   */
  public function read($length) : string
  {
    if($this->isReadable()){
      $data = fread($this->resource, $length);
      if($data !== false)
        return $data;
    }
    throw new RuntimeException('Could not read');
  }

  /**
   * Returns the remaining contents in a string
   *
   * @return string
   * @throws \RuntimeException if unable to read or an error occurs while
   *     reading.
   */
  public function getContents() : string
  {
    if($this->isReadable()){
      $data = stream_get_contents($this->resource);
      if($data !== false)
        return $data;
    }
    throw new RuntimeException('Could not read');
  }

  /**
   * Get stream metadata as an associative array or retrieve a specific key.
   *
   * The keys returned are identical to the keys returned from PHP's
   * stream_get_meta_data() function.
   *
   * @link http://php.net/manual/en/function.stream-get-meta-data.php
   * @param string $key Specific metadata to retrieve.
   * @return array|mixed|null Returns an associative array if no key is
   *     provided. Returns a specific key value if a key is provided and the
   *     value is found, or null if the key is not found.
   */
  public function getMetadata($key = null)
  {
    $metaData = stream_get_meta_data($this->resource);
    if($key === null)
      return $metaData;

    return isset($metaData[$key]) ? $metaData[$key] : null;
  }
}