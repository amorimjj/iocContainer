<?php
/**
 * IocValidators is the validator class
 *
 * @author Jeferson Amorim <amorimjj@gmail.com>
 */

namespace ioc {

    class IocValidators
    {
        /**
         * @param string $interfaceName interface name to check
         * @return boolean
         */
        public static function isInterface($interfaceName)
        {
            return @interface_exists($interfaceName);
        }

        /**
         * @param type $className class name to check
         * @return boolean
         */
        public static function isValidClass($className)
        {
            return @class_exists($className);
        }

        /**
         *
         * @param string $interfaceName
         * @param string $className
         * @return boolean 
         */
        public static function isInterfaceImplementedByClass($interfaceName, $className)
        {
            $class = new \ReflectionClass($className);
            return $class->implementsInterface($interfaceName);
        }

        /**
         *
         * @param string $object Interface or class to register
         * @param object $instance Instance of Interface or class informed
         * @return boolean 
         */
        public static function isInstanceValidToObject($object, $instance)
        {
            return ($instance instanceof $object);
        }

        /**
         * @param array $registers
         * @return boolean 
         */
        public static function isValidRegister($registers)
        {
            return is_array($registers) && self::validateSecondParameterToRegister($registers);
        }

        protected static function validateSecondParameterToRegister($registers)
        {
            if ( is_array($param = reset($registers)) && ! array_key_exists('class', $param) )
                throw new \InvalidArgumentException('Class parameter should has a \'class\' key');

            return true;
        }

        /**
         * @param string $namespace
         * @return boolean
         */
        /*public static function isValidYiiNamespace($namespace)
        {
            return  ! (strpos($namespace, '.') === false );
        }*/

    }
}
?>
