<?php

//require_once BASE_PATH . 'Modulos' . DS . 'Paciente' . DS . 'Modelos' . DS . 'paciente.php';

/**
 * Clase Modelo Paciente que extiende de la clase Modelo
 */
class personalModelo extends Modelo
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
     * Obtiene los datos de un personal
     * @return Resource 
     */
    public function getPersonal($id)
    {
        $retorno = '';
        $id = (int) $id;
        $sql = "select * from cronos_personal where id = $id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $result = $this->_db->fetchRow();
        require_once BASE_PATH . 'Modulos' . DS . 'Personal' . DS . 'Modelos' . DS . 'Personal.php';
        if ($result != false) {
            $retorno = new Personal($result);
//            print_r($retorno);
        }
        return $retorno;
    }

    /**
     * Obtiene algunos personal
     * @return Resource 
     */
    public function getAlgunosPersonal($inicio, $fin, $orden, $filtro, $campos = array('*'))
    {
//        require_once BASE_PATH . 'Modulos' . DS . 'Personal' . DS . 'Modelos' . DS . 'personal.php';

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
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return Personal::getPersonal($this->_db->fetchall());
        //return $this->_db->fetchall();
    }

}
