<?php

namespace test\test2
{
    /**
     * Class4
     *
     * @author Jeferson Amorim <amorimjj@gmail.com>
     */
    class Class4 {
        
        public $dep;
        
        public function __construct(\test\test1\Class3 $dep) {
            $this->dep = $dep;
        }
    }    
}

?>
