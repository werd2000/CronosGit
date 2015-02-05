<?php
require_once BASE_PATH . 'LibQ' . DS . 'SClases' . DS . 'Persona' . DS . 'Persona.php';
require_once 'TerapiaModelo.php';
require_once 'TerapiaPaciente.php';
require_once 'FamiliaPaciente.php';
require_once 'FamiliaModelo.php';
require_once 'OsocialModelo.php';
require_once 'ObraSocialPaciente.php';
require_once 'PlanTratamientoModelo.php';
require_once 'PlanTratamiento.php';
require_once 'HTerapeuticaModelo.php';
require_once 'HTerapeutica.php';
require_once 'EducacionPacienteModelo.php';
require_once 'EducacionPaciente.php';
require_once BASE_PATH . 'LibQ' . DS . 'SClases' . DS . 'Persona' . DS . 'Domicilio.php';

/**
 * Clase Paciente extiende de la clase Libreria_SClass_Persona
 * @see Persona
 * @author WERD
 */
class Paciente_Modelos_Paciente extends LibQ_Sclases_Persona_Persona
{
    protected $_id;
    protected $_diagnostico;
    protected $_observaciones;
    protected $_eliminado;
    protected $_estado;
//    protected $_objContactos;
    protected $_objTerapias;
    protected $_objFamilia;
    protected $_objOSocial;
    protected $_objPlanTratamiento;
    protected $_objHTerapeutica;
    protected $_educacion;
    /**
     * Familia
     * @var array
     */
    protected $_familia;

    public function __construct($paciente=array())
    {
        parent::__construct($paciente);
        $this->_id = $paciente['id'];
        $this->_diagnostico = $paciente['diagnostico'];
        $this->_eliminado = $paciente['eliminado'];
        $this->_observaciones = $paciente['observaciones'];
        $this->_estado = $paciente['estado'];
    }
    
    /**
     * Estabece los datos del domicilio de la persona
     * @param Array $domicilio
     */
    public function setDomicilio(DomicilioPaciente $domicilio)
    {
        $this->_domicilio = $domicilio;
    }
    
    public function getDomicilio()
    {
        return $this->_domicilio;
    }
    

    /**
     * 
     * @return 
     */
    public function getFistDomicilio()
    {
        $dom = '';
        if (is_array($this->_domicilio) && count($this->_domicilio) > 0){
           $dom = $this->_domicilio[0];
        }else{
            $dom = $this->_domicilio;
        }
        return $dom;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getDiagnostico()
    {
        return $this->_diagnostico;
    }

    public function getLocalidad()
    {
        return $this->_domicilio->getLocalidad();
    }
    
    public function getEstado()
    {
        return $this->_estado;
    }


    /** Obtiene una colección de Terapias
     * @return TerapiaPaciente 
     */
    private function _getTerapias()
    {
        $terapiaModelo = new Paciente_Modelos_terapiaModelo();
        $datos = $terapiaModelo->getTerapias($this->_id);
        return TerapiaPaciente::getTerapias($datos);
    }

    public function getTerapias()
    {
        return $this->_getTerapias();
    }

    public function getEdad()
    {
        $hoy = new LibQ_Fecha('now');
        $edad = intval($hoy->s_datediff('y', $this->getFecha_nac(), $hoy->getFecha()));
        return $edad;
    }

    public function getFoto()
    {
        $foto = BASE_PATH . 'Public/Img/Fotos/Pacientes/Id' . $this->_id . '.png';
        if (is_readable($foto)) {
            $retorno = IMAGEN_PUBLICA . 'Fotos/Pacientes/Id' . $this->_id . '.png';
        }else{
            $retorno = IMAGEN_PUBLICA . 'Fotos/Pacientes/idsin_imagen.png';
        }
        return $retorno;
    }

    public function getFamilia()
    {
        return $this->_familia;
    }

    private function _getOSocial()
    {
        $oSocialModelo = new Paciente_Modelos_oSocialModelo();
        $datos = $oSocialModelo->getOSocialPaciente($this->_id);
        return new ObraSocialPaciente($datos);
    }
    
    public function getEducacion()
    {
        $educacionModelo = new Paciente_Modelos_educacionPacienteModelo();
        $datos = $educacionModelo->getEducacionPaciente($this->_id);
        return new EducacionPaciente($datos);
    }

    public function getOSocial()
    {
        return $this->_getOSocial();
    }

    public function getPlanTratamiento()
    {
        $planModelo = new planTratamientoModelo();
        $datos = $planModelo->getPlanTratamientoAnio($this->_id, date("Y"));
        return new PlanTratamiento($datos);
    }
    
    /** Obtiene una colección de historias terapeuticas
     * @return HTerapeutica 
     */
    private function _getHTerapeutica()
    {
        $retorno = array();
        $hTerapeuticaModelo = new Paciente_Modelos_hTerapeuticaModelo();
        $datos = $hTerapeuticaModelo->getHTerapeutica(intval($this->_id));
        if($datos != false){
            $retorno = HTerapeutica::getHTerapeuticas($datos);
        }
        return $retorno;                        
    }
    
    public function getHTerapeutica()
    {
        $resultado = '';
        if (count($this->_objHTerapeutica)>0){
            foreach ($this->_objHTerapeutica as $hterapeutica) {
                $resultado[] = 'El <b>' . $hterapeutica->getFechaObservacion()
                        . '</b> - <b>' . $hterapeutica->getIdProfesional() . '</b> escribió:' . '<br>' .
                        $hterapeutica->getObservacion() . '<br>';
            }
        }
        if (is_array($resultado)){
            $resultado = implode('<br>', $resultado);
        }
        return $resultado;
    }
    
    public function getHTerapeuticas()
    {
        return $this->_getHTerapeutica();
    }
    
        /**
     * Estabece los datos de educacion de la persona
     * @param Array $educacion
     */
    public function setEducacion($educacion)
    {
        $this->_educacion = $educacion;
    }
    
    /**
     * Estabece el array de contactos de la persona
     * @param Array $familia
     */
    public function setFamilia(Array $familia)
    {
        $this->_familia = $familia;
    }

}