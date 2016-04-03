<?php
declare (strict_types = 1);

namespace Asd\Http;

use InvalidArgumentException;

class ResponseBody extends Stream
{

    public function __construct($resource = null)
    {
        if (!is_resource($resource) && $resource !== null) {
            throw new InvalidArgumentException('resource parameter must be a valid resource or null');
        }
        if ($resource === null) {
            $resource = fopen('php://temp', 'r+');
        }
        parent::__construct($resource);
    }
}
