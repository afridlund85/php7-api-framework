<?php
declare(strict_types = 1);

namespace Asd;

class Request implements iRequest{
    
    private $url;
    
    public function getUrl()
    {
        return $this->url;
    }
}