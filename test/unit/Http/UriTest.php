<?php
namespace Test\Unit;

use InvalidArgumentException;
use Asd\Http\Uri;
use Psr\Http\Message\UriInterface;

class UriTest extends \PHPUnit_Framework_TestCase
{

  protected $defaultData = [
    'scheme'    => 'http',
    'user'      => 'username',
    'password'  => 'password',
    'host'      => 'my-host.io',
    'port'      => 80,
    'path'      =>  '/asd/qwe',
    'query'     => 'q1=asd&q2=qwe',
    'fragment'  => 'fragment'
  ];

  protected function uriSetup()
  {
    return new Uri(
      $this->defaultData['scheme'],
      $this->defaultData['user'],
      $this->defaultData['password'],
      $this->defaultData['host'],
      $this->defaultData['port'],
      $this->defaultData['path'],
      $this->defaultData['query'],
      $this->defaultData['fragment']
    );
  }

  /**
   * @test
   * @covers Asd\Http\Uri::__construct
   */
  public function implements_PSR7()
  {
    $uri = $this->uriSetup();
    $this->assertInstanceOf('Psr\Http\Message\UriInterface', $uri);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withScheme
   * @covers Asd\Http\Uri::getScheme
   */
  public function withScheme_isImmutable()
  {
    $uri = $this->uriSetup();
    $uri1 = $uri->withScheme('https');

    $this->assertEquals($this->defaultData['scheme'], $uri->getScheme());
    $this->assertEquals('https', $uri1->getScheme());
    $this->assertNotSame($uri, $uri1);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withScheme
   * @covers Asd\Http\Uri::getScheme
   */
  public function withScheme_validStringValues()
  {
    $uri = $this->uriSetup();
    $uri1 = $uri->withScheme('https');
    $uri2 = $uri->withScheme('https://');
    $uri3 = $uri->withScheme('http://');
    $uri4 = $uri->withScheme('http');

    $this->assertEquals('https', $uri1->getScheme());
    $this->assertEquals('https', $uri2->getScheme());
    $this->assertEquals('http', $uri3->getScheme());
    $this->assertEquals('http', $uri4->getScheme());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withScheme
   * @covers Asd\Http\Uri::getScheme
   */
  public function withScheme_emptyValues()
  {
    $uri = $this->uriSetup();
    $uri1 = $uri->withScheme('');
    $uri2 = $uri->withScheme(null);
    $uri3 = $uri->withScheme(false);
    $uri4 = $uri->withScheme(0);
    $uri5 = $uri->withScheme([]);
    
    $this->assertSame('', $uri1->getScheme());
    $this->assertSame('', $uri2->getScheme());
    $this->assertSame('', $uri3->getScheme());
    $this->assertSame('', $uri4->getScheme());
    $this->assertSame('', $uri5->getScheme());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withScheme
   * @expectedException InvalidArgumentException
   */
  public function withScheme_invalidStringValue()
  {
    $uri = $this->uriSetup()->withScheme('ftp');
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withScheme
   * @expectedException InvalidArgumentException
   */
  public function withScheme_numericValue()
  {
    $uri = $this->uriSetup()->withScheme(123);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withScheme
   * @expectedException InvalidArgumentException
   */
  public function withScheme_arrayValue()
  {
    $uri = $this->uriSetup()->withScheme(['http']);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getHost
   */
  public function getHost()
  {
    $uri = new Uri();
    $uri2 = $this->uriSetup();

    $this->assertEquals('', $uri->getHost());
    $this->assertEquals($this->defaultData['host'], $uri2->getHost());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getHost
   * @covers Asd\Http\Uri::withHost
   */
  public function getHost_upperCase()
  {
    $uri = $this->uriSetup()->withHost('MYHOST.COM');
    $this->assertEquals('myhost.com', $uri->getHost());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withHost
   * @covers Asd\Http\Uri::getHost
   */
  public function withHost_isImmutable()
  {
    $uri = $this->uriSetup();
    $uri1 = $uri->withHost('myhost.com');

    $this->assertEquals($this->defaultData['host'], $uri->getHost());
    $this->assertEquals('myhost.com', $uri1->getHost());
    $this->assertNotSame($uri, $uri1);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withHost
   * @covers Asd\Http\Uri::getHost
   */
  public function withHost_emptyValues()
  {
    $uri = $this->uriSetup();
    $uri1 = $uri->withHost('');
    $uri2 = $uri->withHost(null);
    $uri3 = $uri->withHost(false);
    $uri4 = $uri->withHost(0);
    $uri5 = $uri->withHost([]);

    $this->assertSame('', $uri1->getHost());
    $this->assertSame('', $uri2->getHost());
    $this->assertSame('', $uri3->getHost());
    $this->assertSame('', $uri4->getHost());
    $this->assertSame('', $uri5->getHost());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withHost
   * @expectedException InvalidArgumentException
   */
  public function withHost_numericValue()
  {
    $uri = $this->uriSetup()->withHost(123);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getPort
   */
  public function getPort_port80_schemeHttp()
  {
    $uri = new Uri();
    $uri2 = $this->uriSetup();

    $this->assertEquals(null, $uri->getPort());
    $this->assertEquals(null, $uri2->getPort());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getPort
   * @covers Asd\Http\Uri::withPort
   */
  public function getPort_port443_schemeHttps()
  {
    $uri = $this->uriSetup()->withPort(443)->withScheme('https');
    $this->assertEquals(null, $uri->getPort());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getPort
   * @covers Asd\Http\Uri::withPort
   */
  public function getPort_nonStandardPort()
  {
    $uri = $this->uriSetup()->withPort(8080);
    $this->assertEquals(8080, $uri->getPort());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getPort
   * @covers Asd\Http\Uri::withPort
   */
  public function getPort_nullValue()
  {
    $uri = $this->uriSetup()->withPort(null);
    $this->assertEquals(null, $uri->getPort());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getPort
   * @covers Asd\Http\Uri::withPort
   */
  public function getPort_nullValue_nullScheme()
  {
    $uri = $this->uriSetup()->withScheme(null)->withPort(null);
    $this->assertEquals(null, $uri->getPort());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withPort
   * @expectedException InvalidArgumentException
   */
  public function withPort_tooSmallInteger()
  {
    $uri = $this->uriSetup()->withPort(0);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withPort
   * @expectedException InvalidArgumentException
   */
  public function withPort_tooLargeInteger()
  {
    $uri = $this->uriSetup()->withPort(65535);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withPort
   * @expectedException InvalidArgumentException
   */
  public function withPort_string()
  {
    $uri = $this->uriSetup()->withPort('8080');
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withPort
   * @expectedException InvalidArgumentException
   */
  public function withPort_float()
  {
    $uri = $this->uriSetup()->withPort(8080.8080);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withPort
   * @expectedException InvalidArgumentException
   */
  public function withPort_boolean()
  {
    $uri = $this->uriSetup()->withPort(false);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getPath
   */
  public function getPath()
  {
    $uri = new Uri();
    $uri2 = $this->uriSetup();

    $this->assertEquals('', $uri->getPath());
    $this->assertEquals($this->defaultData['path'], $uri2->getPath());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withPath
   * @covers Asd\Http\Uri::getPath
   */
  public function withPath_isImmutable()
  {
    $uri = $this->uriSetup();
    $uri2 = $uri->withPath('/other/path');

    $this->assertEquals($this->defaultData['path'], $uri->getPath());
    $this->assertEquals('/other/path', $uri2->getPath());
    $this->assertNotSame($uri, $uri2);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withPath
   * @covers Asd\Http\Uri::getPath
   */
  public function withPath_emptyValues()
  {
    $uri = $this->uriSetup()->withPath('');
    $uri2 = $uri->withPath(null);
    $uri3 = $uri->withPath(0);
    $uri4 = $uri->withPath(false);

    $this->assertEquals('', $uri->getPath());
    $this->assertEquals('', $uri2->getPath());
    $this->assertEquals('', $uri3->getPath());
    $this->assertEquals('', $uri4->getPath());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withPath
   * @covers Asd\Http\Uri::getPath
   */
  public function withPath()
  {
    $uri = $this->uriSetup();
    $uri2 = $uri->withPath('/absolute/path');
    $uri3 = $uri->withPath('relative/path');
    $uri4 = $uri->withPath('/');

    $this->assertEquals($this->defaultData['path'], $uri->getPath());
    $this->assertEquals('/absolute/path', $uri2->getPath());
    $this->assertEquals('relative/path', $uri3->getPath());
    $this->assertEquals('/', $uri4->getPath());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withPath
   * @expectedException InvalidArgumentException
   */
  public function withPath_numericValue()
  {
    $uri = $this->uriSetup()->withPath(123);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getUserInfo
   */
  public function getUserInfo()
  {
    $uri = new Uri();
    $uri2 = $this->uriSetup();
    $expected = $this->defaultData['user'] . ':' . $this->defaultData['password'];

    $this->assertEquals('', $uri->getUserInfo());
    $this->assertEquals($expected, $uri2->getUserInfo());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getUserInfo
   * @covers Asd\Http\Uri::withUserInfo
   */
  public function withUserInfo_validUsernameAndPassword()
  {
    $uri = $this->uriSetup()->withUserInfo('user', 'pass');
    $this->assertEquals('user:pass', $uri->getUserInfo());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getUserInfo
   * @covers Asd\Http\Uri::withUserInfo
   */
  public function withUserInfo_withoutPassword()
  {
    $uri = $this->uriSetup()->withUserInfo('user');
    $this->assertEquals('user', $uri->getUserInfo());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getUserInfo
   * @covers Asd\Http\Uri::withUserInfo
   */
  public function withUserInfo_emptyString()
  {
    $uri = $this->uriSetup()->withUserInfo('');
    $this->assertEquals('', $uri->getUserInfo());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getUserInfo
   * @covers Asd\Http\Uri::withUserInfo
   */
  public function withUserInfo_emptyValues()
  {
    $uri = $this->uriSetup()->withUserInfo(null);
    $this->assertEquals('', $uri->getUserInfo());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getUserInfo
   * @covers Asd\Http\Uri::withUserInfo
   */
  public function withUserInfo_withoutUsername_ValidPassword()
  {
    $uri = $this->uriSetup()->withUserInfo('', 'pass');
    $this->assertEquals('', $uri->getUserInfo());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withUserInfo
   * @expectedException InvalidArgumentException
   */
  public function withUserInfo_numericValue()
  {
    $uri = $this->uriSetup()->withUserInfo(123);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withUserInfo
   * @expectedException InvalidArgumentException
   */
  public function withUserInfo_arrayValue()
  {
    $uri = $this->uriSetup()->withUserInfo(['user']);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withUserInfo
   * @expectedException InvalidArgumentException
   */
  public function withUserInfo_invalidPassword()
  {
    $uri = $this->uriSetup()->withUserInfo('user', 123);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getAuthority
   */
  public function getAuthority()
  {
    $uri = new Uri();
    $uri2 = $this->uriSetup();
    $expected = $this->defaultData['user'] . ':'
      . $this->defaultData['password'] . '@'
      . $this->defaultData['host'];

    $this->assertEquals('', $uri->getAuthority());
    $this->assertEquals($expected, $uri2->getAuthority());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getAuthority
   * @covers Asd\Http\Uri::withPort
   */
  public function getAuthority_withNonStandardPort()
  {
    $uri = $this->uriSetup()->withPort(8080);
    $expected = $this->defaultData['user'] . ':'
      . $this->defaultData['password'] . '@'
      . $this->defaultData['host'] . ':8080';
    $this->assertEquals($expected, $uri->getAuthority());
  }
  
  /**
   * @test
   * @covers Asd\Http\Uri::getAuthority
   * @covers Asd\Http\Uri::withUserInfo
   */
  public function getAuthority_withUsername()
  {
    $uri = $this->uriSetup()->withUserInfo('user');
    $expected = 'user' . '@' . $this->defaultData['host'];
    $this->assertEquals($expected, $uri->getAuthority());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getAuthority
   * @covers Asd\Http\Uri::withUserInfo
   */
  public function getAuthority_withNoUserInfo()
  {
    $uri = $this->uriSetup()->withUserInfo('');
    $this->assertEquals($this->defaultData['host'], $uri->getAuthority());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getQuery
   */
  public function getQuery()
  {
    $uri = new Uri();
    $uri2 = $this->uriSetup();

    $this->assertEquals('', $uri->getQuery());
    $this->assertEquals($this->defaultData['query'], $uri2->getQuery());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getQuery
   * @covers Asd\Http\Uri::withQuery
   */
  public function withQuery_emptyString()
  {
    $uri = $this->uriSetup()->withQuery('');
    $this->assertEquals('', $uri->getQuery());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withQuery
   * @expectedException InvalidArgumentException
   */
  public function withQuery_numericValue()
  {
    $uri = $this->uriSetup()->withQuery(123);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withQuery
   * @expectedException InvalidArgumentException
   */
  public function withQuery_arrayValue()
  {
    $uri = $this->uriSetup()->withQuery([1, 2, 3]);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withQuery
   * @expectedException InvalidArgumentException
   */
  public function withQuery_booleanValue()
  {
    $uri = $this->uriSetup()->withQuery(true);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withQuery
   * @covers Asd\Http\Uri::getQuery
   */
  public function withQuery_isImmutable()
  {
    $uri = $this->uriSetup();
    $uri1 = $uri->withQuery('other=query');

    $this->assertEquals($this->defaultData['query'], $uri->getQuery());
    $this->assertEquals('other=query', $uri1->getQuery());
    $this->assertNotSame($uri, $uri1);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withQuery
   * @covers Asd\Http\Uri::getQuery
   */
  public function withQuery_validStringValues()
  {
    $uri = $this->uriSetup()->withQuery('firstQuery');
    $uri1 = $uri->withQuery('other=query');
    $uri2 = $uri->withQuery('one=with&many&values=true');

    $this->assertEquals('firstQuery', $uri->getQuery());
    $this->assertEquals('other=query', $uri1->getQuery());
    $this->assertEquals('one=with&many&values=true', $uri2->getQuery());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getFragment
   */
  public function getFragment()
  {
    $uri = new Uri();
    $uri2 = $this->uriSetup();

    $this->assertEquals('', $uri->getFragment());
    $this->assertEquals($this->defaultData['fragment'], $uri2->getFragment());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::getFragment
   * @covers Asd\Http\Uri::withFragment
   */
  public function withFragment_emptyString()
  {
    $uri = $this->uriSetup()->withFragment('');
    $this->assertEquals('', $uri->getFragment());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withFragment
   * @expectedException InvalidArgumentException
   */
  public function withFragment_numericValue()
  {
    $uri = $this->uriSetup()->withFragment(123);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withFragment
   * @expectedException InvalidArgumentException
   */
  public function withFragment_arrayValue()
  {
    $uri = $this->uriSetup()->withFragment([1, 2, 3]);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withFragment
   * @expectedException InvalidArgumentException
   */
  public function withFragment_booleanValue()
  {
    $uri = $this->uriSetup()->withFragment(true);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withFragment
   * @covers Asd\Http\Uri::getFragment
   */
  public function withFragment_isImmutable()
  {
    $uri = $this->uriSetup();
    $uri1 = $uri->withFragment('frag');

    $this->assertEquals($this->defaultData['fragment'], $uri->getFragment());
    $this->assertEquals('frag', $uri1->getFragment());
    $this->assertNotSame($uri, $uri1);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::withFragment
   * @covers Asd\Http\Uri::getFragment
   */
  public function withFragment_validStringValues()
  {
    $uri = $this->uriSetup()->withFragment('frag');
    $uri1 = $uri->withFragment('fr4gm3NT');

    $this->assertEquals('frag', $uri->getFragment());
    $this->assertEquals('fr4gm3NT', $uri1->getFragment());
  }

  /**
   * @test
   * @covers Asd\Http\Uri::__toString
   */
  public function __toString_defaultValues()
  {
    $uri = new Uri();
    $uri2 = $this->uriSetup();
    $expected = $this->defaultData['scheme'] . '://'
      . $this->defaultData['user'] . ':' . $this->defaultData['password'] 
      . '@' . $this->defaultData['host']
      . $this->defaultData['path']
      . '?' .$this->defaultData['query']
      . '#' . $this->defaultData['fragment'];

    $this->assertEquals('', (string)$uri);
    $this->assertEquals($expected, (string)$uri2);
  }

  /**
   * @test
   * @covers Asd\Http\Uri::__toString
   */
  public function __toString_variousValues()
  {
    $uri = new Uri();
    $this->assertEquals('', (string)$uri);

    $uri = $uri->withScheme('https');
    $this->assertEquals('https:', (string)$uri);

    $uri = $uri->withScheme('http')->withHost('domain.com');
    $this->assertEquals('http://domain.com/', (string)$uri);

    $uri = $uri->withScheme('');
    $this->assertEquals('//domain.com/', (string)$uri);

    $uri = $uri->withScheme('http')->withPort(80);
    $this->assertEquals('http://domain.com/', (string)$uri);

    $uri = $uri->withPort(3000);
    $this->assertEquals('http://domain.com:3000/', (string)$uri);

    $uri = $uri->withUserInfo('user', 'pass');
    $this->assertEquals('http://user:pass@domain.com:3000/', (string)$uri);

    $uri = $uri->withUserInfo('');
    $this->assertEquals('http://domain.com:3000/', (string)$uri);

    $uri = $uri->withUserInfo('otherUser');
    $this->assertEquals('http://otherUser@domain.com:3000/', (string)$uri);

    $uri = $uri->withPort(null)->withPath('relative/path');
    $this->assertEquals('http://otherUser@domain.com/relative/path', (string)$uri);

    $uri = $uri->withUserInfo('');
    $this->assertEquals('http://domain.com/relative/path', (string)$uri);

    $uri = $uri->withPath('/absolute/path');
    $this->assertEquals('http://domain.com/absolute/path', (string)$uri);

    $uri = $uri->withScheme('https')->withQuery('my=query&true');
    $this->assertEquals('https://domain.com/absolute/path?my=query&true', (string)$uri);

    $uri = $uri->withFragment('myFr4gM3nT');
    $this->assertEquals('https://domain.com/absolute/path?my=query&true#myFr4gM3nT', (string)$uri);
  }

}