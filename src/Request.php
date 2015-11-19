<?php
declare(strict_types = 1);

namespace Asd;

class Request implements iRequest{
    
    /**
     * @var string
     */
    private $url;
    
    /**
     * 
     */
    public function __construct()
    {
        $this->url = $_SERVER['PATH_INFO'];
    }
    
    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }
}