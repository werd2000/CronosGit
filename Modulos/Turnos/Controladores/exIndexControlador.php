<?php

/**
 * Clase Turnos Controlador 
 */
class indexControlador extends TurnosControlador
{

    private $_turnos;
    private $_personal;
    private $_paciente;

    public function __construct()
    {
        parent::__construct();
        $this->_turnos = $this->cargarModelo('index');
        $this->_personal = $this->cargarModelo('laboral', 'personal');
        $this->_paciente = $this->cargarModelo('pacientes');
        $this->_terapias = $this->cargarModelo('terapia', 'paciente');
        parent::getLibreria('Fechas');
    }

    public function index($fecha = '')
    {
        if (!isset($fecha) && $fecha == '') {
            $fecha = date('d-m-Y', time());
        }
        $this->_vista->setJs(array('turnos'));
        $this->_vista->setJs(array('tag-it'));
        $this->_vista->setCss(array('tagit.ui-zendesk', 'jquery.tagit'));
//        $this->_vista->setJs(array('jquery-ui'));
        $filtro = "personal.nomina='TERAPEUTAS'";
        $listaPersonal = $this->_personal->getAlgunosPersonal(false, false, 'personal.apellidos', $filtro, $campos = array('*'));
        $this->_vista->listaPersonal = $listaPersonal;
//        print_r($listaPersonal);
        $this->_vista->listaPacientes = $this->_paciente->getPacientes();
        $this->_vista->titulo = 'Turnos';
        $this->_vista->turnos = $this->getTerapiaDia($fecha);
        $this->_vista->datos['paciente'] = '';
        $this->_vista->horarioAm = array('08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30');
        $this->_vista->datos['fechaTurno'] = $fecha;
        $this->_vista->renderizar('index', 'turnos');
    }

    public function guardar($paciente, $profesional, $hora, $fecha, $salon)
    {
        try {
            if ($this->_turnos->guardarTurno(array(
                        'fecha' => fechas::getFechaBd($fecha),
                        'hora' => $hora,
                        'idProfesional' => $profesional,
                        'idPaciente' => $paciente,
                        'salon' => $salon,
                    ))) {
                echo 'Datos Guardados ' . fechas::getFechaBd($fecha);
            } else {
                echo 'No se guardo';
            }
        } catch (Exception $exc) {
            switch ($exc->getCode()) {
                case 0:
                    echo 'El registro ya existe';
                    break;
                default:
                    echo $exc->getMessage();
                    break;
            }
        }
    }

    public function getTerapiaDia($fecha)
    {
        $datos = $this->_turnos->getTurnoByFecha(fechas::getFechaBd($fecha));
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;
        while ($row = mysql_fetch_array($datos, MYSQL_ASSOC)) {
            $responce->rows[$i]['id'] = $row[id];
            $responce->rows[$i]['cell'] = array($row[id], $row[invdate], $row[name], $row[amount], $row[tax], $row[total], $row[note]);
            $i++;
        }
//        echo json_encode($responce);
        return $datos;
    }

    public function getTurnoByFechaTurnoProfesional($fecha, $turno, $profesional)
    {
        $fechaTurno['dia'] = fechas::getDia($fecha);
        $fechaTurno['mes'] = fechas::getMes($fecha);
        $fechaTurno['anio'] = fechas::getAnio($fecha);
        if ($turno == 'AM') {
            $turno = '"12:00"';
        }
        return count($this->_turnos->getTurnoByFechaTurnoProfesional($fechaTurno, $turno, $profesional));
    }

    public function controlarTerapiaPaciente($idPaciente, $idProfesional)
    {
        $terapiaProfesional = $this->_personal->getDatosLaborales($idProfesional);
//        echo $terapiaProfesional['puesto'];
        foreach ($this->_terapias->getTerapias($this->filtrarInt($idPaciente)) as $indice => $terapia) {
            $listaIdTerapia[] = $terapia['idTerapia'];
        }
        $lista = implode(',', $listaIdTerapia);
//        echo $lista;
        if (in_array($terapiaProfesional['puesto'], $listaIdTerapia)) {
            echo "ok";
        } else {
            echo "no";
        }
    }

    public function getCantidadSesionesPorSemana($idPaciente, $idTerapia)
    {
        $sesiones = 0;
        $terapias = $this->_terapias->getTerapias($this->filtrarInt($idPaciente));
//        echo '<pre>';print_r($terapias);
        foreach ($terapias as $terapia) {
            if ($terapia['idTerapia'] == $idTerapia) {
                $sesiones = $terapia['sesiones'];
            }
        }
        echo $sesiones / 4;
    }

}