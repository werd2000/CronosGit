<?php
require_once MODS_PATH . 'Personal' . DS . 'Modelos' . DS . 'Personal.php';
require_once APP_PATH . 'Modelo.php';
require_once 'Paciente.php';
require_once 'ContactoPaciente.php';
require_once 'DomicilioPaciente.php';
require_once MODS_PATH . 'Terapias' . DS . 'Modelos' . DS . 'Terapia.php';

/**
 * Clase Modelo Paciente que extiende de la clase Modelo
 */
class Paciente_Modelos_indexModelo extends App_Modelo
{

    private $_verEliminados = 0;
    private $_lastId;

    /**
     * Clase constructora 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtiene un array con los pacientes
     * @return Resource 
     */
    public function getPacientes()
    {
        $this->_verEliminados = 0;
        $sql = 'SELECT * FROM cronos_pacientes WHERE eliminado = ' .
                $this->_verEliminados . ' ORDER BY apellidos';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_crearPacientes($this->_db->fetchall());
    }
    
    /**
     * Obtiene un array con los pacientes
     * @return Resource 
     */
    public function getListaEscuelas()
    {
        $this->_verEliminados = 0;
        $sql = 'SELECT * FROM cronos_lista_escuelas ORDER BY Nombre';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }

    /**
     * Crea un array de pacientes
     * @param Array $lista
     * @return \Paciente
     */
    public function _crearPacientes($lista)
    {
        $resultado = array();
        if (is_array($lista) and count($lista) > 0) {
            foreach ($lista as $datos) {
                $pac = new Paciente_Modelos_Paciente($datos);
                $pac->setDomicilio($this->getDomicilioPaciente("id_paciente=" . $datos['id']));
                $pac->setContactos($this->getContactosPaciente("id_paciente=" . $datos['id']));
                $resultado[] = $pac;
            }
        }
        return $resultado;
    }

    /**
     * Crea un paciente
     * @param Array $datos
     * @return \Paciente
     */
    public function _crearPaciente($datos)
    {
        $pac = new Paciente_Modelos_Paciente($datos);
        $pac->setDomicilio($this->getDomicilioPaciente("id_paciente=" . $datos['id']));
        $pac->setContactos($this->getContactosPaciente("id_paciente=" . $datos['id']));
        $pac->setFamilia($this->getFamiliaPaciente("id_paciente=" . $datos['id']));
        $pac->setEducacion($this->getEducacionPaciente("id_paciente=" . $datos['id']));
        return $pac;
    }
    
    public function getFamiliaPaciente($where)
    {
        $this->_verEliminados = 0;
        $sql = 'SELECT * FROM cronos_familia_paciente WHERE eliminado = ' .
                $this->_verEliminados . ' AND ' . $where;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_crearFamiliaPaciente($this->_db->fetchAll());
    }
    
    public function _crearFamiliaPaciente($lista)
    {
        $resultado = array();
        if (is_array($lista) and count($lista) > 0) {
            foreach ($lista as $datos) {
                $resultado[] = new FamiliaPaciente($datos);
            }
        }
        return $resultado;
    }
    
    public function getEducacionPaciente($where)
    {
        $this->_verEliminados = 0;
        $sql = 'SELECT * FROM cronos_educacion_paciente WHERE eliminado = ' .
                $this->_verEliminados . ' AND ' . $where;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_crearEducacionPaciente($this->_db->fetchRow());
    }

    public function _crearEducacionPaciente($datos)
    {
        $resultado = new EducacionPaciente($datos);
        return $resultado;
    }

    /**
     * Obtener de la bd los domicilios del paciente solicitado en el $where
     * @param String $where
     * @return Array
     */
    public function getDomicilioPaciente($where)
    {
        $this->_verEliminados = 0;
        $sql = 'SELECT * FROM cronos_domicilios_pacientes WHERE eliminado = ' .
                $this->_verEliminados . ' AND ' . $where . ' ORDER BY tipo_domicilio';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_crearDomicilio($this->_db->fetchRow());
    }

    public function _crearDomicilios($lista)
    {
        $resultado = array();
        if (is_array($lista) and count($lista) > 0) {
            foreach ($lista as $datos) {
                $resultado[] = new DomicilioPaciente($datos);
            }
        }
        return $resultado;
    }
    
    public function _crearDomicilio($datos)
    {
        return new DomicilioPaciente($datos);
    }

    /**
     * Obtiene un array con los pacientes
     * @return Resource 
     */
    public function getPacientesArray()
    {
        $sql = 'SELECT * FROM cronos_pacientes ORDER BY apellidos';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
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
                ' AND osPaciente.idPaciente = pacientes.id AND pacientes.eliminado = ' .
                $this->_verEliminados . ' ORDER BY pacientes.apellidos, pacientes.nombres';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_crearPacientes($this->_db->fetchall());
    }

    /**
     * Obtiene algunos pacientes
     * @return Resource 
     */
    public function getAlgunosPacientes($inicio, $fin, $orden, $filtro, $campos = array('*'))
    {
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
        return Paciente::getPacientes($this->_db->fetchall());
    }

    public function getPacientesBySql($sql)
    {
        $this->_db->setTipoDatos('Array');
//        echo $sql;
        $this->_db->query($sql);
        return $this->_crearPacientes($this->_db->fetchall());
    }

    /**
     * Obtiene los datos de un paciente
     * @param
     * @return Resource 
     */
    public function getPaciente($where)
    {
        $sql = "SELECT * FROM cronos_pacientes WHERE $where";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_crearPaciente($this->_db->fetchRow());
    }
    
    public function getTerpias()
    {
        $sql = "SELECT * FROM cronos_terapias";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_crearTerapias($this->_db->fetchall());
    }

    public function insertarPaciente(array $valores)
    {
        return $this->_db->insert('cronos_pacientes', $valores);
    }
    
    public function insertarDomicilioPaciente(array $valores)
    {
        return $this->_db->insert('cronos_domicilios_pacientes', $valores);
    }

    public function editarPaciente(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_pacientes', $valores, $condicion);
    }
    
    public function editarDomicilioPaciente(array $valores, $condicion)
    {
        return $this->_db->editar('cronos_domicilios_pacientes', $valores, $condicion);
    }

    public function eliminarPaciente($condicion)
    {
        return $this->_db->editar('cronos_pacientes', array('eliminado' => 1), $condicion);
//        return $this->_db->eliminar('cronos_pacientes', $condicion);
    }

    /**
     * Obtiene algunos personal
     * @return Resource 
     */
    public function getAlgunosPersonal($inicio, $fin, $orden, $filtro, $campos = array('*'))
    {
        $this->_verEliminados = 0;
        $sql = new Sql();
        $sql->addCampos($campos);
        $sql->addFuncion('Select');
        $sql->addTable('cronos_personal AS personal');
        $sql->addOrder($orden);
        $sql->addWhere("personal.eliminado=$this->_verEliminados");
        if ($filtro != '') {
            $sql->addWhere($filtro);
        }

        if ($fin > 0) {
            $sql->addLimit($inicio, $fin);
        }
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }

    public function getContactosPacientes()
    {
        $this->_verEliminados = 0;
        $sql = 'SELECT * FROM cronos_contacto_pacientes WHERE eliminado = ' .
                $this->_verEliminados . ' ORDER BY tipo';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_crearContactos($this->_db->fetchall());
    }

    public function getContactosPaciente($where)
    {
        $this->_verEliminados = 0;
        $sql = 'SELECT * FROM cronos_contacto_pacientes WHERE eliminado = ' .
                $this->_verEliminados . ' AND ' . $where . ' ORDER BY tipo';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_crearContactos($this->_db->fetchall());
    }

    private function _crearContactos($lista)
    {
        $resultado = array();
        if (is_array($lista) and count($lista) > 0) {
            foreach ($lista as $datos) {
                $resultado[] = new ContactoPaciente($datos);
            }
        }
        return $resultado;
    }
    
    private function _crearTerapias($lista)
    {
        $resultado = array();
        if (is_array($lista) and count($lista) > 0) {
            foreach ($lista as $datos) {
                $resultado[] = new Terapias_Modelos_Terapia($datos);
            }
        }
        return $resultado;
    }
    
    public function getPersonalTerapia($where)
    {
        $retorno = array();
        $sql = "SELECT * FROM cronos_rrhh_datos_laborales WHERE $where";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $todos = $this->_db->fetchall();
        foreach ($todos as $personal) {
            $idProf = $personal['idProfesional'];
            $sql = "SELECT * FROM cronos_personal WHERE id = $idProf";
            $this->_db->setTipoDatos('Array');
            $this->_db->query($sql);
            $retorno[] = new Personal($this->_db->fetchRow());
        }
        return $retorno;
    }

}
