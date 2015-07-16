<?php
require_once BASE_PATH . 'Modulos' . DS . 'Personal' . DS . 'Modelos' . DS .  'Personal.php';
require_once BASE_PATH . 'Modulos' . DS . 'Terapias' . DS . 'Modelos' . DS .  'Terapia.php';
require_once 'PersonalPacienteModelo.php';
require_once 'TerapiaModelo.php';

/**
 * Clase TerapiaPaciente
 *
 * @author WERD
 */
class TerapiaPaciente
{
    protected $_id;
//    protected $_idPaciente;
    protected $_idTerapia;
//    protected $_idProfesional;
    protected $_sesiones;
    protected $_observaciones;
    protected $_eliminado;
    protected $_profesional;
    protected $_terapia;

        /** Métodos GET */
    public function getId()
    {
        return $this->_id;
    }
    
    public function getIdPaciente()
    {
        return $this->_idPaciente;
    }
    
    public function getIdTerapia()
    {
        return $this->_idTerapia;
    }
    
//    public function getIdProfesional()
//    {
//        return $this->_idProfesional;
//    }
    
    public function getSesiones()
    {
        return $this->_sesiones;
    }
    
    public function getObservaciones()
    {
        return $this->_observaciones;
    }
    
    public function getEliminado()
    {
        return $this->_eliminado;
    }
    
    public function getProfesional()
    {
        return $this->_profesional;
    }
    
    public function getTerapia()
    {
        return $this->_terapia;
    }
    
    public function setTerapia($terapia)
    {
        $this->_terapia = $terapia;
    }

    public function __construct($datos)
    {
        $this->_id = $datos['id'];
//        $this->_idPaciente = $datos['idPaciente'];
//        $this->_idProfesional = $datos['idProfesional'];
        $this->_profesional = $this->_getProfesional($datos['idProfesional']);
        $this->_idTerapia = $datos['idTerapia'];
//        $this->_terapia = $this->_getTerapia($this->_idTerapia);
        $this->_sesiones = $datos['sesiones'];
        $this->_observaciones = $datos['observaciones'];
        if (isset($datos['eliminado'])){
            $this->_eliminado = $datos['eliminado'];
        }
    }
    
    /**
     * Obtiene un array con las terapias de los pacientes
     * @param array $datos
     * @return \TerapiaPaciente 
     */
    public static function getTerapias($datos = array())
    {
        $resultado = array();
        if (count($datos)>1){
            foreach ($datos as $terapia) {
                $resultado[] = new TerapiaPaciente($terapia);
            }
        }
        return $resultado;
    }
    
    private function _getProfesional($idProfesional)
    {
        $datos = new Paciente_Modelos_personalPacienteModelo();
        $personal = new Personal_Modelos_Personal($datos->getPersonal($idProfesional));
        return $personal;
    }
    
    private function _getTerapia($idTerapia)
    {
        $datos = new Paciente_Modelos_terapiaModelo();
        $terapia = new Terapias_Modelos_Terapia($datos->getObjTerapia($idTerapia));
        return $terapia;
    }
}

?>
