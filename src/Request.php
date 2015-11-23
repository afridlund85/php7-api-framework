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
    private $queries = [];
    
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
        if(isset($this->uri) && strpos($this->uri, '?') !== false){
            $queryString = substr($this->uri, strpos($this->uri, '?') + 1);
            $queries = explode('&', $queryString);
            foreach($queries as $query){
                $q = explode('=', $query);
                $this->queries[$q[0]] = $q[1];
            }
        }
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