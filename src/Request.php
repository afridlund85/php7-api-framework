<?php
declare(strict_types = 1);

namespace Asd;

class Request implements iRequest{
    
    /**
     * @var string
     */
    private $uri = '';
    
    /**
     * @var array
     */
    private $queries = [];
    
    /**
     * 
     */
    public function __construct()
    {
        $this->parseUri();
        $this->parseQueries();
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
    
    /**
     * @param string $queryKey
     * @return string
     */
    public function getQuery(string $queryKey) : string
    {
        
    }
    
    /**
     * Checks server variables for the uri the request was made to.
     * @return void
     */
    private function parseUri()
    {
        if(isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])){
            $this->uri = $_SERVER['PATH_INFO'];
        }
        else{
            $this->uri = $_SERVER['REQUEST_URI'];
        }
    }
    
    /**
     * Checks $_GET and the uri for query parameters
     * @return void
     */
    private function parseQueries()
    {
        if(isset($this->uri) && strpos($this->uri, '?') !== false){
            $queryString = substr($this->uri, strpos($this->uri, '?') + 1);
            parse_str($queryString, $this->queries);
        }
        if(isset($_GET) && !empty($_GET)){
            $this->queries += $_GET;
        }
    }
}