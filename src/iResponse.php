<?php

namespace Asd;

interface iResponse
{
    public function getBody() : string;
    public function setBody(string $body);
    public function getStatusCode() : int;
    public function setStatusCode(int $statusCode);
    public function getHeaders() : Array;
}