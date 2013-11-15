<?php
/**
 * ClassTest3
 *
 * @author jamorim
 */
class ClassTest3
{
    private $_test;
    
    public function __construct(ITest $test)
    {
       $this->_test = $test;
    }
}

?>
