<?php
namespace Test;

use Asd\Request;
use Asd\iRequest;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test 
     */
    public function implements_iRequestInterface()
    {
        $req = new Request();
        $this->assertTrue($req instanceof iRequest);
    }
}