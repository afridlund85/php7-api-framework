<?php
namespace Test\Unit;

use Asd\Router\FunctionCallback;

class FunctionCallbackTest extends \PHPUnit_Framework_TestCase
{
    protected $requestStub;
    protected $responseStub;
    /**
     * @before
     */
    public function setUp()
    {
        $this->requestStub = $this->getMockBuilder('\\Asd\\Http\\Request')
            ->disableOriginalConstructor()
            ->getMock();
        $this->responseStub = $this->getMockBuilder('\\Asd\\Http\\Response')
            ->disableOriginalConstructor()
            ->getMock();
    }
    /**
     * @test
     * @covers Asd\Router\FunctionCallback::__construct
     * @covers Asd\Router\FunctionCallback::invoke
     * @covers Asd\Router\Callback::getDependencies
     */
    public function invoke()
    {
        $fcb = new FunctionCallback(function($req, $res){
            return $res;
        });
        $this->assertEquals($this->responseStub, $fcb->invoke($this->requestStub, $this->responseStub));
    }
}