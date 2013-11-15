<?php

/**
 * InvalidInterfaceException
 *
 * @author jamorim
 */
namespace ioc\exceptions {

    class InvalidClassException extends \InvalidArgumentException 
    {
        public function __construct($className)
        {
            parent::__construct('Invalid class: ' . $className, null, null);
        }
    }
}
?>
