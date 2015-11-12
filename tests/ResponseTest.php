<?php
declare(strict_types = 1);

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
    
    /**
     * @test
     * @covers  Asd\Response::toString
     */
    public function toString_returnsResponseBodyAsString()
    {
        $expected = 'the response body';
        $response = new Response($expected);
        
        $actual = $response->toString();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers  Asd\Response::setBody
     */
    public function setBody_setsBodyOfResponseObject()
    {
        $expected = 'expected body';
        $response = new Response();
        $response->setBody($expected);
        
        $actual = $response->toString();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function responsePropertyStatusCode_defaultsTo200()
    {
        $expected = 200;
        $response = new Response();
        
        $actual = $response->getStatusCode();
        
        $this->assertEquals($expected, $actual);
    }
}