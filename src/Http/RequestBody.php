<?php
declare (strict_types = 1);

namespace Asd\Http;

use InvalidArgumentException;

class RequestBody extends Stream
{

    public function __construct($resource = null)
    {
        if (!is_resource($resource) && $resource !== null) {
            throw new InvalidArgumentException('resource parameter must be a valid resource or null');
        }
        if ($resource === null) {
            $resource = fopen('php://temp', 'w+');
            stream_copy_to_stream(fopen('php://input', 'r'), $resource);
            rewind($resource);
        }
        parent::__construct($resource);
    }
}
