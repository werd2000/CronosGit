<?php
/**
 * Clase PlanTratamiento
 *
 * @author WERD
 */
class PlanTratamiento
{
    protected $_id;
    protected $_idPaciente;
    protected $_archivo;
    protected $_fecha;
    protected $_eliminado;

    /** Métodos GET */
    public function getId()
    {
        return $this->_id;
    }
    
    public function getIdPaciente()
    {
        return $this->_idPaciente;
    }
    
    public function getArchivo()
    {
        return $this->_archivo;
    }
    
    public function getFecha()
    {
        return $this->_fecha;
    }
    
    public function getEliminado()
    {
        return $this->_eliminado;
    }
    
    public function __construct($datos)
    {
        $this->_id = $datos['id'];
        $this->_idPaciente = $datos['idPaciente'];
        $this->_archivo = $datos['archivo'];
        $this->_fecha = $datos['fecha'];
        if (isset($datos['eliminado'])){
            $this->_eliminado = $datos['eliminado'];
        }
    }
    
    /**
     * Obtiene un array con los planes de tratamiento de un paciente
     * @param array $datos
     * @return \TerapiaPaciente 
     */
    public static function getPlanesTratamiento($datos = array())
    {
        $resultado = array();
        if (count($datos)>1){
            foreach ($datos as $plan) {
                $resultado[] = new PlanTratamiento($plan);
            }
        }
        return $resultado;
    }
    

}

?>
