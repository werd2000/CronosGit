<?php
/**
 * Clase ContactoPaciente
 *
 * @author WERD
 */
class ContactoPaciente extends LibQ_Sclases_Contacto
{
    protected $_id;
    protected $_idPaciente;
    protected $_eliminado;
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getIdPaciente()
    {
        return $this->_idPaciente;
    }
    
    public function __construct($datos=null)
    {
        if(!is_null($datos)){
            parent::__construct($datos);
            $this->_id = $datos['id'];
            $this->_idPaciente = $datos['id_paciente'];
            $this->_eliminado = $datos['eliminado'];
        }
    }
    
    public static function getContactos($lista = array())
    {
        $resultado = array();
        if (count($lista)>0){
            foreach ($lista as $datos) {
                $resultado[] = new ContactoPaciente($datos);
            }
        }
        return $resultado;
    }
}
