<?php

require_once APP_PATH . 'Modelo.php';
require_once 'Turno.php';
require_once BASE_PATH . 'LibQ' . DS . 'Fechas.php';

/**
 * Clase Modelo Turnos que extiende de la clase Modelo
 */
class Turnos_Modelos_indexModelo extends App_Modelo {

    private $_verEliminados = false;

    /**
     * Clase constructora 
     */
    public function __construct() {
        parent::__construct();
    }

    public function getTurnoByFechaHora($fecha, $hora) {
        $sql = 'SELECT id, hora, idPaciente, idProfesional
            FROM cronos_turnos_terapias2 WHERE
            anio = ' . $fecha->getDia() . ' AND
            mes = ' . $fecha->getMes() . ' AND 
            dia = ' . $fecha->getAnio() . ' AND 
            hora = "' . $hora . '"';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchAll();
        return new Turno($retorno);
    }

    /**
     * Obtiene un array con los turnos del día
     * @param date $fecha
     * @return \Turno
     */
    public function getTurnosByFecha($fecha) {
        $turno = array();
        $sql = 'SELECT * FROM cronos_turnos_terapias2 WHERE
            anio = ' . $fecha->getAnio() . ' AND
            mes = ' . $fecha->getMes() . ' AND 
            dia = ' . $fecha->getDia();
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchAll();
        //echo '<pre>';print_r($retorno);
        if (is_array($retorno)) {
            foreach ($retorno as $dato) {
                $turno[] = new Turnos_Modelos_Turno($dato);
            }
        }
        return $turno;
    }
    
    /**
     * Obtiene un array con los turnos asignados del mes
     * @param date $fecha
     * @return \Turno
     */
    public function getTurnosAsignadosByMes($mes,$anio) {
        $turno = array();
        $sql = 'SELECT idProfesional, count(id) as cant_turnos
            FROM cronos_turnos_terapias2 
            WHERE anio = ' . $anio . ' AND
            mes = ' . $mes . ' AND 
            idPaciente <> 0 GROUP BY idProfesional';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchAll();
        return $retorno;
    }
    
    /**
     * Obtiene un array con los turnos del día
     * @param date $fecha
     * @return \Turno
     */
    public function getTurnosByMes($mes,$anio) {
        $turno = array();
//        SELECT idProfesional, count(id) FROM `cronos_turnos_terapias2` WHERE `mes`=3 group by `idProfesional`
        $sql = 'SELECT idProfesional, count(id) as cant_turnos
            FROM cronos_turnos_terapias2 
            WHERE anio = ' . $anio . ' AND
            mes = ' . $mes . ' AND 
            estado <> 1 AND estado <> 5 AND
            idPaciente <> 0 GROUP BY idProfesional';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchAll();
        return $retorno;
    }
    
    /**
     * Obtiene un array con los turnos ausentes del mes
     * @param date $fecha
     * @return \Turno
     */
    public function getTurnosAusentesByMes($mes,$anio) {
        $turno = array();
//        SELECT idProfesional, count(id) FROM `cronos_turnos_terapias2` WHERE `mes`=3 group by `idProfesional`
        $sql = 'SELECT idProfesional, count(id) as cant_turnos
            FROM cronos_turnos_terapias2 
            WHERE anio = ' . $anio . ' AND
            mes = ' . $mes . ' AND 
            estado <> 1 AND estado <> 5 AND
            idPaciente <> 0 GROUP BY idProfesional';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchAll();
        return $retorno;
    }

    /**
     * Obtiene un array con los turnos del día
     * @param date $fecha
     * @return \Turno
     */
    public function getTurnosPacientesByMes($paciente,$mes,$anio) {
        $turno = array();
//        SELECT idProfesional, count(id) FROM `cronos_turnos_terapias2` WHERE `mes`=3 group by `idProfesional`
        $sql = 'SELECT idPaciente, dia, hora, idProfesional
            FROM cronos_turnos_terapias2 
            WHERE anio = ' . $anio . ' AND
            mes = ' . $mes . ' AND 
            idPaciente = ' . $paciente;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchAll();
        return $retorno;
    }
    
    /**
     * Obtiene un array con los turnos de un profesional en el mes
     * @param date $fecha
     * @return \Turno
     */
    public function getTurnosPersonalByMes($personal,$mes,$anio) {
        $turno = array();
//        SELECT idProfesional, count(id) FROM `cronos_turnos_terapias2` WHERE `mes`=3 group by `idProfesional`
        $sql = 'SELECT dia, count(id) as cant_turnos
            FROM cronos_turnos_terapias2 
            WHERE anio = ' . $anio . ' AND
            mes = ' . $mes . ' AND 
            estado <> 1 AND estado <> 5 AND
            idPaciente <> 0 AND
            idProfesional = ' . $personal . ' GROUP BY dia';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchAll();
        return $retorno;
    }

    /**
     * Obtiene la lista de terapeutas en la fecha
     * @param date $fecha
     * @return array 
     */
    public function getTerapeutasByFechaTurno($fecha,$turnoBuscado) {
        $turno = array();
        $sql = 'SELECT DISTINCT idProfesional
            FROM cronos_turnos_terapias2 WHERE
            anio = ' . $fecha['anio'] . ' AND
            mes = ' . $fecha['mes'] . ' AND 
            dia = ' . $fecha['dia'] . ' AND ' . $turnoBuscado;
//        echo $sql;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchAll();
        if (is_array($retorno)) {
            foreach ($retorno as $dato) {
                $turno[] = new Turnos_Modelos_Turno($dato);
            }
        }
        return $turno;
    }

    public function getTurnoByFechaHoraProfesional($fecha, $hora, $profesional) {
        $sql = 'SELECT *
            FROM cronos_turnos_terapias2 WHERE
            anio = ' . $fecha['anio'] . ' AND
            mes = ' . $fecha['mes'] . ' AND 
            dia = ' . $fecha['dia'] . ' AND 
            idProfesional = ' . $profesional . ' AND 
            hora = "' . $hora . '"';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchRow();
        
        if (!is_null($retorno)) {
            $turno = new Turno($retorno);
        } else {
            $turno = 'ERROR';
        }
        return $turno;
    }

    public function getTurnoByFechaHoraProfesionalPacienteSalon($fecha, $hora, $profesional, $paciente, $salon) {
        $sql = 'SELECT * FROM cronos_turnos_terapias WHERE
            anio = ' . $fecha['anio'] . ' AND
            mes = ' . $fecha['mes'] . ' AND 
            dia = ' . $fecha['dia'] . ' AND 
            hora = "' . $hora . '" AND 
            idProfesional = ' . $profesional . ' AND 
            idPaciente = ' . $paciente . ' AND 
            salon = ' . $salon;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }

    public function getTurnoByFechaHoraProfesionalPaciente(LibQ_Fecha $fecha, $hora, $profesional, $paciente) {
        $sql = 'SELECT * FROM cronos_turnos_terapias2 WHERE
            anio = ' . $fecha->getAnio() . ' AND
            mes = ' . $fecha->getMes() . ' AND 
            dia = ' . $fecha->getDia() . ' AND 
            hora = "' . $hora . '" AND
            idProfesional = ' . $profesional . ' AND 
            idPaciente = ' . $paciente;
//        echo $sql;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }

    public function getTurnoByFechaTurnoProfesional($fecha, $turno, $profesional) {
        $sql = 'SELECT * FROM cronos_turnos_terapias2 WHERE
            anio = ' . $fecha['anio'] . ' AND
            mes = ' . $fecha['mes'] . ' AND 
            dia = ' . $fecha['dia'] . ' AND 
            idProfesional = ' . $profesional . ' AND 
            hora <= ' . $turno;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
    }

    public function getTurnoByFechaProfesionalPaciente($fecha, $profesional, $paciente) {
        return;
    }

    public function getTurnoByFechaProfesional($fecha, $profesional) {
        $turno = array();
        $sql = 'SELECT * FROM cronos_turnos_terapias2 WHERE
            dia = ' . $fecha->getDia() . ' AND
            mes = ' . $fecha->getMes() . ' AND 
            anio = ' . $fecha->getAnio() . ' AND 
            idProfesional = ' . $profesional . ' ORDER BY hora';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchall();
        if (is_array($retorno)) {
            foreach ($retorno as $dato) {
                $turno[] = new Turno($dato);
            }
        }
        return $turno;
    }
    
    public function getTurnoByFechaProfesionalTurno($fecha, $profesional, $turnoH) {
        $turno = array();
        $sql = 'SELECT * FROM cronos_turnos_terapias2 WHERE
            dia = ' . $fecha->getDia() . ' AND
            mes = ' . $fecha->getMes() . ' AND 
            anio = ' . $fecha->getAnio() . ' AND 
            idProfesional = ' . $profesional . ' AND ' . $turnoH . ' ORDER BY hora';
//        echo $sql;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchall();
        if (is_array($retorno)) {
            foreach ($retorno as $dato) {
                $turno[] = new Turnos_Modelos_Turno($dato);
            }
        }
        return $turno;
    }
    
    public function getTurnoById($id) {
        $sql = 'SELECT * FROM cronos_turnos_terapias2 WHERE
            id = ' . $id . ' ORDER BY hora';
//        echo $sql;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $retorno = $this->_db->fetchRow();
        $turno = new Turno($retorno);
        return $turno;
    }

    public function guardarTurno($valores) {
        return $this->_db->insert('cronos_turnos_terapias2', $valores);
    }
    
    public function modificarTurno($valores, $condicion) {
        return $this->_db->editar('cronos_turnos_terapias2', $valores, $condicion);
    }
    public function editarTurnoPersonal(array $valores, $condicion) {
        return $this->_db->editar('cronos_turnos_terapias2', $valores, $condicion);
    }

    public function editarTurnoPaciente(array $valores, $condicion) {
        return $this->_db->editar('cronos_turnos_terapias2', $valores, $condicion);
    }
    
    public function getProfesional($id)
    {
        $id = (int) $id;
        $sql = "select * from cronos_personal where id = $id";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
//        print_r(new Personal($this->_db->fetchRow()));
        require_once BASE_PATH . 'Modulos' . DS . 'Personal' . DS . 'Modelos' . DS . 'Personal.php';
        return new Personal($this->_db->fetchRow());
    }
    
    public function getPacientes()
    {
        require_once BASE_PATH . 'Modulos' . DS . 'Paciente' . DS . 'Modelos' . DS . 'Paciente.php';
        $this->_verEliminados = 0;
        $sql = "select * from cronos_pacientes where eliminado = $this->_verEliminados order by apellidos";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $lista = $this->_db->fetchall();
        if(is_array($lista)){
            foreach ($lista as $pac) {
                $pacientes[] = new Paciente_Modelos_Paciente($pac);
            }
        }
        return $pacientes;
    }
    
    public function getPacientesPersonal($idProfesional)
    {
        require_once BASE_PATH . 'Modulos' . DS . 'Paciente' . DS . 'Modelos' . DS . 'Paciente.php';
        $lista = NULL;
        $this->_verEliminados = 0;
        $sql = "select * from cronos_pacientes_terapia where idProfesional = $idProfesional";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $todos = $this->_db->fetchall();
        if (is_array($todos)){
            foreach ($todos as $pac) {
                $sql2 = "select * from cronos_pacientes where id = " . $pac['idPaciente'];
                $this->_db->setTipoDatos('Array');
                $this->_db->query($sql2);
                $paciente = $this->_db->fetchrow();
                $lista[] = new Paciente_Modelos_Paciente($paciente);
            }
        }
        return $lista;
    }

}
