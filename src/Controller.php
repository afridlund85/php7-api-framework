<?php
declare (strict_types = 1);

namespace Asd;

// use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Controller
{

    // protected $request;
    // protected $response;

    // public function setRequest(RequestInterface $request)
    // {
    //     $this->request = $request;
    // }

    // public function setResponse(ResponseInterface $response)
    // {
    //     $this->response = $response;
    // }

    // public function getResponse()
    // {
    //     return $this->response;
    // }

    public function json(ResponseInterface $reponse, $data)
    {
        $responseBody = $reponse->getBody();
        $responseBody->rewind();
        $responseBody->write(json_encode($data));
        $jsonResponse = $reponse->withHeader('Content-Type', 'application/json;charset=utf-8');
        return $jsonResponse->withBody($responseBody);
    }
}
