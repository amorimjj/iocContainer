<?php

    require '../ioc/IocContainer.php';
    require 'IClass.php';
    require 'Class0.php';
    require 'Class1.php';
    require 'Class2.php';
    require 'Class3.php';
    require 'Class4.php';
    
    
    //Use
        //Getting ioc's container instance

         $ioc = \ioc\IocContainer::getContainer();
         
        //Registers
            //Registering concrete class to interface
            $ioc->setRegisters(array('IClass'=>'Class2'));
            
            //Registering child class to parent class
             $ioc->setRegisters(array('Class000'=>'Class0000'));
                         
            //Registering instances
            $instance1 = new Class2();
            
            $ioc->registerInstance('IClass', $instance1);
            $instance2 = $ioc->getInstance('IClass');
            
            //$instance1 === $instance2 true;
            
            //Defining default values            
            $ioc->register('Class0',array('prop0'=>'test'));
            
            $instance = $ioc->getInstance('Class0'); //$instance->prop0 has "test" as value
            
            $ioc->setRegisters(array('IClass'=>array(
                'class' => 'Class2',
                'prop1' => 'value1'
                )));
            
            $instance1 = $ioc->getInstance('IClass'); //$instance1->prop1 has "value1" as value
        
        //Getting instance from concrete class
        $instance = $ioc->getInstance('Class0'); // $intance is instance of Class0
        
        $instance = $ioc->getInstance('\test\test2\Class4');
        
        //Getting singleton instance
        $instance1 = $ioc->getSingletonInstance('Class0');
        $instance2 = $ioc->getSingletonInstance('Class0');
        $instance3 = $ioc->getInstance('Class0');
        
        //$instance1 === $instance2 true;
        //$instance1 === $instance3 false;
        
        //Getting instance from registered Interface
        $instance1 = $ioc->getInstance('IClass'); //$instance1 is instance of concrete class registered to IClass
       
        //$ioc->setRegisters(array('Class0'=>'Class00'));
        
        //$c = $ioc->getInstance('Class0');
        
        //Solving dependencies
        //You must set in constructor dependecies types
        /*
         * class Class10
         * {
         *    //your code here
         * }
         * 
         * class Class11
         * {
         *  public $dep;
         * 
         *  public function __construct(Class10 $instance)
         *  {
         *      $this->dep = $instance;
         *  }
         * }
         * 
         * class Class12
         * {
         *  public $dep1;
         *  public $dep2;
         *  
         *  public function __construct(Class11 $dep1, Class1 $dep2)
         *  {
         *   $this->dep1 = $dep1;
         *   $this->dep2 = $dep2;
         *  }
         * }
         */
        
         $instance = $ioc->getInstance('Class12');
         //$instance->dep1 is a instance of Class11
         //$instance->dep2 is a instance of Class1;
         //$instane->dep1->dep is a instance of Class10
         //$instance->dep2->getClass() will return a registered instance class of IClass
         
         
         $instance = $ioc->getInstance('Class1'); //$instance->getClass() will return IClass registered instance;
         
         

        //Reseting container
         $ioc->reset();
        
?>
