<?php

require_once APP_PATH . 'Modelo.php';

/**
 * Clase Modelo Datos Laborales que extiende de la clase Modelo
 */
class planTratamientoModelo extends App_Modelo
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
     * Obtiene un array con los datos de los plan de tratamiento del paciente
     * @return Resource 
     */
    public function getPlanesTratamiento(int $idPaciente)
    {
        $sql = "select * from cronos_planes_tratamientos where idPaciente = $idPaciente AND eliminado = $this->_verEliminados";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }

    /**
     * Obtiene un array con los datos de un plan de tratamiento
     * @return Resource 
     */
    public function getPlanTratamiento($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_planes_tratamientos where id = $id AND eliminado = '$this->_verEliminados'";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
    /**
     * Obtiene un array con los datos de un plan de tratamiento actual de un paciente
     * @return Resource 
     */
    public function getPlanTratamientoAnio($idPaciente, $anio)
    {
        $idPaciente = (int) $idPaciente;
        $sql = "select * from cronos_planes_tratamientos where idPaciente = $idPaciente
            AND eliminado = '$this->_verEliminados' AND fecha = $anio";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
    /**
     * Modifica los datos de la OS de un paciente
     * @return AfectedRows 
     */
    public function editarPlanTratamiento(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_planes_tratamientos', $valores, $condicion);
    }
    

    /**
     * Guarda datos de la OS del Pac en la BD
     * @param array $valores
     * @return int lastInsertId 
     */
    public function insertarPlanTratamiento(array $valores)
    {
        return $this->_db->insert('cronos_planes_tratamientos', $valores);
    }
    
    public function eliminarPlanTratamiento($condicion)
    {
        return $this->_db->eliminar('cronos_planes_tratamientos', $condicion);
    }

    

}
