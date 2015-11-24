<?php

namespace Test\System;

use GuzzleHttp\Client;

class NotARealTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    
    protected function setUp()
    {
        $this->client = new Client([
            'base_uri' => 'http://asd-api-framework.io',
            'timeout'  => 2.0,
        ]);
    }
    
    /**
     * @test
     * @expectedException \GuzzleHttp\Exception\RequestException
     */
    public function sendingGetToInvalidPath_ThrowsRequestException()
    {
        $this->client->request('GET', '/an/invalid/path');
    }
}