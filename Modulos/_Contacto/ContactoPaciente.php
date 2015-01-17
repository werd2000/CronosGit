<?php
require_once 'iContacto.php';
require_once LibQ . 'Input.php';
require_once LibQ . 'Contacto/Contacto.php';
//require_once DIRMODULOS . 'ContactosPacientes/Modelo/ContactosPacientesModelo.php';

class ContactoPaciente extends ContactoAbstract implements iContacto{

    private $_bDatos;

    function __construct($datos = null) {
        parent::__construct($datos);
        $this->_bDatos = new ContactosPacientesModelo();
    }

    public function getContactos($id) {
        $retorno = $this->_bDatos->buscarContacto(array("idPaciente=$id"));
        return $retorno;
    }
    
    public function getContacto($id) {
        $retorno = $this->_bDatos->buscarContacto(array("idPaciente=$id"));
        return $retorno;
    }

    public function eliminarContacto($id = null) {
        $retorno = 0;
        $retorno = $this->_bDatos->actualizar('cronos_contacto_pacientes', array('eliminado' => true), "id=$id");
        return $retorno;
    }

}

