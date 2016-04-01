<?php
declare(strict_types = 1);

namespace Asd;

use Asd\Http\{Request, Response};

class Controller
{

  protected $request;
  protected $response;

  public function setRequest(Request $request)
  {
    $this->request = $request;
  }

  public function setResponse(Response $response)
  {
    $this->response = $response;
  }

  public function getResponse()
  {
    return $this->response;
  }

  public function json($data)
  {
    $responseBody = $this->response->getBody();
    $responseBody->rewind();
    $responseBody->write(json_encode($data));
    $jsonResponse = $this->response->withHeader('Content-Type', 'application/json;charset=utf-8');
    $jsonResponse = $jsonResponse->withBody($responseBody);
  }
}