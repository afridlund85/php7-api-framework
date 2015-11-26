<?php
namespace Test\Unit;

use Asd\Response;
use Asd\iResponse;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function implements_iResponse_Interface()
    {
        $response = new Response();
        $this->assertTrue($response instanceof iResponse);
    }
    
    /**
     * @test
     * @covers  Asd\Response::__construct
     */
    public function constructor_withNoArguments_defaultsToEmptyString()
    {
        $response = new Response();
    }
    
    /**
     * @test
     * @covers  Asd\Response::__construct
     */
    public function constructor_takesStringArgument()
    {
        $response = new Response('string');
    }
    
    /**
     * @test
     * @covers  Asd\Response::getBody
     */
    public function getBody_returnsResponseBodyAsString()
    {
        $expected = 'the response body';
        $response = new Response($expected);
        
        $actual = $response->getBody();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers  Asd\Response::setBody
     */
    public function setBody_setsBodyOfResponseObject()
    {
        $expected = 'expected body';
        $response = new Response();
        $response->setBody($expected);
        
        $actual = $response->getBody();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers  Asd\Response::__construct
     */
    public function constructor_takesArgumentStatusCode()
    {
        $expected = 404;
        $response = new Response('some body', $expected);
        
        $actual = $response->getStatusCode();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers  Asd\Response::__construct
     * @covers  Asd\Response::getStatusCode
     */
    public function responsePropertyStatusCode_defaultsTo200()
    {
        $expected = 200;
        $response = new Response();
        
        $actual = $response->getStatusCode();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers  Asd\Response::setStatusCode
     * @covers  Asd\Response::getStatusCode
     */
    public function setStatusCode_withCorrectArgument_setsStatusCode()
    {
        $expected = 300;
        $response = new Response();
        
        $response->setStatusCode($expected);
        $actual = $response->getStatusCode();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers              Asd\Response::setStatusCode
     * @expectedException   \Exception
     */
    public function setStatusCode_withIllegalLowValue_throwsException()
    {
        $illegalStatusCode = 99;
        $response = new Response();
        
        $response->setStatusCode($illegalStatusCode);
    }
    
    /**
     * @test
     * @covers              Asd\Response::setStatusCode
     * @expectedException   \Exception
     */
    public function setStatusCode_withIllegalHighValue_throwsException()
    {
        $illegalStatusCode = 1000;
        $response = new Response();
        
        $response->setStatusCode($illegalStatusCode);
    }
    
    /**
     * @test
     * @covers  Asd\Response::__construct
     * @covers  Asd\Response::getHeaders
     */
    public function constructor_headersArrayDefaultsToEmptyArray()
    {
        $expected = [];
        $response = new Response();
        
        $actual = $response->getHeaders();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers  Asd\Response::addHeader
     * @covers  Asd\Response::getHeaders
     */
    public function addHeader_withCorrectArguments_addsToHeaderArray()
    {
        $headerKey = 'theKey';
        $headerValue = 'theValue';
        $response = new Response();
        $expected = [$headerKey => $headerValue];
        
        $response->addHeader($headerKey, $headerValue);
        $actual = $response->getHeaders();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers  Asd\Response::addHeader
     * @covers  Asd\Response::getHeaders
     */
    public function addHeader_whenKeyAlreadyExists_overwritesHeaderKey()
    {
        $headerKey = 'theKey';
        $headerValue = 'theValue';
        $newHeaderValue = 'newValue';
        $response = new Response();
        $expected = [$headerKey => $newHeaderValue];
        
        $response->addHeader($headerKey, $headerValue);
        $response->addHeader($headerKey, $newHeaderValue);
        $actual = $response->getHeaders();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers  Asd\Response::addHeader
     * @covers  Asd\Response::getHeaders
     */
    public function addHeader_withoutValue_defaultsToEmptyString()
    {
        $headerKey = 'theKey';
        $response = new Response();
        $expected = [$headerKey => ''];
        
        $response->addHeader($headerKey);
        $actual = $response->getHeaders();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers              Asd\Response::addHeader
     * @expectedException   \Exception
     */
    public function addHeader_withoutKey_throwsException()
    {
        $response = new Response();
        $response->addHeader();
    }
    
    /**
     * @test
     * @covers Asd\Response::removeHeader
     */
    public function removeHeader_removesAHeaderFromArray()
    {
        $firstKey = 'first';
        $secondKey = 'second';
        $expected = [$secondKey => ''];
        $response = new Response();
        $response->addHeader($firstKey);
        $response->addHeader($secondKey);
        
        $response->removeHeader($firstKey);
        $actual = $response->getHeaders();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Response::removeHeader
     */
    public function removeHeader_withNonExistingKey_doesNotAffectArray()
    {
        $firstKey = 'first';
        $expected = [$firstKey => ''];
        $response = new Response();
        $response->addHeader($firstKey);
        
        $response->removeHeader('notThe' . $firstKey);
        $actual = $response->getHeaders();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Response::__construct
     * @covers Asd\Response::getProtocol
     */
    public function constructor_setsProtocolDefaultValue()
    {
        $expected = 'HTTP/1.1';
        $response = new Response();
        $actual = $response->getProtocol();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Response::setProtocol
     * @covers Asd\Response::getProtocol
     */
    public function setProtocol_withString_changesProtocolProperty()
    {
        $expected = 'CustomProt';
        $response = new Response();
        
        $response->setProtocol($expected);
        $actual = $response->getProtocol();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers              Asd\Response::setProtocol
     * @expectedException   \Exception
     */
    public function setProtocol_withNoArgument_throwsException()
    {
        $response = new Response();
        $response->setProtocol();
    }
    
    /**
     * @test
     * @covers Asd\Response::__construct
     * @covers Asd\Response::getContentType
     */
    public function constructor_setsContentTypeDefaultValue()
    {
        $expected = 'application/json';
        $response = new Response();
        $actual = $response->getContentType();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Response::setContentType
     * @covers Asd\Response::getContentType
     */
    public function setContentType_withString_changesContentTypeProperty()
    {
        $expected = 'text/html';
        $response = new Response();
        
        $response->setContentType($expected);
        $actual = $response->getContentType();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers              Asd\Response::setContentType
     * @expectedException   \Exception
     */
    public function setContentType_withNoArgument_throwsException()
    {
        $response = new Response();
        $response->setContentType();
    }
    
    /**
     * @test
     * @covers  Asd\Response::__construct
     * @covers  Asd\Response::getCharset
     */
    public function constructor_setsCharsetDefaultValue()
    {
        $expected = 'UTF-8';
        $response = new Response();
        $actual = $response->getCharset();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers Asd\Response::setCharset
     * @covers Asd\Response::setCharset
     */
    public function setCharset_withString_changesCharsetProperty()
    {
        $expected = 'UTF-16';
        $response = new Response();
        
        $response->setCharset($expected);
        $actual = $response->getCharset();
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @covers              Asd\Response::setCharset
     * @expectedException   \Exception
     */
    public function setCharset_withNoArgument_throwsException()
    {
        $response = new Response();
        $response->setCharset();
    }
}