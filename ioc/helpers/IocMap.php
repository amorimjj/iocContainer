<?php

/**
 * IocMap
 *
 * @author Jeferson Amorim <amorimjj@gmail.com>
 */
namespace ioc\helpers {

    class IocMap {

        public static function register(array $registerData)
        {
            $registers = new \ioc\IocRegisters();
            foreach ($registerData as $id => $data)
                $registers->add($id, $data);

            return $registers;
        }
    }
}
?>
