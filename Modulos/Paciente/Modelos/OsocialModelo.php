<?php

//require_once BASE_PATH . 'LibQ' . DS . 'bd' . DS . 'Sql.php';
require_once APP_PATH . 'Modelo.php';

/**
 * Clase Modelo Datos Laborales que extiende de la clase Modelo
 */
class Paciente_Modelos_oSocialModelo extends App_Modelo
{

    private $_verEliminados = 0;

    /**
     * Clase constructora 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene un array con los datos de la OS de un paciente
     * @return Resource 
     */
    public function getOSociales()
    {
        $sql = "select * from cronos_obrassociales where eliminado = $this->_verEliminados";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }

    public function getOSocialPaciente($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_paciente_os where idPaciente = $id AND eliminado = '$this->_verEliminados'";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
    /**
     * Modifica los datos de la OS de un paciente
     * @return AfectedRows 
     */
    public function editarOSocialPaciente(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_paciente_os', $valores, $condicion);
    }
    

    /**
     * Guarda datos de la OS del Pac en la BD
     * @param array $valores
     * @return int lastInsertId 
     */
    public function insertarOSocialPaciente(array $valores)
    {
        return $this->_db->insert('cronos_paciente_os', $valores);
    }
    
    public function eliminarOSocialPaciente($condicion)
    {
        return $this->_db->eliminar('cronos_paciente_os', $condicion);
    }

    

}
