<?php

namespace Asd;

interface iResponse
{
    public function getBody() : string;
    public function setBody(string $body);
    public function getStatusCode() : int;
    public function setStatusCode(int $statusCode);
    public function getHeaders() : Array;
    public function addHeader(string $key, string $value);
    public function removeHeader(string $key);
    public function getProtocol() : string;
    public function setProtocol(string $protocol);
    public function getContentType() : string;
}