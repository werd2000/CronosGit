<?php

//require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'Sql.php';
require_once APP_PATH . 'Modelo.php';

/**
 * Clase Modelo ObrasSociales que extiende de la clase Modelo
 */
class indexModelo extends Modelo
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
     * Obtiene un array con los obrasociales
     * @return Resource 
     */
    public function getObrasSociales()
    {
        $sql = 'SELECT * FROM cronos_obrassociales';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }

    /**
     * Obtiene algunos obrasociales
     * @return Resource 
     */
    public function getAlgunasObrasSociales($inicio, $fin, $orden, $filtro, $campos = array('*'))
    {
        $this->_verEliminados = 0;
        $sql = new Sql();
        $sql->addCampos($campos);
        $sql->addFuncion('Select');
        $sql->addTable('cronos_obrassociales AS obrasociales');
        $sql->addOrder($orden);
        $sql->addWhere("obrasociales.eliminado=$this->_verEliminados");
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

    public function getObraSocial($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_obrassociales where id = $id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }

    public function insertarObraSocial(array $valores)
    {
        return $this->_db->insert('cronos_obrassociales', $valores);
    }

    public function editarObraSocial(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_obrassociales', $valores, $condicion);
    }

    public function eliminarObraSocial($condicion)
    {
        return $this->_db->eliminar('cronos_obrassociales', $condicion);
    }

}
