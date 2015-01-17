<?php
require_once LibQ . 'Persona/iPersona.php';
require_once LibQ . 'Persona/PersonaAbstract.php';
require_once DIRMODULOS . 'ContactosPacientes/ContactosPacientes.php';
require_once DIRMODULOS . 'FamiliaPaciente/FamiliaPaciente.php';
require_once DIRMODULOS . 'Pacientes/Modelo/PacientesModelo.php';

/**
 * Clase Paciente que se extiende de la clase persona
 * @see Persona.php 
 */
class Paciente extends PersonaAbstract implements iPersona{

    private $_contactos = array();
    private $_obra_social;
    private $_nro_afiliado;
    private $_familia;
    private $_modelo;
    private $_terapias;
    private $_diagnostico;
    private $_observaciones;
    private $_cuil;

    function __construct(array $datos){
        parent::__construct($datos);
        $this->_modelo = new PacientesModelo();
        $this->_obra_social = $this->_modelo->buscarOSPaciente(array('denominacion='. $datos['obraSocial']));
        print_r($this->_obra_social);
        $this->_terapias = $this->_modelo->buscarTerapiasPaciente(array('idPaciente='.$datos['id']));

        $this->_contactos = new ContactosPacientes();
        $this->_familia = new FamiliaPaciente();
    }

    public function getContactos() {
        return $this->_contactos->getContactos($this->getId());
    }
    
    public function getOSocial()
    {
        return $this->_obra_social;
    }
    
    public function getNroAfiliado()
    {
        return $this->_nro_afiliado;
    }
    
    public function getFamilia()
    {
        return $this->_familia->getFamilia($this->getId());
    }
    
    public function getDiagnostico()
    {
        return $this->_diagnostico;
    }
    
    public function getTerapias()
    {
        return $this->_terapias;
    }
    
    public function getObservaciones()
    {
        return $this->_observaciones;
    }
    
    public function getCuil()
    {
        return $this->_cuil;
    }
    
    

}