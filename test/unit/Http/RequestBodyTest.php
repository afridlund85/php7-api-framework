<?php
namespace Test\Unit;

use Asd\Http\RequestBody;
use Test\Fakes\StreamStub;

class RequestBodyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @covers Asd\Http\RequestBody::__construct
     */
    public function constructor_withDefaultValue()
    {
        $requestBody = new RequestBody();
        $this->assertEquals('', (string)$requestBody);

        $this->assertEquals(0, $requestBody->tell());
        $this->assertTrue($requestBody->isWritable());
        $requestBody->write('some-text');
        $this->assertEquals(9, $requestBody->tell());
        $requestBody->seek(5);
        $this->assertEquals('text', $requestBody->getContents());
        $this->assertEquals('some-text', (string)$requestBody);

        $requestBody->close();
    }

    /**
     * @test
     * @covers Asd\Http\RequestBody::__construct
     */
    public function constructor_withValidStream()
    {
        stream_wrapper_register('requestBody', '\Test\Fakes\StreamStub');
        $streamStub = fopen('requestBody://requestBody', 'r+');
        $requestBody = new RequestBody($streamStub);
        $this->assertEquals($streamStub, $requestBody->detach());
        fclose($streamStub);
        stream_wrapper_unregister('requestBody');
    }

    /**
     * @test
     * @covers Asd\Http\RequestBody::__construct
     * @expectedException InvalidArgumentException
     */
    public function constructor_withInvalidResrouce()
    {
        new RequestBody('not-ok-resrouce');
    }

}