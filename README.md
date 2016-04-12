# Asd(name?) PHP7 API Framework [![Build Status](https://travis-ci.org/afridlund85/php7-api-framework.svg?branch=master)](https://travis-ci.org/afridlund85/php7-api-framework) [![Coverage Status](https://coveralls.io/repos/github/afridlund85/php7-api-framework/badge.png?branch=master)](https://coveralls.io/github/afridlund85/php7-api-framework?branch=master)

PHP7 Framework for API applications.

## Installation

None, it is not even close to ready for release.

##Some Key Concepts

* PHP 7 only
* No dependencies(expect PSR-7 interfaces)
* PSR: 1,2,4,7
* No statics, no globals
* No forced strict conventions in naming/routing, folder structure etc when using
* No settings or configs
* Transparency, no unncessary abstraction/wrapping
* Use PHP 7 strict types, (except PSR-7 classes, cuz they're not PHP 7)

## Intended usage

Presumptions: htaccess configured and PSR-4 autoloading

Define routes with HTTP-method, path and controllerclass + method

**index.php**
```
require_once('vendor/autoload.php');

use Asd\Asd;
use Asd\Router\Route;
use Asd\FunctionCallback;
use Asd\MethodCallback;

$app = new Asd();

//Controller class extending from Asd\Controller and has dependency to Asd\Session
$app->addRoute(new Route('GET', '/', new MethodCallback('MyApp\Controllers', 'Welcome', 'start')));

//Just some class with a method
$app->addRoute(new Route('GET', 'about', new MethodCallback('MyApp', 'SomeClass', 'about')));

//Closure/Anonymus function
$app->addRoute(new Route('post', 'blog', new FunctionCallback(function($req, $res){
  //do stuff
  return $this->res; //return response
})));

//Closure/anonymus function with dependency
$app->addRoute(new Route('post', 'auth', new FunctionCallback(function($req, $res, Asd\Session $session){
  //session class automatically injected through DI
  return $this->res;//return response
})));

$app->run();
```

**MyApp/Controllers/Welcome.php**
```
namespace MyApp\controllers;

use Asd\Controller;

class Welcome extends Controller
{
  public function __construct(Asd\Session $session)
  {
    //session class automatically injected through DI
  }
  public function start($req, $res)
  {
    return $res->withJsonResponse('Hello!');
  }
}
```

**MyApp/SomeClass.php**
```
namespace MyApp;

class SomeClass
{
  public function about($req, $res)
  {
    //do stuff;
    return $res;
  }
}
```

## Setup Development environment

*Requires PHP7*

```
git clone https://github.com/afridlund85/php7-api-framework.git
cd php7-api-framework
composer install
```

### Tests

Three levels of testing, unit, integration and system.

#### Runing tests

**Run all test suites**
```
composer test
```

**Run Unit test suite**
```
composer unit
```

**Run Integration test suite**
```
composer integration
```

**Run System test suite**
```
composer system
```

### PSR-2 linting

**Check for PSR-2 errors**

```
composer sniff
```

### Code coverage

**Generate code coverage**

```
composer coverage
```