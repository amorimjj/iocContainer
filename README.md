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
        * [Registering concrete class to interface](#registering-concrete-class-to-interface)
        * [Registering concrete class to Abstract Class](#registering-concrete-class-to-abstract-class)
        * [Registering a implementation to existing concrete class](#registering-a-implementation-to-existing-concrete-class)
        * [Registering instances](#registering-instances)
        * [Defining instance's default values](#defining-instances-default-values)
        * [Batch register](#batch-register)
    * [Resolving dependencies](#resolving-dependencies)

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
```

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
```

### Getting instance from registered **Interface** ###

[Read more about registers here](#registers)

```php
...

 $instance = $ioc->getInstance('IClass'); //$instance is instance of concrete class registered to IClass

...
```

### Getting instance from registered **Abstract Class** ###

[Read more about registers here](#registers)

```php
...

 $instance = $ioc->getInstance('Class000'); //$instance is instance of concrete class registered to AbstractClass

...
```

### **Registers** ###

Some dependencies like Interfaces and Abstract Classes are not resolved alone and should be defined which concrete class iocContainer will use.

#### Registering concrete class to interface ####

```php
...

interface IClass {
     public function method();
 }

 class Class2 implements IClass {

     public $prop1;

     public function method() {
         //some code here
     }
 }

$ioc->register('IClass','Class2'); //Class2 must be a IClass implementation

...
```

#### Registering concrete class to Abstract Class ####

```php
...

 $ioc->register('Class000','Class0000'); //Class0000 should extend Class000

...
```

#### Registering a implementation to existing concrete class ####

```php
...

class MyDateTime extends DateTime
{
    public function __construct($object) {
        parent::__construct('1985-10-03', $object);
    }

    public function __toString() {
        return $this->format('Y-m-d H:i');
    } 
}

$ioc->register('DateTime', 'MyDateTime');

$instance = $ioc->getInstance('DateTime'); //$instance is instance of MyDateTime

...
```

#### Registering instances ####

Instance's register can help in some cases, like test context.

```php
...

$instance1 = new Class2();
$ioc->registerInstance('IClass', $instance1);
$instance2 = $ioc->getInstance('IClass');

//$instance1 === $instance2 true;
...
```

#### Defining instance's default values ####

```php
...

class Class0 {
    public $prop0;
}

$ioc->register('Class0',array('prop0'=>'test'));
$instance = $ioc->getInstance('Class0'); //$instance->prop0 has "test" as value

...
```

Using Interfaces or Abstract Class, a concrete class should be specified. Use a key *'class'* on array to do it

```php
...
$ioc->register('IClass',array(
                'class' => 'Class2',
                'prop1' => 'value1'
                ));
            
$instance1 = $ioc->getInstance('IClass'); //$instance1 is a Class2 instance and $instance1->prop1 has "value1" as value
...
```

#### Batch register ####

All registers can be setted using one line config. ** This command will clear all configured registers and instances cannot be setted here. **

```php
...

$ioc->setRegisters(
    array(
        'Class000'=>'Class0000',
        'Class0'=>array('prop0'=>'test'),
        'IClass' => array(
            'class' => 'Class2',
            'prop1' => 'value1'
        )
    )
);

...
```

### Resolving dependencies ###

IocContainer will resolve all class dependencies. Class constructor should has parameters types specified.

**Has not limit to construct parameters count.**


```php
...

class Class1 {
    
    private $_class;

    public function __construct(IClass $class) {
        $this->_class = $class;
    }
    
    public function exec()
    {
        $this->_class->method();
    }
    
    public function getClass()
    {
        return $this->_class;
    }
}

class Class10
{
    //your code here
}

class Class11
{
    public $dep;

    public function __construct(Class10 $instance)
    {
        $this->dep = $instance;
    }
}

class Class12
{
    public $dep1;
    
    public $dep2;
    
    public function __construct(Class11 $dep1, Class1 $dep2)
    {
        $this->dep1 = $dep1;
        $this->dep2 = $dep2;
    }
}

$instance = $ioc->getInstance('Class12');
//$instance->dep1 is a instance of Class11
//$instance->dep2 is a instance of Class1;
//$instane->dep1->dep is a instance of Class10
//$instance->dep2->getClass() will return a registered instance class of IClass

...
```
Unspecified parameters will receive *NULL* as value.

```php
...

class Class13
{
    public $dep = '';

    public function __construct($param)
    {
        $this->dep = $param;
    }
}

$instance = $ioc->getInstance('Class13');
//$instance->dep as null

...
```