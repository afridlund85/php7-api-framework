# Asd(name?) PHP7 API Framework

PHP7 Framework for API applications.

[check out Code Coverage](http://afridlund85.github.io/php7-api-framework/)

## Installation

None, it is not even close to ready for release.

## Setup Dev

*Requires PHP7 to be installed on the system!*

```
git clone https://github.com/afridlund85/php7-api-framework.git
cd php7-api-framework
composer install
```

### Runing tests

**Run all test suites in "test"-folder.**
```
composer run-script test
```

**Run Unit test suite**
```
composer run-script unit
```

**Run Integration test suite**
```
composer run-script integration
```

**Run System test suite**
```
composer run-script system
```

### Code coverage

Coverage files are on its own branch, gh-pages to make it runable through the browser. Switch branch or [click here](https://github.com/afridlund85/php7-api-framework/tree/gh-pages) to get to gh-pages branch.

[View coverage in browser](http://afridlund85.github.io/php7-api-framework/coverage/)

**Generate code coverage**

```
composer run-script coverage
```