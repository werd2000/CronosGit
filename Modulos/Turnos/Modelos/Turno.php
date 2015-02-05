<?php
require_once MODS_PATH . 'Paciente' . DS .'Modelos' . DS . 'indexModelo.php';
/**
 * Clase Turno
 *
 * @author WERD
 */
class Turnos_Modelos_Turno {

    private $_id;
    private $_anio;
    private $_mes;
    private $_dia;
    private $_hora;
    private $_idProfesional;
    private $_objProfesional;
    private $_idPaciente;
    private $_objPaciente;
    private $_eliminado;
    private $_estadoPaciente;
    private $_estadoTurno;
    private $_observaciones;

    public function __construct($datos) {
        $this->_id = $this->_obtenerDato($datos, 'id');
        $this->_anio = $this->_obtenerDato($datos, 'anio');
        $this->_mes = $this->_obtenerDato($datos, 'mes');
        $this->_dia = $this->_obtenerDato($datos, 'dia');
        $this->_hora = $this->_obtenerDato($datos, 'hora');
        $this->_idProfesional = $this->_obtenerDato($datos, 'idProfesional');
        $this->_objProfesional = $this->_getProfesional($datos['idProfesional']);
        $this->_idPaciente = $this->_obtenerDato($datos, 'idPaciente');
        $this->_objPaciente = $this->_getPaciente($this->_idPaciente);
        $this->_eliminado = $this->_obtenerDato($datos, 'eliminado');
        $this->_estadoTurno = $this->_obtenerDato($datos, 'estado');
        $this->_estadoPaciente = $this->getEstadoPaciente();
        $this->_observaciones = $this->_obtenerDato($datos, 'observaciones');
    }

    private function _obtenerDato($array, $dato) {
        $retorno = '';
        if (isset($array[$dato])) {
            $retorno = $array[$dato];
        }
        return $retorno;
    }

    private function _getProfesional($id) {
        $retorno = 0;
        if ($id != '' AND $id != 0) {
            $personalModelo = new Modelos_personalModelo();
            $retorno = $personalModelo->getPersonal($id);
        }
        return $retorno;
    }

    private function _getPaciente($id) {
        $paciente = NULL;
        if ($id != '' AND $id != 0) {
            $pacienteModelo = new Paciente_Modelos_indexModelo();
            $paciente = $pacienteModelo->getPaciente("id=$id");
        }
        return $paciente;
    }

    public function getId() {
        return $this->_id;
    }

    public function getAnio() {
        return $this->_anio;
    }

    public function getMes() {
        return $this->_mes;
    }

    public function getDia() {
        return $this->_dia;
    }

    public function getHora() {
        return $this->_hora;
    }

    public function getPaciente() {
        $retorno = '';
        if (!is_null($this->_objPaciente)) {
            $retorno = $this->_objPaciente;
        }
        return $retorno;
    }

    public function getPersonal() {
        $retorno = '';
        if (!is_null($this->_objProfesional)) {
            $retorno = $this->_objProfesional;
        }
        return $retorno;
    }
    
    public function getEstadoTurno(){
        return $this->_estadoTurno;
    }


    public function getEstadoPaciente(){
        if($this->getPaciente() != NULL){
            return $this->getPaciente()->getEstado();
        }
    }
    
    public function getObservaciones(){
        return $this->_observaciones;
    }

    public function __toString() {
        $retorno = '';
        if (is_a($this->_objPaciente, 'Paciente')) {
            $retorno = $this->_objPaciente->getApellidos();
        }
        return $retorno;
    }

    public function __get($name) {
        return $this->$name;
    }

}
