<?php
declare(strict_types = 1);

namespace Asd\Http;

use InvalidArgumentException;

class RequestBody extends Stream
{

  public function __construct($resource = null)
  {
    if($resource === null || !is_resource($resource)){
      $resource = fopen('php://temp', 'w+');
      stream_copy_to_stream(fopen('php://input', 'r'), $resource);
      rewind($resource);
    }
    parent::__construct($resource);
  }
}