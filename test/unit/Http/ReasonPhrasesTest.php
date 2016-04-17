<?php
namespace Test\Unit;

use OutOfBoundsException;
use Asd\Http\ReasonPhrases;

class ReasonPhrasesTest extends \PHPUnit_Framework_TestCase
{
    protected $reasonPhrases;
    
    public function setUp()
    {
        $this->reasonPhrases = new ReasonPhrases();
    }

    /**
     * @test
     * @covers Asd\Http\ReasonPhrases::hasPhrase
     */
    public function hasPhrase()
    {
        $this->assertTrue($this->reasonPhrases->hasPhrase(200));
        $this->assertFalse($this->reasonPhrases->hasPhrase(110));
    }

    /**
     * @test
     * @covers Asd\Http\ReasonPhrases::getPhrase
     */
    public function getPhrase()
    {
        $this->assertEquals('OK', $this->reasonPhrases->getPhrase(200));
    }

    /**
     * @test
     * @covers Asd\Http\ReasonPhrases::getPhrase
     * @expectedException OutOfBoundsException
     */
    public function getPhrase_withInvalidKey()
    {
        $this->reasonPhrases->getPhrase(110);
    }

    /**
     * @test
     * @covers Asd\Http\ReasonPhrases::__construct
     * @covers Asd\Http\ReasonPhrases::hasPhrase
     * @covers Asd\Http\ReasonPhrases::getPhrase
     */
    public function constructor_withCustomPhrases()
    {
        $phrases = [200 => 'Okay', 300 => 'Dokay'];
        $reasonPhrases = new ReasonPhrases($phrases);

        $this->assertEquals('Okay', $reasonPhrases->getPhrase(200));
        $this->assertEquals('Dokay', $reasonPhrases->getPhrase(300));
        $this->assertFalse($reasonPhrases->hasPhrase(201));
    }

    /**
     * @test
     * @covers Asd\Http\ReasonPhrases::withPhrases
     */
    public function withPhrases()
    {
        $phrases = [200 => 'Okay', 300 => 'Dokay'];
        $reasonPhrases = $this->reasonPhrases->withPhrases($phrases);
        $this->assertEquals('Okay', $reasonPhrases->getPhrase(200));
        $this->assertEquals('Dokay', $reasonPhrases->getPhrase(300));
        $this->assertFalse($reasonPhrases->hasPhrase(201));
    }

    /**
     * @test
     * @covers Asd\Http\ReasonPhrases::withAddedPhrases
     */
    public function withAddedPhrases()
    {
        $phrases = [200 => 'Okay', 110 => 'Dokay'];
        $reasonPhrases = $this->reasonPhrases->withAddedPhrases($phrases);
        $this->assertEquals('Okay', $reasonPhrases->getPhrase(200));
        $this->assertEquals('Dokay', $reasonPhrases->getPhrase(110));
        $this->assertTrue($reasonPhrases->hasPhrase(201));
    }
}