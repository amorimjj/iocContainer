<?php

/**
 * InvalidInterfaceException
 *
 * @author jamorim
 */
namespace ioc\exceptions {
    class InvalidInstanceException extends \InvalidArgumentException 
    {
        public function __construct($object)
        {
            parent::__construct('Instance is invalid to '.$object, null, null);
        }
    }    
}
?>
