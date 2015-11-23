<?php
namespace Asd;

interface iRequest
{
    public function getUri() : string;
    public function getQueries() : array;
    public function getQuery() : string;
}