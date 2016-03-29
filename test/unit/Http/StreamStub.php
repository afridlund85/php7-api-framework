<?php
namespace Test\Unit;

class StreamStub
{
  public $context;
  public $position = 0;
  public $data = '';

  public function stream_open($path, $mode, $options, &$opened_path)
  {
    return true;
  }

  public function stream_read($bytes)
  {
    $chunk = substr($this->data, $this->position, $bytes);
    $this->position += strlen($chunk);
    return $chunk;
  }

  public function stream_write($data)
  {
    $this->data .= $data;
    $this->position += strlen($data);
    return strlen($data);
  }

  public function stream_eof()
  {
    return $this->position >= strlen($this->data);
  }

  public function stream_tell()
  {
    return $this->position;
  }

  public function stream_close()
  {
    return null;
  }

  public function stream_seek($offset, $whence)
  {
    if($whence === SEEK_SET)
      $position = $offset;
    elseif($whence === SEEK_CUR)
      $position = $this->position + $offset;
    elseif($whence === SEEK_END)
      $position = strlen($this->data) + $offset;
    else
      return false;

    if($position >= 0 && $position <= strlen($this->data)){
      $this->position = $position;
      return true;
    }
    return false;
  }

  public function stream_stat(){
    return ['size' => strlen($this->data)];
  }

  public function stream_metadata(string $path ,int $option, mixed $value){
    return true;
  }

}