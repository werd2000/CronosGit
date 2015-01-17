<?php
require_once BASE_PATH . 'Modulos' . DS . 'Personal' . DS . 'Modelos' . DS .  'Personal.php';
require_once 'PersonalPacienteModelo.php';
require_once BASE_PATH . 'Modulos' . DS . 'Paciente' . DS . 'Modelos' . DS .  'Paciente.php';
//require_once 'IndexModelo.php';

/**
 * Clase Historia Terapeutica
 */
class hterapeutica
{
    protected $_id;
    protected $_idPaciente;
    protected $_idProfesional;
    protected $_tipo_informe;
    protected $_fechaObservacion;
    protected $_observacion;
    protected $_objProfesional;
    protected $_eliminado;

    private $_verEliminados = 0;

    public function getId()
    {
        return $this->_id;
    }
    
    public function getIdPaciente()
    {
        return $this->_idPaciente;
    }
    
    public function getTipo()
    {
        $retorno = 'Sin valor';
        if ($this->_tipo_informe==1){
            $retorno = 'Evolutivo';
        }else{
            $retorno = 'Evaluativo';
        }
        return $retorno;
    }


    public function getIdProfesional()
    {
        return $this->_idProfesional;
    }
    
    public function getObservacion()
    {
        return $this->_observacion;
    }
    
    public function getFechaObservacion()
    {
        return Fecha::getFechaAr($this->_fechaObservacion);
    }
    
    public function getObjProfesional()
    {
        return $this->_objProfesional;
    }
    /**
     * Clase constructora 
     */
    public function __construct($datos)
    {
        $this->_id = $datos['id'];
        $this->_idPaciente = $datos['idPaciente'];
        $this->_tipo_informe = $datos['tipo'];
        $this->_idProfesional = $datos['idProfesional'];
        $this->_fechaObservacion = $datos['fechaObservacion'];
        $this->_observacion = $datos['observacion'];
        $this->_objProfesional = $this->_getProfesional($datos['idProfesional']);
    }

    /**
     * Obtiene un array con las historia terapeuticas de los pacientes
     * @param array $datos
     * @return \HTerapeutica 
     */
    public static function getHTerapeuticas($datos = false)
    {
        $resultado = array();
        if (is_array($datos)){
            if (count($datos) > 0){
                foreach ($datos as $indice => $hterapeutica) {
                    $resultado[] = new hterapeutica($hterapeutica);
                }
            }
        }
        return $resultado;
    }
    
    
    /**
     * Obtiene un array con las historia terapeuticas de los pacientes
     * @param array $datos
     * @return \HTerapeutica 
     */
    public static function getHTerapeutica($id = false)
    {
        $resultado = array();
        if (is_int($id)){
            $resultado[] = new hterapeutica($hterapeutica);
        }
        return $resultado;
    }
    
    
    /**
     * Obtiene un array con las terapias de un paciente
     * @return Resource 
     */
    public function getTerapias($idPaciente)
    {
//        echo $idPaciente;
        $sql = "SELECT pac.id as pac, pac.idTerapia, pac.idPaciente, pac.idProfesional, 
            pac.sesiones, pac.observaciones, terapia.id, terapia.terapia 
            FROM cronos_pacientes_terapia as pac, cronos_terapias as terapia
            where pac.idPaciente = $idPaciente AND
            pac.eliminado = $this->_verEliminados AND
            pac.idTerapia = terapia.id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
//        echo '<pre>'; print_r ($this->_db->fetchall());
        return $this->_db->fetchall();
    }

    public function getTerapia($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_pacientes_terapia where id = $id AND eliminado = '$this->_verEliminados'";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
    private function _getProfesional($idProfesional)
    {
        $datos = new Paciente_Modelos_PersonalPacienteModelo();
        $personal = new Personal($datos->getPersonal($idProfesional));
        return $personal;
    }
    
    public function getObjTerapia($idTerapia)
    {
        $sql = 'SELECT * FROM cronos_terapias WHERE id = ' . $idTerapia;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
    
    public function __toString()
    {
        return $this->_fechaObservacion . '<br>' . $this->_idProfesional . '<br>' . $this->_observacion;
    }
}
