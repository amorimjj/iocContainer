<?php

require dirname(__FILE__) . '/../../ioc/IocContainer.php';
require 'fakes/ITest.php';
require 'fakes/SubjectFake.php';
require 'fakes/ClassTest1.php';
require 'fakes/ClassTest2.php';
require 'fakes/ClassTest3.php';
require 'fakes/ClassTest4.php';
require 'fakes/ClassTest5.php';
require 'fakes/ClassTest6.php';
require 'fakes/ClassTest7.php';
require 'fakes/ITest2.php';
require 'fakes/ClassTest8.php';

class IocContainerTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var IocContainer 
     */
    private $_ioc;
    
    public function setup()
    {
        $this->_ioc = \ioc\IocContainer::getContainer();
        $this->_ioc->reset();
    }
    
    public function testRegister_WhenTryRegisterAClassToAnInvalidInterface_ShouldThrowInvalidInterfaceException() {
        $this->setExpectedException('\ioc\exceptions\InvalidInterfaceException', 'Invalid interface: IInvalid');
        $this->_ioc->register('IInvalid', 'Class');
    }
    
    public function testRegister_WhenTryRegisterAClassToAValidInterfaceButClassIsInvalid_ShouldThrowInvalidClassException() {
        $this->setExpectedException('\ioc\exceptions\InvalidClassException', 'Invalid class: Class');
        $this->_ioc->register('SplSubject', 'Class');
    }
    
    public function testRegister_WhenTryRegisterAClassToAValidInterfaceButClassDoesntImplementsIt_ShouldThrowInvalidClassToInterfaceException()
    {
        $this->setExpectedException('\ioc\exceptions\InvalidClassToInterfaceException', 'Class \ioc\IocValidators is invalid to interface SplSubject');
        $this->_ioc->register('SplSubject', '\ioc\IocValidators');
    }
    
    public function testRegister_WhenTryRegisterAClassToAValidInterfaceWhenImplementsIt_CantThrowException()
    {
        $this->_ioc->register('SplSubject', 'SubjectFake');
    }
    
    public function testRegister_WhenTryRegisterAClassToAValidInterfaceWhenImplementsIt_ShouldRegisterClassToInteface()
    {
        $this->_ioc->register('SplSubject', 'SubjectFake');
        $this->assertEquals('SubjectFake', $this->_ioc->getClassTo('SplSubject'));
    }
    
    public function testGetClassTo_WhenTryRetrieveANotRegisteredInterface_ShouldThrowUnregisteredInterfaceException()
    {
        $this->setExpectedException('\ioc\exceptions\UnregisteredInterfaceException', 'Interface SplObject was not registered');
        $this->_ioc->getClassTo('SplObject');
    }
    
    public function testGetInstance_WhenTryRetrieveAInstanceForAUnregisteredInterface_ShouldThrowUnregisteredInterfaceException()
    {
        $this->setExpectedException('\ioc\exceptions\UnregisteredInterfaceException','Interface SplObserver was not registered');
        $this->_ioc->getInstance('SplObserver');
    }
    
    public function testGetInstance_WhenTryRetrieveAInstanceForARegisteredInterface_ShouldReturnASolicitedInstance()
    {
        $this->_ioc->register('SplSubject', 'SubjectFake');
        $this->assertInstanceOf('SubjectFake', $this->_ioc->getInstance('SplSubject'));
    }
    
    public function testGetInstance_WhenTryRetrieveAnInstaceToAnInvalidClass_ShouldThrowInvalidClassException()
    {
        $this->setExpectedException('\ioc\exceptions\InvalidClassException', 'Invalid class: Class');
        $this->_ioc->getInstance('Class');
    }
    
    public function testGetInstance_WhenTryRetreiveAnInstanceToAValidClass_ShouldReturnAClassInstance()
    {
        $this->assertInstanceOf('ClassTest1', $this->_ioc->getInstance('ClassTest1'));
    }
    
    public function testGetInstance_WhenTryRetreiveAnInstanceToAValidClassWitchNeedParametersOnConstructor_ShouldReturnAClassInstance()
    {
        $this->_ioc->register('ITest', 'ClassTest2');
        $this->assertInstanceOf('ClassTest3', $this->_ioc->getInstance('ClassTest3'));
    }
    
    public function testGetInstance_WhenTryRetreiveAnInstanceToAValidClass_ShouldReturnAClassInstance1()
    {
        $this->assertInstanceOf('ClassTest4', $this->_ioc->getInstance('ClassTest4'));
    }
    
    public function testGetInstance_WhenTryRetreiveAnInstanceToAValidClass_ShouldReturnAClassInstance2()
    {
        $this->_ioc->register('ITest', 'ClassTest6');
        $this->assertInstanceOf('ClassTest5', $this->_ioc->getInstance('ClassTest5'));
    }
    
    public function testRegisterInstance_WhenTryRegisterAnInstanceAndInstanceNotTypeOfObject_ShouldThrowInvalidInstance()
    {
        $this->setExpectedException('\ioc\exceptions\InvalidInstanceException', 'Instance is invalid to ClassTest5');
        $instance = new ClassTest1();
        $this->_ioc->registerInstance('ClassTest5', $instance);
    }
    
    public function testRegisterInstance_WhenTryRegisterAValidInstanceAndInstance_ShouldRegisterInstance()
    {
        $instance = new ClassTest2();
        $this->_ioc->registerInstance('ITest', $instance);
        $this->assertSame($instance, $this->_ioc->getInstance('ITest'));
    }
    
    public function testRegisterInstance_WhenTryRegisterAValidInstanceAndInstance_ShouldRegisterInstance1()
    {
        $instance = new ClassTest7();
        $this->_ioc->registerInstance('ClassTest1', $instance);
        $this->assertSame($instance, $this->_ioc->getInstance('ClassTest1'));
    }
    
    public function testRegisterInstance_WhenTryRegisterAValidInstanceAndInstance_ShouldRegisterInstance2()
    {
        $instance = new ClassTest1();
        $this->_ioc->registerInstance('ClassTest1', $instance);
        $this->assertSame($instance, $this->_ioc->getInstance('ClassTest1'));
    }
    
    public function testSetRegisters_WhenSetInvalidRegisters_ShouldThrowException()
    {
        $this->setExpectedException('InvalidArgumentException', 'Argument registers should be an array');
        $this->_ioc->setRegisters(null);
    }
    
    public function testSetRgisters_WhenSetValidRegisters_CantCallRegisterUntilTryRetrieveInstance()
    {
        $ioc = $this->getMockBuilder('IocContainer')
                ->disableOriginalConstructor()
                ->setMethods(array('register'))
                ->getMock();
        
        $ioc->expects($this->never())
                ->method('register');
        
        $this->_ioc->setRegisters(array('ITest', 'ClassTest6'));
    }
    
    public function testGetSingletonInstance_WhenTryGetSingletonInstance_ShouldReturnAlwaysTheSameInstance()
    {
        $this->_ioc->register('ITest', 'ClassTest2');
        $instance1 = $this->_ioc->getSingletonInstance('ClassTest3');
        $instance2 = $this->_ioc->getSingletonInstance('ClassTest3');
        
        $this->assertSame($instance1, $instance2);
    }
    
    public function testGetInstance_WhenTryGetSingletonAndNewInstance_ShouldReturnSingletonToSingletonAndNewToNew()
    {
        $this->_ioc->register('ITest', 'ClassTest2');
        $instance1 = $this->_ioc->getSingletonInstance('ClassTest3');
        $instance2 = $this->_ioc->getInstance('ClassTest3');
        $instance3 = $this->_ioc->getSingletonInstance('ClassTest3');
        
        $this->assertNotSame($instance1, $instance2);
        $this->assertSame($instance1, $instance3);
    }
    
    public function testSetRegisters_WhenITryRegisterAInstanceToClass_ShouldRegisterInstance()
    {
        $this->_ioc->setRegisters(array('Class2'=>'Class3'));
        $this->_ioc->getInstance('Class2');
    }
            
    public function testGetInstance_WhenTryGetAInstanceForARegisteredClass_ShouldReturnRegisteredClass()
    {
        $this->_ioc->setRegisters(array('Class2'=>'Class3'));
        $this->assertInstanceOf('Class3', $this->_ioc->getInstance('Class2'));
    }

    public function testGetInstance_WhenNewInstanceThatDependsFromARegisteredInstance_ShouldReceiveARegisteredInstance()
    {
        $instance3 = new Class3();
        $this->_ioc->registerInstance('Class2', $instance3);
        $instance1 = $this->_ioc->getInstance('Class1');
        $instance2 = $this->_ioc->getInstance('Class2');
        $this->assertSame($instance1->class, $instance2);
    }
    
    public function testSetRegisters_WhenTrySetANewRegister_SecondParamentCouldBeAnArray()
    {
        $this->_ioc->setRegisters(array('Class2'=>array('class'=>'Class4')));
        $this->assertInstanceOf('Class4', $this->_ioc->getInstance('Class2'));
    }
    
    public function testSetRegisters_WhenTrySetANewRegisterAndSecondParameterIsAnArray_SecondParamentShouldHasAKeyClass()
    {
        $this->setExpectedException('InvalidArgumentException', 'Class parameter should has a \'class\' key');
        $this->_ioc->setRegisters(array('Class2'=>array('erro'=>'Class4')));
    }
    
    public function testSetRegisters_WhenTrySetANewRegisterAndSecondParameterIsAnArray_ShouldSetParametersValue()
    {
        $this->_ioc->setRegisters(array('Class2'=>array('class'=>'Class4','prop1'=>'value1')));
        $instance1 = $this->_ioc->getInstance('Class2');
        $this->assertEquals('value1', $instance1->prop1);
    }
    
    public function testSetRegisters_WhenTrySetANewRegisterAndSecondParameterIsAnArrayAndParameterToSetIsInvalid_ShouldThrowException()
    {
        $this->setExpectedException('InvalidArgumentException', 'Class \'Class4\' doesn\'t have a property called \'prop2\'');
        $this->_ioc->setRegisters(array('Class2'=>array('class'=>'Class4','prop2'=>'value1')));
        $this->_ioc->getInstance('Class2');
    }
    
    public function testSetRegisters_WhenTrySetANewRegisterToNamespacedClassAndSecondParameterIsAnArray_ShouldSetParametersValue()
    {
        $this->_ioc->setRegisters(array('test\name_space\ITest2'=>array('class'=>'test\name_space\ClassTest8','prop1'=>'value1')));
        $instance1 = $this->_ioc->getInstance('test\name_space\ITest2');
        $this->assertEquals('value1', $instance1->prop1);
    }
}

class Class1 {
    public $class;
    
    public function __construct(Class2 $class) {
        $this->class = $class;
    }
}

class Class2 {
       
}

class Class3 extends Class2 {
    
}

class Class4 extends Class2 {
    public $prop1 = null;    
}

?>
