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
        if(isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])){
            $this->url = $_SERVER['PATH_INFO'];
        }
        else{
            $this->url = $_SERVER['REQUEST_URI'];
        }
    }
    
    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }
}