<?php
declare(strict_types = 1);

namespace Asd;

use Psr\Http\Message\ResponseInterface;

/**
 * Optinal Controller base class for applications. Will provide some convenient
 * methods but not necessary to use.
 */
abstract class Controller
{
    /**
     * Takes mixed inputs and writes it json encoded to the body of a response
     * object and returns a clone.
     *
     * Warning: This method overwrites any previous data in the response body
     *
     * @param  Psr\Http\Message\ResponseInterface $reponse
     * @param  mixed $data
     * @return Psr\Http\Message\ResponseInterface
     */
    public function withJsonResponse(ResponseInterface $reponse, $data) : ResponseInterface
    {
        $responseBody = $reponse->getBody();
        $responseBody->rewind();
        $responseBody->write(json_encode($data));
        $jsonResponse = $reponse->withHeader('Content-Type', 'application/json;charset=utf-8');
        return $jsonResponse->withBody($responseBody);
    }

    /**
     * Takes string inputs and writes it to the body of a response object and
     * returns a clone.
     *
     * Warning: This method overwrites any previous data in the response body
     *
     * @param  Psr\Http\Message\ResponseInterface $reponse
     * @param  string $data
     * @return Psr\Http\Message\ResponseInterface
     */
    public function withTextResponse(ResponseInterface $reponse, string $data) : ResponseInterface
    {
        $responseBody = $reponse->getBody();
        $responseBody->rewind();
        $responseBody->write($data);
        $textResponse = $reponse->withHeader('Content-Type', 'text/html;charset=utf-8');
        return $textResponse->withBody($responseBody);
    }
}
