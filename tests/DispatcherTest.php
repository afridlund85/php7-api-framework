<?php

namespace Test;

use Asd\Dispatcher;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    
    /**
    * @test
    * @expectedException    \Exception
    * @covers               Asd\Dispatcher:__construct
    */
    public function constructor_withNoArguments_throwsException()
    {
        $dispatcher = new Dispatcher();
    }
    
    /**
     * @test
     * @covers              Asd\Dispatcher:__construct
     */
    public function constructor_withCorrectArguments_doesNotThrowException()
    {
        $requestStub = $this->getMockBuilder('Asd\iRequest')->getMock();
        $responseStub = $this->getMockBuilder('Asd\iResponse')->getMock();
        $dispatcher = new Dispatcher($requestStub, $responseStub);
    }
    
    /**
     * @test
     * @expectedException   \Exception
     * @covers              Asd\Dispatcher:__construct
     */
    public function constructor_withMissingReponseArgument_throwsException()
    {
        $requestStub = $this->getMockBuilder('Asd\iRequest')->getMock();
        $dispatcher = new Dispatcher($requestStub, null);
    }
}