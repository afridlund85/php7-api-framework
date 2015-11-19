<?php
declare(strict_types = 1);

namespace Asd;

class Request implements iRequest{
    
    private $url;
    
    public function __construct()
    {
        $this->url = $_SERVER['PATH_INFO'];
    }
    
    public function getUrl()
    {
        return $this->url;
    }
}