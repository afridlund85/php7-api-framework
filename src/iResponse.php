<?php

namespace Asd;

interface iResponse
{
    public function toString() : string;
    public function setBody(string $body);
}