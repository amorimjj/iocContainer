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
    * [Getting ioc's container instance](#getting-iocs-container-instance)
    * [Getting instance from a concrete class](#getting-instance-from-a-contrete-class)
    * [Getting singleton instances](#getting-singleton-instances)
    * [Getting instance from registered Interface](#getting-instance-from-registered-interface)
    * [Getting instance from registered Abstract Class](#getting-instance-from-registered-abstract-class)
    * [Registers](#registers)
        *[Registering concrete class to interface](#registering-concrete-class-to-interface)

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

Use
---

### Getting ioc's container instance ###

```php
...

$ioc = \ioc\IocContainer::getContainer();

...
```

### Getting instance from a concrete class ###

Using concrete class, all dependencies are resolved automatically

```php
...

$instance = $ioc->getInstance('Class0'); // $intance is instance of Class0

...
```

*Using class namespace*

```php
...

$instance = $ioc->getInstance('\test\test2\Class4'); // $intance is instance of \test\test2\Class4

...

### Getting singleton instances ###

IocContainer can control single instances


```php
...

$instance1 = $ioc->getSingletonInstance('Class0');
$instance2 = $ioc->getSingletonInstance('Class0');
$instance3 = $ioc->getInstance('Class0');

//$instance1 === $instance2 true;
//$instance1 === $instance3 false;

...

### Getting instance from registered **Interface** ###

[Read more about registers here](#registers)

```php
...

 $instance = $ioc->getInstance('IClass'); //$instance is instance of concrete class registered to IClass

...

### Getting instance from registered **Abstract Class** ###

[Read more about registers here](#registers)

```php
...

 $instance = $ioc->getInstance('AbstractClass'); //$instance is instance of concrete class registered to AbstractClass

...

### **Registers** ###

Some dependencies like Interfaces and Abstract Classes are not resolved alone and should be defined which concrete class iocContainer will use.

//Registering concrete class to interface

```php
...

 $ioc->register('IClass','Class2'); //Class2 must be a IClass implementation

...
