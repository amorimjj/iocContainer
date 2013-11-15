<?php

/**
 * IocContainer is base class to inversion of control
 *
 * @author Jeferson Amorim <amorimjj@gmail.com>
 */

namespace ioc {
    
    require_once 'IocInstanceBuilder.php';
    require_once 'IocValidators.php';
    require_once 'IocRegisters.php';
    require_once 'IocMap.php';
    require_once 'exceptions/InvalidClassException.php';
    require_once 'exceptions/InvalidClassToInterfaceException.php';
    require_once 'exceptions/InvalidInstanceException.php';
    require_once 'exceptions/InvalidInterfaceException.php';
    require_once 'exceptions/UnregisteredInterfaceException.php';

    class IocContainer
    {
        private static $_container;

        /**
         * Builder of instances
         * @var IocInstanceBuilder 
         */
        private $_instanceBuilder;

        private $_singletonInstances = array();

        private $_extenalLoaderFunction = null;

        /**
         * 
         * @param string $interfaceName Name of interface
         * @param string $className Name of class
         * @throws InvalidInterfaceException
         * @throws InvalidClassException
         * @throws InvalidClassToInterfaceException
         */
        protected function validateRegister($interfaceName, $className)
        {
            if ( !IocValidators::isInterface($interfaceName))
                throw new exceptions\InvalidInterfaceException($interfaceName);

            if ( !IocValidators::isValidClass($className))
                throw new exceptions\InvalidClassException($className);

            if ( !IocValidators::isInterfaceImplementedByClass($interfaceName, $className) )
                throw new exceptions\InvalidClassToInterfaceException($className, $interfaceName);
        }

        protected function registerFromInitIfFound($object)
        {
            if ( ! $this->_instanceBuilder->registers->hasRegisterTo($object) ||  $this->_instanceBuilder->registers->hasRegisteredInstance($object) )
                 return;

            $completeClassName = $this->_instanceBuilder->registers->get($object)->class;

            if ( IocValidators::isInterface($object))
                return $this->register($object, $this->classLoader($completeClassName));

            return $this->registerInstance($object, $this->getInstance($this->classLoader($completeClassName)));  
        }

        protected function getInstanceFromRegisters($object)
        {
            $this->registerFromInitIfFound($object);

            if ( $this->_instanceBuilder->registers->hasRegisteredInstance($object) )
                return $this->_instanceBuilder->registers->get($object)->instance;
        }

        protected function classLoader($className)
        {
            if ( $this->_extenalLoaderFunction !== null )
                return call_user_func($this->_extenalLoaderFunction, $className);

            return $className;
        }

        public function register($interfaceName, $className)
        {
            $this->validateRegister($interfaceName, $className);
            $this->_instanceBuilder->registers->add($interfaceName, $className);
        }

        public function registerInstance($object, $instance)
        {
            $this->_instanceBuilder->registers->addInstance($object, $instance);
        }

        public function getClassTo($interfaceName)
        {
            return $this->_instanceBuilder->registers->get($interfaceName)->class;
        }

        public function getInstance($object)
        {
            if ( ($instance = $this->getInstanceFromRegisters($object)) )
                return $instance;

           return $this->_instanceBuilder->getInstance($object);

        }

        public function getSingletonInstance($object)
        {
            if ( ! isset($this->_singletonInstances[$object]))
                $this->_singletonInstances[$object] = $this->getInstance($object);

            return $this->_singletonInstances[$object];

        }

        public function setRegisters($registers)
        {
            if (!IocValidators::isValidRegister($registers))
                throw new \InvalidArgumentException('Argument registers should be an array');

            $this->_instanceBuilder->registers = IocMap::register($registers);
        }

        public function setExternalLoaderClass($externalLoader)
        {
            if ( ! is_callable($externalLoader) )
                throw new \InvalidArgumentException('External loader should be callable');

            $this->_extenalLoaderFunction = $externalLoader;
        }

        protected function __construct() {
            $this->start();
        }

        protected function start()
        {
            $this->_instanceBuilder = new IocInstanceBuilder();
            $this->_registers = array();
            $this->_registeredInstances = array();
            $this->_singletonInstances = array();  
        }

        public static function getContainer()
        {
            if ( self::$_container === null )
                    self::$_container = new IocContainer();

            return self::$_container;
        }

        public function reset()
        {
            $this->start();
        }
    }    
}

?>
