<?php

namespace Test;

use Asd\Dispatcher;

class DispatcherTest extends \PHPUnit_Framework_TestCase{
    
    /**
    *   @test
    *   @expectedException  \Exception
    *   @covers             \ASD\Dispatcher:__construct
    */
    public function constructor_withNoArguments_throwsException(){
        $dispatcher = new Dispatcher();
    }
}