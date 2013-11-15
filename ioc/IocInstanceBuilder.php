<?php

/**
 * IocInstanceBuilder
 *
 * @author Jeferson Amorim <amorimjj@gmail.com>
 */

namespace ioc {
    
    class IocInstanceBuilder {

        /**
         * @var IocRegisters 
         */
        public $registers;

        public function __construct() {
            $this->registers = new IocRegisters();
        }

        public function getClassTo($interfaceName)
        {
            return $this->registers->get($interfaceName)->class;
        }

        protected function getInstanceToInterface($interfaceName)
        {
            $className = $this->getClassTo($interfaceName);
            return $this->buildInstance($className);
        }

        protected function buildParameterArgument(\ReflectionParameter $parameter)
        {
            if ( !$parameter->getClass() )
                return null;

            return $this->getInstance($parameter->getClass()->name);
        }

        protected function getConstructorArguments(\ReflectionMethod $constructor)
        {
            $constructorArguments = array();

            foreach ($constructor->getParameters() as $parameter)
                $constructorArguments[] = $this->buildParameterArgument($parameter);

            return $constructorArguments;
        }

        protected function getInstanceToClass($className)
        {
            if ( !IocValidators::isValidClass($className))
                throw new exceptions\InvalidClassException($className);

            $class = new \ReflectionClass($className);

            if ( ! $constructor = $class->getConstructor() )
                return $class->newInstance();

            return $class->newInstanceArgs($this->getConstructorArguments($constructor));
        }

        protected function getParametersToInstance($instance)
        {
            if ( ($register = $this->registers->getByInstance($instance))  )
                    return $register->properties;

            return array();
        }

        protected function setInstanceProperty($instance, $property, $value)
        {
           if ( ! property_exists($instance, $property ) )
               throw new \InvalidArgumentException('Class \'' .  get_class($instance) . '\' doesn\'t have a property called \''. $property . '\'');

           $instance->{$property} = $value;
        }


        protected function startParameters($instance)
        {
            foreach( $this->getParametersToInstance($instance) as $key => $value )
                $this->setInstanceProperty($instance, $key, $value);

            return $instance;
        }

        protected function buildInstance($object)
        {
            $instance = $this->getInstanceToClass($object);
            $this->startParameters($instance);
            return $instance;
        }


        public function getInstance($object)
        {
            if ( $this->registers->hasRegisteredInstance($object) )
                return $this->registers->get($object)->instance;

            if ( IocValidators::isInterface($object) )
                return $this->getInstanceToInterface($object);

           return $this->buildInstance($object);

        }

    }
}
?>
