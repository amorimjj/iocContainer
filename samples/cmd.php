<?php

    require '../ioc/IocContainer.php';
    require 'IClass.php';
    require 'Class1.php';
    require 'Class2.php';
    
    $ioc = IocContainer::getContainer();
    //$ioc->init();
    $ioc->setRegisters(array('IClass'=>'Class2'));
    
    $class1 = $ioc->getInstance('Class1');
    
    $class1->exec();
    
?>
