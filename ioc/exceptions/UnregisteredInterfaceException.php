<?php

/**
 * UnregisteredInterfaceException
 *
 * @author jamorim
 */
namespace ioc\exceptions
{
    class UnregisteredInterfaceException extends \InvalidArgumentException
    {
        public function __construct($interfaceName)
        {
            parent::__construct('Interface '.$interfaceName.' was not registered', null, null);
        }
    }    
}
?>