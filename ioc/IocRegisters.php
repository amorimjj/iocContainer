<?php

/**
 * IocRegister
 *
 * @author Jeferson Amorim <amorimjj@gmail.com>
 */

namespace ioc {
    
    use \ioc\helpers\validators\IocRegisterValidator;
    
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


        public function add($id, $data, $registeringInstance = null)
        {   
            $register = $this->getRegister($id);
            $register->setData($data);
            $register->instance = $registeringInstance;
            $register->validate();

            $this->_registers[$register->id] = $register;
        }
        
        public function addInstance($id, $instance)
        {
            $this->add($id, get_class($instance), $instance);
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
        
        private $_validate;

        public function __construct($id)
        {
            $this->id = $id;
            $this->_validate = new IocRegisterValidator($this);
        }

        public function setData($data)
        {
            if ( is_array($data) )
                return $this->setDataFromArray($data);

            $this->class = $data;
        }
        
        public function validate()
        {
            $this->_validate->validate();
        }
        
        public function justSettingDefaultValues()
        {
            return $this->id == $this->class;
        }
        
        protected function setClassFromArray(array &$data)
        {
            if ( ! isset($data['class']))
                return ($this->class = $this->id);
            
            $this->class = $data['class'];
            unset($data['class']);   
        }

        protected function setDataFromArray(array $data)
        {
            $this->setClassFromArray($data);
            $this->properties = $data;
        }
    }
    
}
?>
