<?php
declare(strict_types = 1);

namespace Asd;

class Request implements iRequest{
    
    /**
     * @var string
     */
    private $uri;
    
    /**
     * 
     */
    public function __construct()
    {
        if(isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])){
            $this->uri = $_SERVER['PATH_INFO'];
        }
        else{
            $this->uri = $_SERVER['REQUEST_URI'];
        }
    }
    
    /**
     * @return string
     */
    public function getUri() : string
    {
        return $this->uri;
    }
}