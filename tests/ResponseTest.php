<?php

namespace Test;

use Asd\Response;
use Asd\iResponse;

class ResponseTest extends \PHPUnit_Framework_TestCase
{   
    /**
     * @test
     * @covers  Asd\Response::__construct
     */
    public function constructor_takesStringArgument()
    {
        $response = new Response('string');
    }
    
    /**
     * @test
     * @covers  Asd\Response::__construct
     */
    public function constructor_withNoArguments_defaultsToEmptyString()
    {
        $response = new Response();
    }
    
    /**
     * @test
     */
    public function implements_iResponse_Interface()
    {
        $response = new Response();
        $this->assertTrue($response instanceof iResponse);
    }
}