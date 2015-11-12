<?php

namespace Asd;

interface iResponse
{
    public function toString() : string;
    public function setBody(string $body);
    public function getStatusCode() : int;
    public function setStatusCode(int $statusCode);
}