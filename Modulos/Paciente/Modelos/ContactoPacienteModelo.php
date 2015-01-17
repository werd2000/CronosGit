<?php

//require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'Sql.php';
require_once APP_PATH . 'Modelo.php';

/**
 * Clase Modelo Datos Laborales que extiende de la clase Modelo
 */
class Paciente_Modelos_contactoPacienteModelo extends App_Modelo
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
     * Obtiene un array con los Contactos de un paciente
     * @return Resource 
     */
    public function getContactos($id)
    {
        $sql = "select * from cronos_contacto_pacientes where idPaciente = $id AND eliminado = $this->_verEliminados";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
//        echo $sql;
        return $this->_db->fetchall();
    }

    public function getContacto($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_contacto_pacientes where id = $id AND eliminado = '$this->_verEliminados'";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
    /**
     * Obtiene un array con los Contactos de todo los pacientes
     * @return Resource 
     */
    public function getListadoContactos()
    {
        $sql = "SELECT pacientes.id, pacientes.apellidos, pacientes.nombres,
                contactos.tipo, contactos.valor, contactos.observaciones 
                FROM cronos_contacto_pacientes as contactos, 
                cronos_pacientes as pacientes
                WHERE contactos.idPaciente = pacientes.id AND
                pacientes.eliminado = $this->_verEliminados AND
                contactos.eliminado = $this->_verEliminados
                ORDER BY pacientes.apellidos, pacientes.nombres";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }
    

    /**
     * Guarda un contanto en la BD
     * @param array $valores
     * @return int lastInsertId 
     */
    public function insertarContactoPaciente(array $valores)
    {
        return $this->_db->insert('cronos_contacto_pacientes', $valores);
    }
    
    public function eliminarContactoPaciente($condicion)
    {
        return $this->_db->eliminar('cronos_contacto_pacientes', $condicion);
    }

    

}
