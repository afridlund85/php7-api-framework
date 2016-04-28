<?php
declare (strict_types = 1);

namespace Asd\Http;

use InvalidArgumentException;

class RequestBody extends Stream
{
    /**
     * Default behaviour for Request Body Streams.
     * Opens a new stream for the response but also copies the content of
     * php://input to fill the stream with potential content from client.
     * @param [type] $resource [description]
     */
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
