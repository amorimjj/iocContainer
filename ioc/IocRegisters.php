<?php

/**
 * IocRegister
 *
 * @author Jeferson Amorim <amorimjj@gmail.com>
 */

namespace ioc {
    
    class IocRegisters {

        /**
         * @var IocRegister[] 
         */
        private $_registers = array();


        /**
         * @param string $id
         * @return \IocRegister
         */
        protected function getRegister($id)
        {
            if ( isset($this->_registers[$id] ) )
                return $this->_registers[$id];

            return new IocRegister($id);

        }


        public function add($id, $data)
        {
            $register = $this->getRegister($id);
            $register->setData($data);

            $this->_registers[$register->id] = $register;
        }

        public function addInstance($id, $instance)
        {
            if ( ! IocValidators::isInstanceValidToObject($id, $instance) )
                    throw new exceptions\InvalidInstanceException($id);

            $this->add($id, get_class($instance));
            $register = $this->getRegister($id);
            $register->instance = $instance;
        }

        public function hasRegisteredInstance($id)
        {
            return isset($this->_registers[$id]->instance);
        }

        public function hasRegisterTo($objectName)
        {
            return isset($this->_registers[$objectName]);
        }

        /**
         * 
         * @param string $id
         * @return IocRegister
         */
        public function get($id)
        {
            if ( ! isset($this->_registers[$id] ) )
                throw new exceptions\UnregisteredInterfaceException($id);

            return $this->_registers[$id];
        }

        public function getByInstance($instance)
        {
            foreach ($this->_registers as $register)
            {
                if ($register->class == get_class($instance) )
                    return $register;
            }
        }
    }

    class IocRegister {
        public $id;
        public $class;
        public $instance;
        public $properties = array();

        public function __construct($id)
        {
            $this->id = $id;
        }

        public function setData($data)
        {
            if ( is_array($data) )
                return $this->setDataFromArray($data);

            $this->class = $data;
        }

        protected function setDataFromArray(array $data)
        {
            $this->class = $data['class'];
            unset($data['class']);
            $this->properties = $data;
        }
    }
}
?>
