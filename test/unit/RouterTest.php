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
}