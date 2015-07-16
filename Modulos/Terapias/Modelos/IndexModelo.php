<?php

require_once APP_PATH . 'Modelo.php';
require_once 'Terapia.php';
/**
 * Clase Modelo Terapias que extiende de la clase Modelo
 */
class indexModelo extends App_Modelo
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
     * Obtiene un array con los terapias
     * @return Resource 
     */
    public function getTerapias()
    {
        $sql = 'SELECT * FROM cronos_terapias';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return Terapia::getTerapias($this->_db->fetchall());
    }
    
    /**
     * Obtiene un array con los terapias
     * @return Resource 
     */
    public function getTerapiaTerapeuta($id)
    {
        $sql = 'SELECT * FROM cronos_terapias';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }

    /**
     * Obtiene algunos terapias
     * @return Resource 
     */
    public function getAlgunasTerapias($inicio, $fin, $orden, $filtro, $campos = array('*'))
    {
        $this->_verEliminados = 0;
        $sql = new Sql();
        $sql->addCampos($campos);
        $sql->addFuncion('Select');
        $sql->addTable('cronos_terapias AS terapias');
        $sql->addOrder($orden);
        $sql->addWhere("terapias.eliminado=$this->_verEliminados");
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

    public function getTerapia($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_terapias where id = $id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }

    public function insertarTerapias(array $valores)
    {
        return $this->_db->insert('cronos_terapias', $valores);
    }

    public function editarTerapias(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_terapias', $valores, $condicion);
    }

    public function eliminarTerapias($condicion)
    {
        return $this->_db->eliminar('cronos_terapias', $condicion);
    }

}
