<?php

namespace ioc\helpers\validators
{
    /**
     * IocRegisterValidator
     *
     * @author Jeferson Amorim <amorimjj@gmail.com>
     */
    class IocRegisterValidator
    {   
        private $_register;

        public function __construct(\ioc\IocRegister $register)
        {
            $this->_register = $register;
        }

        protected function registeringToInterface()
        {
            return IocValidators::isInterface($this->_register->id);
        }

        protected function validateInstance()
        {
            if ( ! IocValidators::isInstanceValidToObject($this->_register->id, $this->_register->instance) )
                throw new \ioc\exceptions\InvalidInstanceException($this->_register->id);
        }

        protected function validateInterfaceRegister()
        {
            if ( !IocValidators::isInterface($this->_register->id))
                throw new \ioc\exceptions\InvalidInterfaceException($this->_register->id);

            if ( !IocValidators::isInterfaceImplementedByClass($this->_register->id, $this->_register->class) )
                throw new \ioc\exceptions\InvalidClassToInterfaceException($this->_register->class, $this->_register->id);
        }

        protected function validateClassRegister()
        {
            if ( !IocValidators::isValidClass($this->_register->id))
                throw new \ioc\exceptions\InvalidClassException($this->_register->id);

            if ( ! IocValidators::isClassParentOfClass($this->_register->id, $this->_register->class) )
                throw new \ioc\exceptions\InvalidClassException($this->_register->class . ' to ' .  $this->_register->id);
        }

        public function validate()
        {
            if ( $this->_register->instance !== null )
                return $this->validateInstance();

            if ( !IocValidators::isValidClass($this->_register->class))
                throw new \ioc\exceptions\InvalidClassException($this->_register->class);

            if ( $this->registeringToInterface() )
                return $this->validateInterfaceRegister();

            return $this->validateClassRegister();
        }
    }

}
?>
