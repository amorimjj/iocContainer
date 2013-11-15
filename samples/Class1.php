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
}

?>
