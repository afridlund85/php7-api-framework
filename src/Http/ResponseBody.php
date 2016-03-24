<?php
declare(strict_types = 1);

namespace Asd\Http;

use InvalidArgumentException;

class ResponseBody extends Stream
{

  public function __construct($resource = null)
  {
    if($resource === null || !is_resource($resource)){
      $resource = fopen('php://temp', 'w+');
    }
    parent::__construct($resource);
  }
}