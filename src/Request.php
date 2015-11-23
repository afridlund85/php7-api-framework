<?php
declare(strict_types = 1);

namespace Asd;

class Request implements iRequest{
    
    /**
     * @var string
     */
    private $uri;
    
    /**
     * @var array
     */
    private $queries;
    
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
        $this->queries = $_GET;
    }
    
    /**
     * @return string
     */
    public function getUri() : string
    {
        return $this->uri;
    }
    
    /**
     * @return array
     */
    public function getQueries() : array
    {
        return $this->queries;
    }
}