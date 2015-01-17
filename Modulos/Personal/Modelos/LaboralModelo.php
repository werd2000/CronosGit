<?php

//require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'Sql.php';
require_once APP_PATH . 'Modelo.php';

/**
 * Clase Modelo Datos Laborales que extiende de la clase Modelo
 */
class Personal_Modelos_laboralModelo extends App_Modelo
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
     * Obtiene un array con los personal
     * @return Resource 
     */
    public function getTodoPersonal()
    {
        $sql = 'SELECT * FROM cronos_personal ORDER BY apellidos, nombres';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
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

    public function getDatosLaborales($id)
    {
        $id = (int) $id;
        $sql = "SELECT cronos_rrhh_datos_laborales.*,
                cronos_terapias.terapia, cronos_terapias.id
                FROM cronos_rrhh_datos_laborales, cronos_terapias
                WHERE cronos_rrhh_datos_laborales.idProfesional = $id AND
                cronos_terapias.id = cronos_rrhh_datos_laborales.puesto
                ";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        var_dump($this->_db->fetchRow());
        return $this->_db->fetchRow();
    }
    

    public function insertarDatosLaborales(array $valores)
    {
        return $this->_db->insert('cronos_rrhh_datos_laborales', $valores);
    }

    public function editarDatosLaborales(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_rrhh_datos_laborales', $valores, $condicion);
    }

    public function eliminarDatosLaborales($condicion)
    {
        return $this->_db->eliminar('cronos_personal', $condicion);
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
        return $this->_db->fetchall();
    }

}
