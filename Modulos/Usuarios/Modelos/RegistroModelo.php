<?php

class registroModelo extends Modelo
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Verifica que el username no exista
     * @param string $usuario
     * @return Resource 
     */
    public function verificarUsuario($usuario)
    {
        $this->_db->setTipoDatos('Array');
        $this->_db->query("select id from conta_usuarios where username = '$usuario'");

        return $this->_db->fetchRow();
    }

    /**
     * Verifica que el email no exista
     * @param string $email
     * @return boolean 
     */
    public function verificarEmail($email)
    {
        $this->_db->setTipoDatos('Array');
        $this->_db->query("select id from conta_usuarios where email = '$email'");
        if ($this->_db->fetchRow()) {
            return true;
        }
        return false;
    }

    /**
     * Inserta un usuario en la BD
     * @param array $valores
     * @return type 
     */
    public function registrarUsuario(array $valores)
    {
        $random = rand(1234567890, 9999999999);
        $valores['codigo'] = $random;
        return $this->_db->insert('conta_usuarios', $valores);
    }

    /**
     * Obtiene información del usuario
     * @param int $id
     * @param int $codigo
     * @return Resource 
     */
    public function getUsuario($id, $codigo)
    {
        $this->_db->setTipoDatos('Array');
        $this->_db->query("select * from conta_usuarios where id = $id and codigo = '$codigo'");
        
        return $this->_db->fetchRow();
    }

    /**
     * Activa o desbloquea un usuario
     * @param int $id
     * @param int $codigo
     * @return AffectedRows 
     */
    public function activarUsuario($id, $codigo)
    {
        $valores = array('bloqueado'=>0);
        $condicion = "id = $id and codigo = '$codigo'";
        return $this->_db->editar('conta_usuarios',$valores, $condicion);
    }

}
