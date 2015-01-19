<?php

class Usuarios_Modelos_loginModelo extends App_Modelo
{
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Obtien los datos del usuario de la BD
     * @param strin $usuario
     * @param string $password
     * @return array 
     */
    public function getUsuario($usuario, $password)
    {
        $sql = "select * from conta_usuarios where username = '$usuario' " .
                "and password = '" . LibQ_Hash::getHash('sha1',$password,HASH_KEY) ."'";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
}