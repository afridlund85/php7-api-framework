<?php

namespace Test;

use Asd\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException   \Exception
     * @covers              Asd\Response::__construct
     */
    public function constructor_withNoArguments_ThrowsException()
    {
        $response = new Response();
    }
}