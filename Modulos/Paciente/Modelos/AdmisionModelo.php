<?php

//require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'Sql.php';
require_once APP_PATH . 'Modelo.php';

/**
 * Clase Modelo Paciente que extiende de la clase Modelo
 */
class admisionModelo extends Modelo
{

    private $_verEliminados = false;

    /**
     * Clase constructora 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene un array con los pacientes
     * @return Resource 
     */
    public function getPacientes()
    {
        $sql = 'SELECT * FROM cronos_admision_pacientes';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }

    /**
     * Obtiene algunos pacientes
     * @return Resource 
     */
    public function getAlgunosPacientes($inicio, $fin, $orden, $filtro, $campos = array('*'))
    {
        $this->_verEliminados = 0;
        $sql = new Sql();
        $sql->addCampos($campos);
        $sql->addFuncion('Select');
        $sql->addTable('cronos_pacientes AS pacientes');
        $sql->addOrder($orden);
        $sql->addWhere("pacientes.eliminado=$this->_verEliminados");
        if (is_object($filtro)) {
            $sql->addWhere($filtro->__toString());
        }

        if ($fin > 0) {
            $sql->addLimit($inicio, $fin);
        }
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }
    
    /**
     * Obtiene los datos de un paciente
     * @return Resource 
     */
    public function getPaciente($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_admision_pacientes where id = $id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }

    public function insertarPaciente(array $valores)
    {
        return $this->_db->insert('cronos_admision_pacientes', $valores);
    }

    public function editarPaciente(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_admision_pacientes', $valores, $condicion);
    }

    public function eliminarPaciente($condicion)
    {
        return $this->_db->eliminar('cronos_admision_pacientes', $condicion);
    }

}
