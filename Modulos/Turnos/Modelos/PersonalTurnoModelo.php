<?php

require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'Sql.php';
require_once APP_PATH . 'Modelo.php';

/**
 * Clase Modelo Personal que extiende de la clase Modelo
 */
class personalTurnosModelo extends Modelo
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
        return $this->_db->fetchall();
    }

    /**
     * Obtiene algunos personal
     * @return Resource 
     */
    public function getAlgunosPersonal($inicio=false, $fin=false, $orden=false, $filtro=false, $campos = array('*'))
    {
        $this->_verEliminados = 0;
        $sql = new Sql();
        $sql->addCampos($campos);
        $sql->addFuncion('Select');
        $sql->addTable('cronos_personal AS personal');
        $sql->addOrder($orden);
        $sql->addWhere("personal.eliminado=$this->_verEliminados");
        if (isset($filtro) && $filtro != false) {
            $sql->addWhere($filtro);
        }

        if ($fin > 0) {
            $sql->addLimit($inicio, $fin);
        }
//        echo $sql;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
//        print_r($this->_db->fetchall());
        return $this->_db->fetchall();
    }

    public function getPersonal($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_personal where id = $id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
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

    public function editarTurnoPersonal(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_personal', $valores, $condicion);
    }

    public function eliminarPersonal($condicion)
    {
        return $this->_db->eliminar('cronos_personal', $condicion);
    }

}
