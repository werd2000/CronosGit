<?php

require_once APP_PATH . 'Modelo.php';
require_once 'Personal.php';

/**
 * Clase Modelo Personal que extiende de la clase Modelo
 */
class Personal_Modelos_IndexModelo extends App_Modelo
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

    /**
     * Obtiene un array con los personal
     * @return Resource 
     */
    public function getTodoPersonal()
    {
        $sql = "SELECT * FROM cronos_personal 
            where eliminado = $this->_verEliminados ORDER BY apellidos, nombres";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return Personal::getPersonal($this->_db->fetchall());
    }

    /**
     * Obtiene algunos personal
     * @return Resource 
     */
    public function getAlgunosPersonal($inicio, $fin, $orden, $filtro, $campos = array('*'))
    {
        $this->_verEliminados = 0;
        $sql = new Sql();
        $sql->addCampos($campos);
        $sql->addFuncion('Select');
        $sql->addTable('cronos_personal AS personal');
        $sql->addOrder($orden);
        $sql->addWhere("personal.eliminado=$this->_verEliminados");
        if ($filtro != '') {
            $sql->addWhere($filtro);
        }

        if ($fin > 0) {
            $sql->addLimit($inicio, $fin);
        }
//        echo $sql;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
//        print_r($this->_db->fetchall());
//        return $this->_db->fetchall();
        return Personal::getPersonal($this->_db->fetchall());
    }

    public function getPersonal($where)
    {
        $sql = "SELECT * FROM cronos_personal WHERE $where";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
//        print_r(new Personal($this->_db->fetchRow()));
        return $this->_crearPersonal($this->_db->fetchRow());
    }
    
    private function _crearPersonal($datos)
    {
        $personal = new Personal($this->_db->fetchRow());
        return $personal;
    }

    public function getPersonalByNro_doc($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_personal where nro_doc = $id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }

    public function insertarPersonal(array $valores)
    {
        return $this->_db->insert('cronos_personal', $valores);
    }

    public function editarPersonal(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_personal', $valores, $condicion);
    }

    public function eliminarPersonal($condicion)
    {
        return $this->_db->eliminar('cronos_personal', $condicion);
    }

    public function getPacientesPersonal($idProfesional)
    {
        $lista = NULL;
        $this->_verEliminados = 0;
        $sql = "select * from cronos_pacientes_terapia where idProfesional = $idProfesional";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $todos = $this->_db->fetchall();
        require_once BASE_PATH . 'Modulos' . DS . 'Pacientes' . DS . 'Modelos' . DS . 'Paciente.php';
        foreach ($todos as $pac) {
            $sql2 = "select * from cronos_pacientes where id = " . $pac['idPaciente'];
            $this->_db->setTipoDatos('Array');
            $this->_db->query($sql);
            $paciente = $this->_db->fetchrow();
            $lista[] = new Paciente($paciente);
        }
        return $lista;
    }
    
    

}
