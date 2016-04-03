<?php
namespace Test\Unit;

use Asd\Http\ResponseBody;
use Test\Unit\StreamStub;

class ResponseBody extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @covers Asd\Http\ResponseBody::__construct
     */
    public function constructor_withDefaultValue()
    {
        $responseBody = new ResponseBody();
        $this->assertEquals('', (string)$responseBody);

        $this->assertEquals(0, $responseBody->tell());
        $this->assertTrue($responseBody->isWritable());
        $responseBody->write('some-text');
        $this->assertEquals(9, $responseBody->tell());
        $responseBody->seek(5);
        $this->assertEquals('text', $responseBody->getContents());
        $this->assertEquals('some-text', (string)$responseBody);

        $responseBody->close();
    }

    /**
     * @test
     * @covers Asd\Http\ResponseBody::__construct
     */
    public function constructor_withValidStream()
    {
        stream_wrapper_register('responseBody', '\Test\Unit\StreamStub');
        $streamStub = fopen('responseBody://responseBody', 'r+');
        $responseBody = new ResponseBody($streamStub);
        $this->assertEquals($streamStub, $responseBody->detach());
        fclose($streamStub);
        stream_wrapper_unregister('responseBody');
    }

    /**
     * @test
     * @covers Asd\Http\ResponseBody::__construct
     * @expectedException InvalidArgumentException
     */
    public function constructor_withInvalidResrouce()
    {
        new ResponseBody('not-ok-resrouce');
    }

}