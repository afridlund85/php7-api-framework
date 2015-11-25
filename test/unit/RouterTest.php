<?php
namespace Test\Unit;

use Asd\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Exception
     */
    public function constructor_withNoArgument_throwsException()
    {
        new Router();
    }
    
    /**
     * @test
     */
    public function constructor_withControllerFactory_throwsNoException()
    {
        $factoryStub = $this->getMockBuilder('Asd\ControllerFactory')
            ->getMock();
            
        new Router($factoryStub);
    }
}