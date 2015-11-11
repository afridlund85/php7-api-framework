<?php

use Asd\Asd;

class AsdTest extends PHPUnit_Framework_TestCase{
    
    /**
    *   @test
    *   @expectedException \Exception
    *   @covers \API\Asd:__construct
    */
    public function constructor_withNoArguments_throwsException(){
        $asd = new Asd();
    }
}