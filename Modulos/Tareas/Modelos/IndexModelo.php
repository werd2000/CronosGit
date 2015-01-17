<?php

require_once APP_PATH . 'Modelo.php';
require_once 'Tarea.php';
/**
 * Clase Modelo Tareas que extiende de la clase Modelo
 */
class indexModelo extends Modelo
{

    private $_verEliminados = false;
    private $_verResuelto = false;

    /**
     * Clase constructora 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene un array con los tareas
     * @return Resource 
     */
    public function getTareas()
    {
        $sql = new Sql();
        $sql->addCampos(array('tareas.*'));
        $sql->addFuncion('Select');
        $sql->addTable('cronos_tareas as tareas');
//        $sql->addTable('conta_usuarios as usuarios');
        $sql->addOrder('fechaFin');
        $sql->addWhere("tareas.eliminado='$this->_verEliminados'");
        if (!$this->_verResuelto){
            $sql->addWhere("tareas.estado!='RESUELTO'");
        }
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return Tarea::getTareas($this->_db->fetchall());
    }

    /**
     * Obtiene algunos tareas
     * @return Resource 
     */
    public function getAlgunasTareas($inicio, $fin, $orden, $filtro, $campos = array('*'))
    {
        $this->_verEliminados = 0;
        $sql = new Sql();
        $sql->addCampos($campos);
        $sql->addFuncion('Select');
        $sql->addTable('cronos_tareas AS tareas');
        $sql->addOrder($orden);
        $sql->addWhere("tareas.eliminado=$this->_verEliminados");
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

    public function getTarea($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_tareas where id = $id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return new Tarea($this->_db->fetchRow());
    }

    public function insertarTareas(array $valores)
    {
        return $this->_db->insert('cronos_tareas', $valores);
    }

    public function editarTareas(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_tareas', $valores, $condicion);
    }

    public function eliminarTareas($condicion)
    {
        return $this->_db->eliminar('cronos_tareas', $condicion);
    }

}
