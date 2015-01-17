<?php
//require_once BASE_PATH . 'Modulos' . DS . 'Paciente' . DS . 'Modelos' . DS . 'paciente.php';

/**
 * Clase Modelo Paciente que extiende de la clase Modelo
 */
class pacientesModelo extends Modelo
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
     * Obtiene un array con los pacientes de una OS
     * @return Resource 
     */
    public function getPacientesByOs($idOsocial)
    {
        $sql = 'SELECT osPaciente.*, pacientes.* FROM cronos_paciente_os as osPaciente, 
            cronos_pacientes as pacientes 
            WHERE osPaciente.idOSocial = ' . $idOsocial . 
            ' AND osPaciente.idPaciente = pacientes.id ORDER BY pacientes.apellidos, pacientes.nombres';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }
    
    /**
     * Obtiene los datos de un paciente
     * @return Resource 
     */
    public function getPaciente($id)
    {
        $paciente = '';
        $id = (int) $id;
        $sql = "SELECT * FROM cronos_pacientes WHERE id = $id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchRow();
//        echo '<pre>'; print_r($retorno);
        if ($retorno!=false){
            require_once BASE_PATH . 'Modulos' . DS . 'Paciente' . DS . 'Modelos' . DS . 'Paciente.php';
            $paciente = new Paciente($retorno);
//            echo '<pre>'; print_r($paciente);
        }
        return $paciente;
    }
    
    /**
     * Obtiene algunos pacientes
     * @return Resource 
     */
    public function getAlgunosPacientes($inicio, $fin, $orden, $filtro, $campos = array('*'))
    {
//        require_once BASE_PATH . 'Modulos' . DS . 'Paciente' . DS . 'Modelos' . DS . 'Paciente.php';
        $this->_verEliminados = 0;
        $sql = new Sql();
        $sql->addCampos($campos);
        $sql->addFuncion('Select');
        $sql->addTable('cronos_pacientes AS pacientes');
        $sql->addOrder($orden);
        $sql->addWhere("pacientes.eliminado=$this->_verEliminados");
        if ($filtro != '') {
            $sql->addWhere($filtro);
        }
        if ($fin > 0) {
            $sql->addLimit($inicio, $fin);
        }
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $lista = $this->_db->fetchall();
        if(is_array($lista)){
            foreach ($lista as $pac) {
                $pacientes[] = new Paciente($pac);
            }
        }
        return $pacientes;
    }

    
   

}
