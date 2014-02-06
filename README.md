iocContainer
============

Php Ioc Container

This is a [Dependency Injection](http://www.martinfowler.com/articles/injection.html) / [Inversion of Control](http://codebetter.com/jeremymiller/2005/09/13/inversion-of-control-with-the-plugin-pattern/) tool for PHP.

This tool can help you if you need:

Extensibility
A generic configuration tool
Support multiple deployment configurations
Test-Driven Development philosophy or automate testing

Table of content:

* [Requirements](#requirements)
* [Install](#install)
* [Use](#use)
    * [Getting ioc's container instance](#getting ioc)


Requirements
------------

PHP 5.3 or above

Install
-------

Download iocContainer (you just need ioc folder) and include IocContainer.php to your code.

```php
...

include '/path_to_ioc_folder/ioc/IocContainer.php';

...
```

Getting ioc's container instance
--------------------------------

```php
...

$ioc = \ioc\IocContainer::getContainer();

...
```