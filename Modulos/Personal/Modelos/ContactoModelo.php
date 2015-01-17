<?php

//require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'Sql.php';
require_once APP_PATH . 'Modelo.php';

/**
 * Clase Modelo Datos Laborales que extiende de la clase Modelo
 */
class Personal_Modelos_contactoModelo extends App_Modelo
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
     * Obtiene un array con los Contactos de un personal
     * @return Resource 
     */
    public function getContactos($id=0)
    {
        if (is_null($id)) $id=0;
        $sql = "select * from cronos_contacto_profesionales where idProfesional = $id";
        $this->_db->setTipoDatos('Array');
//        echo $sql;
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }
/**
     * Obtiene un array con los Contactos de todo el personal
     * @return Resource 
     */
    public function getListadoContactos()
    {
        $sql = "SELECT personal.id, personal.apellidos, personal.nombres,
                contactos.tipo, contactos.valor 
                FROM cronos_contacto_profesionales as contactos, 
                cronos_personal as personal
                WHERE contactos.idProfesional = personal.id AND personal.eliminado = $this->_verEliminados
                ORDER BY personal.apellidos, personal.nombres";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }    
    /**
     * Obtiene un array con todos los Contactos de personal
     * @return Resource 
     */
    public function getListaContactos()
    {
        $sql = "select personal.id, personal.apellidos, personal.nombres,
        contactos.tipo, contactos.valor
        FROM cronos_contacto_profesionales as contactos,
        cronos_personal as personal
        WHERE contactos.idProfesional = personal.id AND personal.eliminado = $this->_verEliminados";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }

    public function getContacto($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_contacto_profesionales where id = $id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    

    /**
     * Guarda un contanto en la BD
     * @param array $valores
     * @return int lastInsertId 
     */
    public function insertarContactoProfesional(array $valores)
    {
        return $this->_db->insert('cronos_contacto_profesionales', $valores);
    }
    
    public function eliminarContactoProfesional($condicion)
    {
        return $this->_db->eliminar('cronos_contacto_profesionales', $condicion);
    }


}