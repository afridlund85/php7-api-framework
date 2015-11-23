<?php
declare(strict_types = 1);

namespace Asd;

class Request implements iRequest{
    /**
     * @var string
     */
    private $url = '';
    
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
        $this->parseUrl();
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
    public function getQuery(string $queryKey = null) : string
    {
        if($queryKey === null) throw new \Exception('Query key is missing');
        if(!isset($this->queries[$queryKey])) throw new \Exception('Query Key does not exist.');
        return $this->queries[$queryKey];
    }
    
    /**
     * Checks server variables for the uri the request was made to.
     * @return void
     */
    private function parseUrl()
    {
        if(isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO'])){
            $this->url = $_SERVER['PATH_INFO'];
        }
        else if(isset($_SERVER['REQUEST_URI'])){
            $this->url = $_SERVER['REQUEST_URI'];
        }
        $this->uri = $this->url;
        if(strpos($this->url, '?') !== false){
            $uri = explode('?', $this->url);
            $this->uri = $uri[0];
        }
    }
    
    /**
     * Checks $_GET and the uri for query parameters
     * @return void
     */
    private function parseQueries()
    {
        if(isset($_SERVER['QUERY_STRING'])){
            parse_str($_SERVER['QUERY_STRING'], $this->queries);
        }
        else{
            if(isset($this->url) && strpos($this->url, '?') !== false){
                $queryString = substr($this->url, strpos($this->url, '?') + 1);
                parse_str($queryString, $this->queries);
            }
        }
        if(isset($_GET) && !empty($_GET)){
            $this->queries += $_GET;
        }
    }
}