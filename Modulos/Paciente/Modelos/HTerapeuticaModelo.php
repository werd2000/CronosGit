<?php
require_once APP_PATH . 'Modelo.php';

/**
 * Clase Modelo Datos Laborales que extiende de la clase Modelo
 */
class Paciente_Modelos_hTerapeuticaModelo extends App_Modelo
{
    protected $_id;
    protected $_idPaciente;
    protected $_idProfesional;
    protected $_tipo_informe;
    protected $_fechaObservacion;
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
    public function getHTerapeutica($idPaciente)
    {
//        echo $idPaciente;
        $sql = "SELECT *
            FROM cronos_hterapeutica
            where idPaciente = $idPaciente AND
            eliminado = $this->_verEliminados";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $resultado = $this->_db->fetchall();
//        echo '<pre>'; print_r ($resultado);
        return $resultado;
    }
    
    /**
     * Obtiene un array con las terapias de un paciente
     * @return Resource 
     */
    public function getHTerapeuticaId($id)
    {
        $sql = "SELECT cronos_hterapeutica.*, cronos_pacientes.apellidos,
            cronos_pacientes.nombres, cronos_pacientes.fecha_nac,
            cronos_pacientes.diagnostico, cronos_rrhh_datos_laborales.puesto,
            cronos_terapias.terapia, cronos_terapias.id
            FROM cronos_hterapeutica, cronos_pacientes, cronos_terapias, cronos_rrhh_datos_laborales
            WHERE cronos_hterapeutica.id = $id AND
            cronos_terapias.id = cronos_rrhh_datos_laborales.puesto AND
            cronos_rrhh_datos_laborales.idProfesional = cronos_hterapeutica.idProfesional AND
            cronos_hterapeutica.idPaciente = cronos_pacientes.id AND
            cronos_hterapeutica.eliminado = $this->_verEliminados";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $resultado = $this->_db->fetchRow();
//        echo '<pre>'; print_r ($resultado);
        return $resultado;
    }

     /**
     * Guarda la terapuia del paciente en la BD
     * @param array $valores
     * @return int lastInsertId 
     */
    public function insertarHTerapeutica(array $valores)
    {
        return $this->_db->insert('cronos_hterapeutica', $valores);
    }
    
    public function eliminarTerapiaPaciente($condicion)
    {
        return $this->_db->eliminar('cronos_pacientes_terapia', $condicion);
    }

    

}
