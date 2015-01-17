<?php

/**
 * Clase Modelo Personal que extiende de la clase Modelo
 */
class usuarioTareaModelo extends Modelo
{

    private $_verEliminados = false;

    /**
     * Clase constructora 
     */
    public function __construct()
    {
        parent::__construct();
        $this->_verEliminados = 0;
    }

    public function getUsuario($usuarioID)
    {
        $id = (int) $usuarioID;
        $sql = "SELECT usuarios.*, roles.role FROM conta_usuarios as usuarios, cronos_roles as roles WHERE
            usuarios.categoria = roles.id_role AND usuarios.id = $usuarioID";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
}
