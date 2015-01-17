<?php

require_once BASE_PATH . 'Modulos' . DS . 'Turnos' . DS . 'Modelos' . DS . 'IndexModelo.php';
require_once BASE_PATH . 'LibQ' . DS . 'Fechas.php';
require_once BASE_PATH . 'LibQ' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php';
require_once BASE_PATH . 'LibQ' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph_bar.php';
require_once BASE_PATH . 'LibQ' . DS . 'msg_dlg.php';
//require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'TablaDiv.php';
//require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'FilaDiv.php';
require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'CeldaDiv.php';

/**
 * Clase Turnos Controlador 
 */
class indexControlador extends TurnosControlador {

    private $_horario = array('Hora', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30');
    private $_horarioPM = array('Hora', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30');
    private $_estadoTurno = array(
        0 => 'normal',
        1 => 'personal_ausente',
        2 => 'personal_tarde',
        3 => 'paciente_ausente',
        4 => 'paciente_tarde',
        5 => 'paciente_personal_ausente',
        6 => 'paciente_personal_tarde');
    private $_normal = 0;
    private $_personal_Ausente = 1;
    private $_personal_Tarde = 2;
    private $_paciente_Ausente = 3;
    private $_paciente_Tarde = 4;
    private $_paciente_Personal_Ausente = 5;
    private $_paciente_Personal_Tarde = 6;
    private $_turnos;
//    private $_turnosDia;
    private $_personal;
    private $_paciente;

//    private $_headTabla;

    public function __construct() {
        parent::__construct();
        $this->_paciente = $this->cargarModelo('Pacientes', 'default');
        $this->_personal = $this->cargarModelo('Personal', 'default');
        //$this->_turnos = $this->cargarModelo('index');
        $this->_turnos = new indexModelo();
        parent::getLibreria('Fechas');
        parent::getLibreria('array_column');
    }
    
    private function _menuIndex($fecha = 'now', $mesResumen = 'actual', $paciente = 0)
    {
        $menu = array(
            array(
                'onclick' => '',
                'href' => "?option=Turnos&sub=index&cont=resumenPaciente&paciente="
                . $paciente . "&mes=" . $mesResumen,
                'title' => 'Resumen Mensual Paciente',
                'class' => 'lista32'
            ),
            array(
                'onclick' => '',
                'href' => "?option=Turnos&sub=index&cont=resumen&mes=" . $mesResumen,
                'title' => 'Resumen Mensual',
                'class' => 'icono-lista32'
            ),
            array(
                'onclick' => '',
                'href' => "javascript:imprimir_turnos()",
                'title' => 'Imprimir',
                'class' => 'icono-imprimir32'
            ),
            array(
                'onclick' => '',
                'href' => "?option=Turnos&sub=index&cont=eliminarTurnos&fecha=" . $fecha,
                'title' => 'Eliminar Turnos',
                'class' => 'icono-eliminar32'
            ),
            array(
                'onclick' => '',
                'href' => "?option=Turnos&sub=index&cont=copiarAlDiaSiguiente&fecha=" . $fecha,
                'title' => 'Copiar al día siguiente',
                'class' => 'copiar_a_32'
            ),
            array(
                'onclick' => '',
//                'href' => "?option=Turnos&sub=index&cont=copiarDelDiaAnterior&fecha=" . $fecha,
                'href' => "?option=Turnos&sub=index&cont=copiarDeSemanaAnterior&fecha=" . $fecha,
                'title' => 'Copiar de semana anterior',
                'class' => 'copiar_de_32'
            ),
            array(
                'onclick' => '',
                'href' => "javascript:history.back(1)",
                'title' => 'Volver',
                'class' => 'icono-volver32'
            ),
            array(
                'onclick' => '',
                'href' => "?option=Index",
                'title' => 'Inicio',
                'class' => 'icono-inicio32'
            )
        );
        return $menu;
    }

    public function index($fecha = 'now', $mesResumen = 'actual', $paciente = 0) {
        $this->_vista->_barraHerramientas = $this->_menuIndex($fecha, $mesResumen, $paciente);
        $this->_vista->estadosTurnos = $this->_estadoTurno;
        $fecha_creada = new fecha($fecha);
        $this->_vista->fecha = $fecha_creada->getFecha('-');
        $this->_vista->titulo = 'Turnos del día ';
        $this->_vista->horario = $this->_columnaHorario('AM');
        $this->_vista->horarioPM = $this->_columnaHorario('PM');
//        //Los profesionales del día y en horario AM
        $profesionalesDiaAM = $this->getListaTerapeutasDia($fecha_creada, 'AM');
        $pAM = array();
        foreach ($profesionalesDiaAM as $turno) {
            $pAM[] = $turno;
            $idPersonal = $turno->getPersonal()->getId();
            $horario = $this->_horarioProfesional($idPersonal, $fecha_creada, 'hora < \'12:00:00\'');
            $this->_vista->horarioProfesionalAM[$turno->getPersonal()->getId()] = $this->_horarioProfesional($turno->getPersonal()->getId(), $fecha_creada, 'hora < \'12:00:00\'');
        }
        $this->_vista->profesionalesDiaAM = $profesionalesDiaAM; //$pAM;
        $profesionalesDiaPM = $this->getListaTerapeutasDia($fecha_creada, 'PM');
        $pPM = array();
        foreach ($profesionalesDiaPM as $turno) {
            $pPM[] = $turno;
            $this->_vista->horarioProfesionalPM[$turno->getPersonal()->getId()] = $this->_horarioProfesional($turno->getPersonal()->getId(), $fecha_creada, 'hora > \'14:00:00\'');
        }
        $this->_vista->profesionalesDiaPM = $profesionalesDiaPM; //$pPM;
        $this->_vista->setCss(array('jquery.toolbars', 'bootstrap.icons','chosen'));
        $this->_vista->setJs(array('turnos', 'jquery.toolbar','chosen.jquery'));
        $this->_vista->setJs(array('msg_dlg'));
        
        $this->_vista->listaPacientes = $this->_turnos->getPacientes();
        $this->_vista->listaPacientes = $this->_paciente->getAlgunosPacientes(0, 0, 'apellidos', '');
        $this->_vista->listaProfesionales = $this->_personal->getAlgunosPersonal(0, 0, 'apellidos', '');

        $dlg = new LibQ\msg_dlg($msg = '');
        $this->_vista->msg_dlg = $dlg->render();
        $this->_vista->renderizar('index', 'turnos');
    }

    /**
     * Obtiene un array de celdas con el horario a mostrar mostrar
     * Los datos los obtiene del array $this->_horario
     * @return \CeldaDiv
     */
    private function _columnaHorario($turno = 'AM') {
        $h = array();
        if ($turno === 'AM') {
            foreach ($this->_horario as $hora) {
                $h[] = new CeldaDiv($hora, $this->_configurarColHorario());
            }
        } else {
            foreach ($this->_horarioPM as $hora) {
                $h[] = new CeldaDiv($hora, $this->_configurarColHorario());
            }
        }
        return $h;
    }

    /**
     * Obtiene un array con los turnos de un profesional
     * @param int $idProfesional
     * @param fecha $fecha
     * @return Array
     */
    private function _horarioProfesional($idProfesional, $fecha, $turno) {
        $turnos = $this->_turnos->getTurnoByFechaProfesionalTurno($fecha, $idProfesional, $turno);
        //$retorno[] = new CeldaDiv($turnos[0]->getPersonal(), $this->_configurarEncabezadoProfesional());
        $retorno[] = $turnos[0]->getPersonal();
        foreach ($turnos as $turno) {
            //$retorno[] = new CeldaDiv($turno, $this->_configurarHorarioProfesional());
            $retorno[] = $turno;
        }
        return $retorno;
    }

    /**
     * Obtiene una lista de terapeutas por día y por turno (AM/PM)
     * @param fecha $fecha
     * @return array
     */
    private function getListaTerapeutasDia($fecha, $turno = 'AM') {
        $result = array();
        $fechaTurno = $this->_fechaToArray($fecha);
        if ($turno === 'AM') {
            $turnosDia = $this->_turnos->getTerapeutasByFechaTurno($fechaTurno, 'hora < \'12:00:00\'');
        } else {
            $turnosDia = $this->_turnos->getTerapeutasByFechaTurno($fechaTurno, "hora > '14:00:00'");
        }
        return $turnosDia;
    }

    private function _fechaToArray($fecha) {
        $fechaTurno['dia'] = $fecha->getDia();
        $fechaTurno['mes'] = $fecha->getMes();
        $fechaTurno['anio'] = $fecha->getAnio();
        return $fechaTurno;
    }
    
    private function _menuResumen()
    {
        $menu = array(
            array(
                'onclick' => '',
                'href' => "javascript:history.back(1)",
                'title' => 'Volver',
                'class' => 'icono-volver32'
            ),
            array(
                'onclick' => '',
                'href' => "?option=Index",
                'title' => 'Inicio',
                'class' => 'icono-inicio32'
            )
        );
        return $menu;
    }

    /**
     * Muestra un resumen mensual de los turnos
     * @param int $mes
     */
    public function resumen($mes = 'actual') {
//        require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'TablaDiv.php';
        $datos = array();
        $this->_vista->setJs(array('resumen'));
        $this->_vista->_barraHerramientas = $this->_menuResumen();
        $this->_vista->titulo = 'Resumen Mensual ';
        $fecha = new fecha();
        $anio = $fecha->getAnio();
        if ($mes == 'actual') {
            $mes = $fecha->getMes();
        }
        $turnos = $this->_turnos->getTurnosByMes($mes, $anio);
        if(!is_array($turnos)){
            $turnos = array();
        }
        $encabezado = array(
            'PROFESIONAL', 'ASIGNADOS', 'AUSENTE', 'TARDE', 'HORAS'
            );
            $this->_vista->encabezado = $encabezado;
        foreach ($turnos as $turno) {
            $personal = $this->_personal->getPersonal($turno['idProfesional']);
            $datos[] = array(
                'personal' => $personal->getApellidos() . ', ' . $personal->getNombres(),
                'turnos_dados' => $turno['cant_turnos'],
                'horas' => $turno['cant_turnos'] / 2,
                'detalle' => $this->_detalleResumen($turno['idProfesional'], $mes, $anio)
            );
        }
        $options['id'] = 'tablaResumen';
        $options['class'] = 'resumen';
        $options['fila'] = array(
            'class' => 'filaResumen',
            'celda' => array(
                'class' => 'celdaResumen')
        );
        $tabla = $datos; //new TablaDiv($datos,$options);
        $this->_vista->datos = $tabla;
        $this->_vista->renderizar('resumen', 'turnos');
    }
    
    private function _detalleResumen($personal, $mes, $anio)
    {
        return $this->_turnos->getTurnosPersonalByMes($personal,$mes,$anio);
        
    }

    private function _menuResumenPaciente()
    {
        $menu = array(
            array(
                'onclick' => '',
                'href' => "javascript:history.back(1)",
                'title' => 'Volver',
                'class' => 'icono-volver32'
            ),
            array(
                'onclick' => '',
                'href' => "?option=Index",
                'title' => 'Inicio',
                'class' => 'icono-inicio32'
            )
        );
        return $menu;
    }
    /**
     * Muestra un resumen mensual de los turnos de un paciente
     * @param int mes
     * @param int paciente
     */
    public function resumenPaciente($paciente = 0, $mes = 'actual') {
        require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'TablaDiv.php';
        $this->_vista->setJs(array('resumen'));
        $this->_vista->_barraHerramientas = $this->_menuResumenPaciente();
        $this->_vista->titulo = 'Resumen Mensual Paciente ';
        $fecha = new fecha();
        $anio = $fecha->getAnio();
        if ($mes == 'actual') {
            $mes = $fecha->getMes();
        }
        $turnos = array();
        if ($paciente <> 0 ){
            $turnos = $this->_turnos->getTurnosPacientesByMes($paciente, $mes, $anio);
        }
        $datos[] = array(
            'dia' => 'DIA',
            'hora' => 'HORA',
            'profesional' => 'PROFESIONAL');
        if (is_array($turnos)) {
            $this->_vista->totalMensual = count($turnos);
            foreach ($turnos as $turno) {
                $personal = $this->_personal->getPersonal($turno['idProfesional']);
                $datos[] = array(
                    'dia' => $turno['dia'],
                    'hora' => $turno['hora'],
                    'profesional' => $personal->getApellidos() . ', ' . $personal->getNombres()
                );
            }
        }
        $options['id'] = 'tablaResumen';
        $options['class'] = 'resumen';
        $options['fila'] = array(
            'class' => 'filaResumen',
            'celda' => array(
                'class' => 'celdaResumen')
        );
        $tabla = $datos; //new TablaDiv($datos,$options);
        $this->_vista->idPaciente = $paciente;
        $this->_vista->datos = $tabla;
        $this->_vista->listaPacientes = $this->_turnos->getPacientes();
        $this->_vista->renderizar('resumenPaciente', 'turnos');
    }

    public function copiarAlDiaSiguiente($fecha = 'now') {
        echo 'fecha que trae: ' . $fecha . '<br>';
        $fechaTurno = new fecha($fecha);
        echo 'fecha creada con lo que trae: ' . $fechaTurno . '<br>';
        echo 'dia creado: ' . $fechaTurno->getDia() . '<br>';
        echo 'mes creado: ' . $fechaTurno->getMes() . '<br>';
        echo 'anio creado: ' . $fechaTurno->getAnio() . '<br>';
        $turnos = $this->_turnos->getTurnosByFecha($fechaTurno);
        $fecha_turno = new fecha($fecha);
        echo 'Nueva fecha creada con lo que trae: ' . $fechaTurno . '<br>';
        $nuevaFecha = $fechaTurno->add_date($fecha_turno->getFecha(), 1);
        echo 'Nueva fecha + 1: ' . $nuevaFecha . '<br>';
        $newFecha = new fecha($nuevaFecha);
        echo 'Nueva con la sumada: ' . $newFecha . '<br>';
        foreach ($turnos as $turno) {
            $this->_guardarTurno($newFecha, $turno);
        }
        $this->redireccionar('option=Turnos&sub=index&met=index&fecha='.$fechaTurno->getFecha());
    }

    public function copiarDelDiaAnterior($fecha = 'now') {
        echo 'fecha que trae: ' . $fecha . '<br>';
        $fechaTurno = new fecha($fecha);
        $fecha_turno = new fecha($fecha);
        echo 'fecha creada con lo que trae: ' . $fechaTurno . '<br>';
        $nuevaFecha = $fechaTurno->add_date($fecha_turno->getFecha(), -1);
        echo 'Nueva fecha - 1: ' . $nuevaFecha . '<br>';
        $newFecha = new fecha($nuevaFecha);
        echo 'Nueva con la restada: ' . $newFecha . '<br>';
        $turnos = $this->_turnos->getTurnosByFecha($newFecha);
        foreach ($turnos as $turno) {
            $this->_guardarTurno($fechaTurno, $turno);
        }
        $this->redireccionar('option=Turnos&sub=index&met=index&fecha='.$fechaTurno->getFecha());
    }

    public function copiarDeSemanaAnterior($fecha = 'now') {
        echo 'fecha que trae: ' . $fecha . '<br>';
        $fechaTurno = new fecha($fecha);
        $fecha_turno = new fecha($fecha);
        echo 'fecha creada con lo que trae: ' . $fechaTurno . '<br>';
        $nuevaFecha = $fechaTurno->add_date($fecha_turno->getFecha(), -7);
        echo 'Nueva fecha - 7: ' . $nuevaFecha . '<br>';
        $newFecha = new fecha($nuevaFecha);
        echo 'Nueva con la restada: ' . $newFecha . '<br>';
        $turnos = $this->_turnos->getTurnosByFecha($newFecha);
        foreach ($turnos as $turno) {
            $this->_guardarTurno($fechaTurno, $turno);
        }
        $this->redireccionar('option=Turnos&sub=index&met=index&fecha='.$fechaTurno->getFecha());
    }
    
    public function eliminarTurnos($fecha = 'now') {
        echo 'fecha que trae: ' . $fecha . '<br>';
        $fechaTurno = new fecha($fecha);
        echo 'fecha creada con lo que trae: ' . $fechaTurno . '<br>';
        $condicion = "anio = " . $fechaTurno->getAnio() . " AND mes = " .
                $fechaTurno->getMes() . " AND dia = " .
                $fechaTurno->getDia();
        $valor = array("idPaciente" => 0);
        echo $condicion;
        echo $valor;
        $retorno = $this->_turnos->modificarTurno($valor, $condicion);
        echo 'salida: ' . $retorno;
        parent::redireccionar("option=Turnos&sub=index&met=index&fecha=" . $fecha);
    }

    private function _guardarTurno($nuevaFecha, $turno) {
        $retorno = '';
        $nuevoTurno['dia'] = $nuevaFecha->getDia();
        $nuevoTurno['mes'] = $nuevaFecha->getMes();
        $nuevoTurno['anio'] = $nuevaFecha->getAnio();
        $nuevoTurno['hora'] = $turno->getHora();
        $nuevoTurno['idProfesional'] = $turno->getPersonal()->getId();
        if (is_a($turno->getPaciente(), 'Paciente')) {
            $nuevoTurno['idPaciente'] = $turno->getPaciente()->getId();
        } else {
            $nuevoTurno['idPaciente'] = 0;
        }
        if ($this->_ifExisteTurno($nuevoTurno)) {
            echo 'ya existe<br>';
            $condicion = "id = " . $turno->getId();
            $retorno = $this->_turnos->modificarTurno($nuevoTurno, $condicion);
        } else {
            echo 'no existe<br>';
            $retorno = $this->_turnos->guardarTurno($nuevoTurno);
        }
    }

    private function _ifExisteTurno($turno) {
        $hora = $turno['hora'];
        $idProfesional = $turno['idProfesional'];
        return $this->_turnos->getTurnoByFechaHoraProfesionalPaciente($turno, $hora, $idProfesional, $turno['idPaciente']);
    }

//    public function getTerapiaDiaHora($fecha, $hora = '08:00') {
//        $datos = $this->_turnos->getTurnoByFecha(fecha::getFechaBd($fecha), $hora);
//        $result = array_column($datos, 'idProfesional');
//        return $result;
//    }
//
//    public function getTurnoByFechaHoraProfesional($fecha, $hora, $profesional) {
//        $retorno = '';
//        echo $fecha->year($fecha->getFecha());
//        $fechaTurno['dia'] = $fecha->day($fecha->getFecha());
//        $fechaTurno['mes'] = $fecha->month($fecha->getFecha());
//        $fechaTurno['anio'] = $fecha->year($fecha->getFecha());
//        if ($profesional != 'Hora'){
//            $idProfesional = $profesional->getId();
//            $retorno = $this->_turnos->getTurnoByFechaHoraProfesional($fechaTurno, $hora, $idProfesional);
//        }
//        return $retorno;
//        
//    }
//
//    public function controlarTerapiaPaciente($idPaciente, $idProfesional) {
//        $terapiaProfesional = $this->_personal->getDatosLaborales($idProfesional);
//        foreach ($this->_terapias->getTerapias($this->filtrarInt($idPaciente)) as $indice => $terapia) {
//            $listaIdTerapia[] = $terapia['idTerapia'];
//        }
//        if (in_array($terapiaProfesional['puesto'], $listaIdTerapia)) {
//            echo "ok";
//        } else {
//            echo "no";
//        }
//    }

    public function getCantidadSesionesPorSemana($idPaciente, $idTerapia) {
        $sesiones = 0;
        $terapias = $this->_terapias->getTerapias($this->filtrarInt($idPaciente));
        foreach ($terapias as $terapia) {
            if ($terapia['idTerapia'] == $idTerapia) {
                $sesiones = $terapia['sesiones'];
            }
        }
        echo $sesiones / 4;
    }

    public function guardarTurnoProfesional() {
        if (parent::getPostParam('fecha')) {
            $fecha = new fecha(parent::getPostParam('fecha'));
        }
        if (parent::getPostParam('turno') === 'AM') {
            $turno = 'hora < \'12:00:00\'';
        } else {
            $turno = 'hora > \'14:00:00\'';
        }
        $condicion = 'anio = ' . $fecha->getAnio() . ' AND
            mes = ' . $fecha->getMes() . ' AND 
            dia = ' . $fecha->getDia() . ' AND idProfesional=' . parent::getPostParam('idDiv')
                . ' AND ' . $turno;
        $valores = array('idProfesional' => parent::getPostParam('idProfesional'));
        $retorno = $this->_turnos->editarTurnoPersonal($valores, $condicion);
        if ($retorno >= 1) {
            echo 'Los datos fueron guardados';
        } else {
            echo 'Se produjo un error y los datos no se guardaron';
        }
    }

    public function personalAusente() {
        if (parent::getPostParam('turno') === 'AM') {
            $turno = 'hora < \'12:00:00\'';
        } else {
            $turno = 'hora > \'14:00:00\'';
        }
        $fecha = parent::getPostParam('fecha');
        $fechaTurno = new fecha($fecha);
        $idProfesional = parent::getPostParam('idProfesional');
        $condicion = $turno . ' AND idProfesional=' . $idProfesional .
                ' AND mes = ' . $fechaTurno->getMes() . ' AND dia = ' . $fechaTurno->getDia() .
                ' AND anio=' . $fechaTurno->getAnio();
        $turnoBuscado = $this->_turnos->getTurnoByFechaProfesionalTurno($fechaTurno, $idProfesional, $turno);
        $estadoT = $turnoBuscado[0]->getEstadoTurno();
        if ($estadoT == 1) {
            $valores = array('estado' => 0);
        } else {
            $valores = array('estado' => 1);
        }
        $retorno = $this->_turnos->editarTurnoPersonal($valores, $condicion);
        if ($retorno >= 1) {
            echo ' Los datos fueron guardados ';
        } else {
            echo $condicion . ' Se produjo un error y los datos no se guardaron';
        }
    }

    public function guardarTurnoPaciente() {
        if (parent::getPostParam('idDiv') != '') {
            $condicion = 'id = ' . parent::getPostParam('idDiv');
            $valores = array('idPaciente' => parent::getPostParam('idPaciente'));
            $retorno = $this->_turnos->editarTurnoPaciente($valores, $condicion);
        } else {
            $valores = array(
                'idPaciente' => parent::getPostParam('idPaciente'),
                'hora' => parent::getPostParam('hora')
            );
            $retorno = $this->_turnos->editarTurnoPaciente($valores);
        }
        if ($retorno >= 1) {
            echo 'Los datos fueron guardados';
        } else {
            echo 'Se produjo un error y los datos no se guardaron';
        }
    }

    public function personal_ausente() {
        if (parent::getPostParam('id') != '') {
            $id = parent::getPostParam('id');
            $condicion = 'id = ' . $id;
            $turnoBuscado = $this->_turnos->getTurnoById($id);
            if ($turnoBuscado->getEstadoTurno() == 1) {
                $valores = array('estado' => 0);
            } else {
                $valores = array('estado' => 1);
            }
            $retorno = $this->_turnos->editarTurnoPaciente($valores, $condicion);
        }
        if ($retorno >= 1) {
            echo 'Los datos fueron guardados';
        } else {
            echo $condicion . ' Se produjo un error y los datos no se guardaron';
        }
    }

    public function personal_tarde() {
        if (parent::getPostParam('id') != '') {
            $id = parent::getPostParam('id');
            $condicion = 'id = ' . $id;
            $turnoBuscado = $this->_turnos->getTurnoById($id);
            if ($turnoBuscado->getEstadoTurno() == 2) {
                $valores = array('estado' => 0);
            } else {
                $valores = array('estado' => 2);
            }
            $retorno = $this->_turnos->editarTurnoPaciente($valores, $condicion);
        }
        if ($retorno >= 1) {
            echo 'Los datos fueron guardados';
        } else {
            echo $condicion . ' Se produjo un error y los datos no se guardaron';
        }
    }

    public function paciente_ausente() {
        if (parent::getPostParam('id') != '') {
            $id = parent::getPostParam('id');
            $condicion = 'id = ' . $id;
            $turnoBuscado = $this->_turnos->getTurnoById($id);
            if ($turnoBuscado->getEstadoTurno() == 3) {
                $valores = array('estado' => 0);
            } else {
                $valores = array('estado' => 3);
            }
            $retorno = $this->_turnos->editarTurnoPaciente($valores, $condicion);
        }
        if ($retorno >= 1) {
            echo 'Los datos fueron guardados';
        } else {
            echo $condicion . ' Se produjo un error y los datos no se guardaron';
        }
    }

    public function paciente_tarde() {
        if (parent::getPostParam('id') != '') {
            $id = parent::getPostParam('id');
            $condicion = 'id = ' . $id;
            $turnoBuscado = $this->_turnos->getTurnoById($id);
            if ($turnoBuscado->getEstadoTurno() == 4) {
                $valores = array('estado' => 0);
            } else {
                $valores = array('estado' => 4);
            }
            $retorno = $this->_turnos->editarTurnoPaciente($valores, $condicion);
        }
        if ($retorno >= 1) {
            echo 'Los datos fueron guardados';
        } else {
            echo $condicion . ' Se produjo un error y los datos no se guardaron';
        }
    }
    
    public function reunion_equipo() {
        $retorno = 0;
        if (parent::getPostParam('id') != '') {
            $id = parent::getPostParam('id');
            $condicion = 'id = ' . $id;
            $turnoBuscado = $this->_turnos->getTurnoById($id);
            $valores = array('observaciones'=>$turnoBuscado->getObservaciones() . ' Reunión de equipo');
            $retorno = $this->_turnos->editarTurnoPaciente($valores, $condicion);
        }
        if ($retorno >= 1) {
            echo 'Los datos fueron guardados';
        } else {
            echo $condicion . ' Se produjo un error y los datos no se guardaron';
        }
    }
    
    public function devolucion() {
        $retorno = 0;
        if (parent::getPostParam('id') != '') {
            $id = parent::getPostParam('id');
            $condicion = 'id = ' . $id;
            $turnoBuscado = $this->_turnos->getTurnoById($id);
            $valores = array('observaciones'=>$turnoBuscado->getObservaciones() . ' Devolucion');
            $retorno = $this->_turnos->editarTurnoPaciente($valores, $condicion);
        }
        if ($retorno >= 1) {
            echo 'Los datos fueron guardados';
        } else {
            echo $condicion . ' Se produjo un error y los datos no se guardaron';
        }
    }
    
    public function getPacientesProfesional() {
        $retorno = 0;
        if (parent::getPostParam('idProfesional') != '') {
            $id = parent::getPostParam('idProfesional');
            $idPaciente = parent::getPostParam('idPaciente');
            $condicion = 'id = ' . $id;
            $profesionalBuscado = $this->_turnos->getProfesional($id);
            $pac = $this->_turnos->getPacientesPersonal($id);
            sort($pac);
            $combo[] = '<option value="0" label=""></option>';
            foreach ($pac as $paciente) {
                if ($paciente->getId() > 0 ){
                    if ($paciente->getId()==$idPaciente){
                        $combo[] = '<option value="' .$paciente->getId() . '" label="' . 
                            $paciente->getApellidos() . ', ' . $paciente->getNombres() .
                            '" selected="selected">' . $paciente->getApellidos() . ', ' . $paciente->getNombres() .'</option>';
                    }else{
                        $combo[] = '<option value="' .$paciente->getId() . '" label="' . 
                            $paciente->getApellidos() . ', ' . $paciente->getNombres() .
                            '">' . $paciente->getApellidos() . ', ' . $paciente->getNombres() .'</option>';
                    }
                }
            }
        }
        echo implode('', $combo);
        
    }

    public function guardarObservaciones_turno() {
        if (parent::getPostParam('idTurno') != '') {
            $id = parent::getPostParam('idTurno');
            $observaciones = parent::getPostParam('observaciones');
            $condicion = 'id = ' . $id;
            $turnoBuscado = $this->_turnos->getTurnoById($id);
            if ($turnoBuscado->getObservaciones() == '') {
                $valores = array('observaciones' => $observaciones);
            } else {
                $observaciones = $observaciones;
                $valores = array('observaciones' => $observaciones);
            }
            $retorno = $this->_turnos->editarTurnoPaciente($valores, $condicion);
        }
        if ($retorno >= 1) {
            echo 'Las observaciones fueron guardados';
        } else {
            echo $condicion . ' Se produjo un error y los datos no se guardaron';
        }
    }

    private function _configurarColHorario() {
        $config['class'] = 'cel_horario turno';
        return $config;
    }

    private function _configurarHorarioProfesional() {
        $config['class'] = 'turno_paciente';
        $config['idPaciente'] = '_idPaciente';
        $config['id'] = '_id';
        $config['css_condicional'] = 'background-color: #FDFAB5;';
        $config['css_condicion'] = array('_estadoPaciente' => 'EVALUACION');
        $config['mostrar'] = array('_objPaciente');
        return $config;
    }

    private function _configurarEncabezadoProfesional() {
        $config['class'] = 'cel_horario turno_profesional';
        $config['idProfesional'] = '_id';
        $config['mostrar'] = array('_apellidos', '_nombres');
        return $config;
    }

//    /**
//     * Configuracion de la tabla 
//     */
//    private function _configurarTabla() {
//        //$config['id'] = 'Turnos';
//        $config['class'] = 'tabla';
//        $config['fila'] = array(
//            'primero' => array('_objProfesional'),
//            //'class' => 'filaEncabezado',
//            'celda' => array(
//                'class' => 'turno',
////                'idPaciente' => '_idPaciente',
////                'profesional' => '_idProfesional',
////                'name' => '_id',
////                'hora' => '_hora',
//                'mostrar' => array('_objPaciente')
//            )
//        );
//        return $config;
//    }
//    /**
//     * Devuelve una fila en forma de tabla para mostrar el encabezado de la tabla
//     * @param array $turnos
//     * @return \TablaDiv
//     */
//    private function _encabezadoTabla($turnos) {
//        $config = $this->_configurarEncabezadoTabla();
//        $this->_headTabla[] = 'Hora';
//        foreach ($turnos as $turno) {
//            if (!in_array($turno->getPersonal(), $this->_headTabla)) {
//                $this->_headTabla[] = $turno->getPersonal();
//            }
//        }
//        $filaEncabezado = new FilaDiv($this->_headTabla, $config['fila']);
//        $encabezado = new TablaDiv($filaEncabezado, $config);
//        return $encabezado;
//    }
}
