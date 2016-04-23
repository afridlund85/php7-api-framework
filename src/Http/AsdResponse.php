<?php
declare (strict_types = 1);

namespace Asd\Http;

use Psr\Http\Message\ResponseInterface;
use Asd\Http\Response;

class AsdResponse extends Response implements ResponseInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Takes mixed inputs and writes it json encoded to the body of a response
     * object and returns a clone.
     *
     * Warning: This method overwrites any previous data in the response body
     *
     * @param  array $data
     * @return Psr\Http\Message\ResponseInterface
     */
    public function withJson(array $data) : ResponseInterface
    {
        $jsonBody = $this->getBody();
        $jsonBody->rewind();
        $jsonBody->write(json_encode($data));
        $jsonResponse = $this->withHeader('Content-Type', 'application/json;charset=utf-8');
        return $jsonResponse->withBody($jsonBody);
    }

    /**
     * Takes string inputs and writes it to the body of a response object and
     * returns a clone.
     *
     * Warning: This method overwrites any previous data in the response body
     *
     * @param  string $data
     * @return Psr\Http\Message\ResponseInterface
     */
    public function withText(string $data) : ResponseInterface
    {
        $textBody = $this->getBody();
        $textBody->rewind();
        $textBody->write($data);
        $textResponse = $this->withHeader('Content-Type', 'text/html;charset=utf-8');
        return $textResponse->withBody($textBody);
    }
}
