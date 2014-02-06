<?php

/**
 * Class1
 *
 * @author Jeferson Amorim <amorimjj@gmail.com>
 */
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
?>
