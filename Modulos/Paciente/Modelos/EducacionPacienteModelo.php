<?php

//require_once BASE_PATH . 'LibQ' . DS . 'bd' . DS . 'Sql.php';
require_once APP_PATH . 'Modelo.php';

/**
 * Clase Modelo Datos Laborales que extiende de la clase Modelo
 */
class Paciente_Modelos_educacionPacienteModelo extends App_Modelo
{

    private $_verEliminados = 0;

    /**
     * Clase constructora 
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function getEducacionPaciente($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_educacion_paciente where id_paciente = $id AND eliminado = '$this->_verEliminados'";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
    /**
     * Modifica los datos de la OS de un paciente
     * @return AfectedRows 
     */
    public function editarEducacionPaciente(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_educacion_paciente', $valores, $condicion);
    }
    

    /**
     * Guarda datos de la OS del Pac en la BD
     * @param array $valores
     * @return int lastInsertId 
     */
    public function insertarEducacionPaciente(array $valores)
    {
        return $this->_db->insert('cronos_educacion_paciente', $valores);
    }
    
    public function eliminarEducacionPaciente($condicion)
    {
        return $this->_db->eliminar('cronos_educacion_paciente', $condicion);
    }    

}
