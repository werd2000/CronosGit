<?php
require_once APP_PATH . 'Modelo.php';

/**
 * Clase Modelo Datos Laborales que extiende de la clase Modelo
 */
class Paciente_Modelos_terapiaModelo extends App_Modelo
{
    protected $_id;
    protected $_idTerapia;
    protected $_idPaciente;
    protected $_idProfesional;
    protected $_sesiones;
    protected $_observaciones;
    protected $_eliminado;

    private $_verEliminados = 0;

    /**
     * Clase constructora 
     */
    public function __construct()
    {
        parent::__construct();
    }

    
    /**
     * Obtiene un array con las terapias de un paciente
     * @return Resource 
     */
    public function getTerapias($idPaciente)
    {
        $sql = "SELECT pac.id as pac, pac.idTerapia, pac.idPaciente, pac.idProfesional, 
            pac.sesiones, pac.observaciones, terapia.id, terapia.terapia 
            FROM cronos_pacientes_terapia as pac, cronos_terapias as terapia
            where pac.idPaciente = $idPaciente AND
            pac.eliminado = $this->_verEliminados AND
            pac.idTerapia = terapia.id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
//        echo '<pre>'; print_r ($this->_db->fetchall());
        return $this->_db->fetchall();
    }

    public function getTerapia($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_pacientes_terapia where id = $id AND eliminado = '$this->_verEliminados'";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
    public function getObjTerapia($idTerapia)
    {
        $sql = 'SELECT * FROM cronos_terapias WHERE id = ' . $idTerapia;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }

            /**
     * Guarda la terapuia del paciente en la BD
     * @param array $valores
     * @return int lastInsertId 
     */
    public function insertarTerapiaPaciente(array $valores)
    {
        return $this->_db->insert('cronos_pacientes_terapia', $valores);
    }
    
    public function eliminarTerapiaPaciente($condicion)
    {
        return $this->_db->eliminar('cronos_pacientes_terapia', $condicion);
    }

    

}
