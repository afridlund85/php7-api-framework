<?php
namespace Test\Unit;

use Asd\Http\Headers;

class HeadersTest extends \PHPUnit_Framework_TestCase
{
    protected $headers;
    protected $headerStub;

    public function setUp()
    {
        $this->headerStub = $this->getMockBuilder('\\Asd\\Http\\Header')
            ->disableOriginalConstructor()
            ->getMock();
        $this->headerStub->method('getName')->willReturn('myHeader');
        $this->headerStub->method('getValues')->willReturn(['myValue']);
        $headers = new Headers();
        $this->headers = $headers->add($this->headerStub);
    }

    /**
     * @test
     * @covers Asd\Http\Headers::get
     * @covers Asd\Http\Headers::contains
     */
    public function get()
    {
        $this->assertEquals($this->headerStub, $this->headers->get('myHeader'));
    }

    /**
     * @test
     * @covers Asd\Http\Headers::get
     * @covers Asd\Http\Headers::contains
     * @expectedException OutOfBoundsException
     */
    public function get_whenEmpty()
    {
        $headers = new headers();
        $headers->get('invalidKey');
    }

    /**
     * @test
     * @covers Asd\Http\Headers::get
     * @covers Asd\Http\Headers::contains
     * @expectedException OutOfBoundsException
     */
    public function get_withInvalidKey()
    {
        $this->headers->get('invalidKey');
    }

    /**
     * @test
     * @covers Asd\Http\Headers::all
     */
    public function all_whenEmpty()
    {
        $headers = new Headers();
        $this->assertEquals([], $headers->all());
    }

    /**
     * @test
     * @covers Asd\Http\Headers::all
     */
    public function all()
    {
        $this->assertEquals([$this->headerStub], $this->headers->all());
    }

    /**
     * @test
     * @covers Asd\Http\Headers::add
     * @covers Asd\Http\Headers::get
     * @covers Asd\Http\Headers::all
     */
    public function add()
    {
        $newHeaderStub = $this->getMockBuilder('\\Asd\\Http\\Header')
            ->disableOriginalConstructor()
            ->getMock();
        $newHeaderStub->method('getName')->willReturn('otherHeader');
        $newHeaderStub->method('getValues')->willReturn(['otherValue']);
        
        $headers = $this->headers->add($newHeaderStub);

        $this->assertEquals($newHeaderStub, $headers->get('otherHeader'));
        $this->assertEquals([$this->headerStub, $newHeaderStub], $headers->all());
        $this->assertNotSame($this->headers, $headers);
    }

    /**
     * @test
     * @covers Asd\Http\Headers::add
     * @covers Asd\Http\Headers::get
     * @covers Asd\Http\Headers::all
     */
    public function add_withExistingKey()
    {
        $newHeaderStub = $this->getMockBuilder('\\Asd\\Http\\Header')
            ->disableOriginalConstructor()
            ->getMock();
        $newHeaderStub->method('getName')->willReturn('myHeader');
        $newHeaderStub->method('getValues')->willReturn(['otherValue']);
        
        $headers = $this->headers->add($newHeaderStub);

        $this->assertEquals($newHeaderStub, $headers->get('myHeader'));
        $this->assertEquals([$newHeaderStub], $headers->all());
        $this->assertNotSame($this->headers, $headers);
    }

    /**
     * @test
     * @covers Asd\Http\Headers::remove
     * @covers Asd\Http\Headers::contains
     */
    public function remove()
    {
        $headers = $this->headers->remove('myHeader');
        $this->assertEquals([], $headers->all());
        $this->assertNotSame($headers, $this->headers);
    }

    /**
     * @test
     * @covers Asd\Http\Headers::remove
     * @covers Asd\Http\Headers::contains
     */
    public function remove_onEmpty()
    {
        $headers = $this->headers->remove('myHeader');
        $headers = $this->headers->remove('myHeader');
        $this->assertEquals([], $headers->all());
        $this->assertNotSame($headers, $this->headers);
    }

    /**
     * @test
     * @covers Asd\Http\Headers::remove
     * @covers Asd\Http\Headers::contains
     */
    public function remove_invalidKey()
    {
        $headers = $this->headers->remove('invalidKey');
        $this->assertEquals([$this->headerStub], $headers->all());
    }
}