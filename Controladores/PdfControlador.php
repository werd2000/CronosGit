<?php
require_once MODS_PATH . 'Paciente' . DS . 'Modelos' . DS . 'IndexModelo.php';
require_once BASE_PATH . 'LibQ/fpdf_ips.php';

class Controladores_PdfControlador extends App_Controlador
{

    private $_pdf;
    private $_modeloPaciente;

    public function __construct()
    {
        parent::__construct();
        parent::getLibreria('fpdf_ips');
        parent::getLibreria('Fechas');
        $this->_pdf = new FPDF_ips();
    }

    public function index()
    {
        ;
    }

    public function pedidoIps($id)
    {
        $this->_modeloPaciente = new Paciente_Modelos_indexModelo();
        $paciente = $this->_modeloPaciente->getPaciente("id = $id");
        $this->_pdf->AddPage('p', 'legal');
        $this->_pdf->SetFont('Helvetica', 'B', 12);
        $this->_pdf->Cell(0, -40, 'SOLICITUD DE PRESTACION ESPECIAL', 0, 1, 'C');
        $this->_pdf->Cell(0, 60, utf8_decode('CENTRO:  PEQUEÑO HOGAR S.R.L.'), 0, 1);
        $this->_pdf->SetFont('Helvetica', '', 12);
        $this->_pdf->Cell(130, -45, utf8_decode('Nombre y Apellido: ' . utf8_decode($paciente->getApellidos() .
                                ', ' . utf8_decode($paciente->getNombres()))), 0, 0);
        $this->_pdf->Cell(50, -45, utf8_decode('DNI: ' . utf8_decode($paciente->getNro_doc())), 0, 1);
        $this->_pdf->Cell(60, 60, utf8_decode('Nº de Afiliado: ' . utf8_decode($paciente->getOSocial()->getNro_afiliado())), 0, 0);
        $this->_pdf->Cell(70, 60, utf8_decode('Fecha de Nacimiento: ' . utf8_decode($paciente->getFecha_nac())), 0, 0);
        $this->_pdf->Cell(50, 60, 'Edad: ' . utf8_decode($paciente->getEdad()) . utf8_decode(' Años'), 0, 1);
        $this->_pdf->Cell(0, -45, utf8_decode('Diagnóstico: ') . utf8_decode($paciente->getDiagnostico()), 0, 1);
        $mes_anio = $_GET['getV'];
        $fecha = new LibQ_Fecha('now');
        if($mes_anio != ''){
            $mes_actual = explode('/', $mes_anio);
            $mes = $mes_actual[0];
        }else{
            if ($fecha->getMes() == 12){
                $mes = 1;
            }else{
                $mes = $fecha->getMes() + 1;
            }
        }
        $this->_pdf->Cell(0, 60, utf8_decode('Corresponde al més de: ') . $mes . '/' . $fecha->getAnio(), 0, 1);
        $this->_pdf->Cell(0, -45, utf8_decode('Solicito atención permanente en Centro Interdisciplinario en las siguientes áreas: '), 0, 1);
        $this->_pdf->Cell(10);
        $this->_pdf->Cell(70, 60, utf8_decode('TERAPIAS'), 0, 0);
        $this->_pdf->Cell(0, 60, utf8_decode('CANTIDAD DE SESIONES'), 0, 1);
        $i = 0;
        foreach ($paciente->getTerapias() as $terapia) {
            if ($i == 0) {
                $this->_pdf->Cell(10);
                $this->_pdf->Cell(70, -45, utf8_decode($terapia->getTerapia()), 0, 0);
                $this->_pdf->Cell(0, -45, utf8_decode($terapia->getSesiones()), 0, 1);
                $i = 1;
            } else {
                $this->_pdf->Cell(10);
                $this->_pdf->Cell(70, 60, utf8_decode($terapia->getTerapia()), 0, 0);
                $this->_pdf->Cell(0, 60, utf8_decode($terapia->getSesiones()), 0, 1);
                $i = 0;
            }
        }

        $this->_pdf->Cell(10);
        $this->_pdf->setY(150);
        $this->_pdf->Cell(70, 60, utf8_decode($paciente->getOSocial()->getObservaciones()), 0, 1);
        $this->_pdf->Cell(0, 0, utf8_decode('Firma y Aclaracion Responsable'), 0, 1);
        $this->_pdf->Line(10, 235, 190, 235);
        $this->_pieIps($this->_pdf);
        /** Reverso */
        $this->_pdf->AddPage('p', 'legal');
        $this->_pdf->setY(50);
        foreach ($paciente->getTerapias() as $terapia) {
            $this->_pdf->Cell(0, 7, 'Terapia: ' . $terapia->getTerapia() . '         Firma del Profesional', 1, 1, '');
            $this->_pdf->Cell(0, 7, 'Fecha                                                               Firma del Padre', 1, 1, '');
            for ($fila = 1; $fila <= $terapia->getSesiones(); $fila++) {
                $this->_pdf->Cell(0, 6, ' ', 1, 1, '');
            }
            $this->_pdf->Cell(0, 5, ' ', 0, 1, '');
        }

        $this->_pdf->Output();
    }
    
    public function pedidosIps()
    {
        $this->_modeloPaciente = $this->cargarModelo('index', 'Paciente');
        $pacientes = $this->_modeloPaciente->getPacientesByOs(1);
        foreach ($pacientes as $paciente) {
                $this->_pdf->AddPage('p', 'legal');
                $this->_pdf->SetFont('Helvetica', 'B', 12);
                $this->_pdf->Cell(0, -40, 'SOLICITUD DE PRESTACION ESPECIAL', 0, 1, 'C');
                $this->_pdf->Cell(0, 60, utf8_decode('CENTRO:  PEQUEÑO HOGAR S.R.L.'), 0, 1);
                $this->_pdf->SetFont('Helvetica', '', 12);
                $this->_pdf->Cell(130, -45, utf8_decode('Nombre y Apellido: ' . utf8_decode($paciente->getApellidos() .
                                        ', ' . utf8_decode($paciente->getNombres()))), 0, 0);
                $this->_pdf->Cell(50, -45, utf8_decode('DNI: ' . utf8_decode($paciente->getNro_doc())), 0, 1);
                $this->_pdf->Cell(60, 60, utf8_decode('Nº de Afiliado: ' . utf8_decode($paciente->getOSocial()->getNro_afiliado())), 0, 0);
                $this->_pdf->Cell(70, 60, utf8_decode('Fecha de Nacimiento: ' . utf8_decode($paciente->getFecha_nac())), 0, 0);
                $this->_pdf->Cell(50, 60, 'Edad: ' . utf8_decode($paciente->getEdad()) . utf8_decode(' Años'), 0, 1);
                $this->_pdf->Cell(0, -45, utf8_decode('Diagnóstico: ') . utf8_decode($paciente->getDiagnostico()), 0, 1);
                $mes_anio = $_GET['getV'];
                $fecha = new LibQ_Fecha('now');
                if($mes_anio != ''){
                    $mes_actual = explode('/', $mes_anio);
                    $mes = $mes_actual[0];
                }else{
                    if ($fecha->getMes() == 12){
                        $mes = 1;
                    }else{
                        $mes = $fecha->getMes() + 1;
                    }
                }
                $this->_pdf->Cell(0, 60, utf8_decode('Corresponde al més de: ') . $mes . '/' . $fecha->getAnio(), 0, 1);
                $this->_pdf->Cell(0, -45, utf8_decode('Solicito atención permanente en Centro Interdisciplinario en las siguientes áreas: '), 0, 1);
                $this->_pdf->Cell(10);
                $this->_pdf->Cell(70, 60, utf8_decode('TERAPIAS'), 0, 0);
                $this->_pdf->Cell(0, 60, utf8_decode('CANTIDAD DE SESIONES'), 0, 1);
                $i = 0;
                foreach ($paciente->getTerapias() as $terapia) {
                    if ($i == 0) {
                        $this->_pdf->Cell(10);
                        $this->_pdf->Cell(70, -45, utf8_decode($terapia->getTerapia()), 0, 0);
                        $this->_pdf->Cell(0, -45, utf8_decode($terapia->getSesiones()), 0, 1);
                        $i = 1;
                    } else {
                        $this->_pdf->Cell(10);
                        $this->_pdf->Cell(70, 60, utf8_decode($terapia->getTerapia()), 0, 0);
                        $this->_pdf->Cell(0, 60, utf8_decode($terapia->getSesiones()), 0, 1);
                        $i = 0;
                    }
                }

                $this->_pdf->Cell(10);
                $this->_pdf->setY(150);
                $this->_pdf->Cell(70, 60, utf8_decode($paciente->getOSocial()->getObservaciones()), 0, 1);
                $this->_pdf->Cell(0, 0, utf8_decode('Firma y Aclaracion Responsable'), 0, 1);
                $this->_pdf->Line(10, 235, 190, 235);
                $this->_pieIps($this->_pdf);
                /** Reverso */
                $this->_pdf->AddPage('p', 'legal');
                $this->_pdf->setY(50);
                foreach ($paciente->getTerapias() as $terapia) {
                    $this->_pdf->Cell(0, 7, 'Terapia: ' . $terapia->getTerapia() . '         Firma del Profesional', 1, 1, '');
                    $this->_pdf->Cell(0, 7, 'Fecha                                                               Firma del Padre', 1, 1, '');
                    for ($fila = 1; $fila <= $terapia->getSesiones(); $fila++) {
                        $this->_pdf->Cell(0, 6, ' ', 1, 1, '');
                    }
                    $this->_pdf->Cell(0, 5, ' ', 0, 1, '');
                }
        }

        $this->_pdf->Output();
    }

    private function _pieIps($pdf)
    {
        $pdf->Cell(0, 65, 'DATOS A COMPLETAR POR EL PROFESIONAL AUDITOR DEL IPS', 0, 1, '');
        $pdf->Cell(0, -45, 'Observaciones: ............................................................................................................................', 0, 1, '');
        $pdf->Cell(0, 60, 'En base a la solicitud requerida por la responsable solicitante considero que SI - NO corresponde', 0, 1, '');
        $pdf->Cell(0, -45, 'Fecha: ................/.............../.................', 0, 1, '');
        $pdf->Cell(0, 60, utf8_decode('(La presente autorización debe acompañar a la facturación)'), 0, 1, '');
    }

    public function contactos_pacientes($id)
    {
        $this->_modeloPaciente = $this->cargarModelo('index', 'Paciente');
        $terapiaPaciente = $this->cargarModelo('contacto', 'Paciente');
        $paciente = $this->_modeloPaciente->getListadoContactos();
        $this->_pdf->AddPage('p', 'A4');
//        $this->_cabeceraIps($this->_pdf);
        $this->_pdf->SetFont('Helvetica', 'B', 12);
        $this->_pdf->Cell(0, -45, 'SOLICITUD DE PRESTACION ESPECIAL', 0, 1, 'C');
        $this->_pdf->Cell(0, 60, utf8_decode('CENTRO:  PEQUEÑO HOGAR S.R.L.'), 0, 1);
        $this->_pdf->SetFont('Helvetica', '', 12);
        $this->_pdf->Cell(120, -45, utf8_decode('Nombre y Apellido: ' . utf8_decode($paciente['apellidos'] .
                                ', ' . utf8_decode($paciente['nombres']))), 0, 0);
        $this->_pdf->Cell(50, -45, utf8_decode('DNI: ' . utf8_decode($paciente['nro_doc'])), 0, 1);
        $this->_pdf->Cell(50, 60, utf8_decode('Nº de Afiliado: ' . utf8_decode($paciente['nro_doc'])), 0, 0);
        $this->_pdf->Cell(70, 60, utf8_decode('Fecha de Nacimiento: ' . utf8_decode($paciente['fecha_nac'])), 0, 0);
        $this->_pdf->Cell(50, 60, 'Edad: ' . utf8_decode($paciente['fecha_nac']) . utf8_decode(' Años'), 0, 1);
        $this->_pdf->Cell(0, -45, utf8_decode('Diagnóstico: ') . utf8_decode($paciente['diagnostico']), 0, 1);
        $this->_pdf->Cell(0, 60, utf8_decode('Corresponde al més de: ') . date('n-Y'), 0, 1);
        $this->_pdf->Cell(0, -45, utf8_decode('Solicito atención permanente en Centro Interdisciplinario en las siguientes áreas: '), 0, 1);
        $this->_pdf->Cell(10);
        $this->_pdf->Cell(70, 60, utf8_decode('TERAPIAS'), 0, 0);
        $this->_pdf->Cell(0, 60, utf8_decode('CANTIDAD DE SESIONES'), 0, 1);
        $terapias = $terapiaPaciente->getTerapias($id);
        $i = 0;
        foreach ($terapias as $terapia) {
            if ($i == 0) {
                $this->_pdf->Cell(10);
                $this->_pdf->Cell(70, -45, utf8_decode($terapia['terapia']), 0, 0);
                $this->_pdf->Cell(0, -45, utf8_decode($terapia['sesiones']), 0, 1);
                $i = 1;
            } else {
                $this->_pdf->Cell(10);
                $this->_pdf->Cell(70, 60, utf8_decode($terapia['terapia']), 0, 0);
                $this->_pdf->Cell(0, 60, utf8_decode($terapia['sesiones']), 0, 1);
                $i = 0;
            }
        }
        $oSocial = $obraSocialPaciente->getOSocial($id);
        $this->_pdf->Cell(10);
        $this->_pdf->Cell(70, 60, utf8_decode($oSocial['observaciones']), 0, 1);
        $this->_pdf->Cell(0, 0, utf8_decode('Firma y Aclaracion Responsable'), 0, 1);
        $this->_pdf->Line(10, 235, 190, 235);
        $this->_pieIps($this->_pdf);
        /** Reverso */
        $this->_pdf->AddPage('p', 'legal');
        foreach ($terapias as $terapia) {
            $this->_pdf->Cell(0, 10, 'Terapia: ' . $terapia['terapia'] . '         Firma del Profesional', 1, 1, '');
            $this->_pdf->Cell(0, 10, 'Fecha                                                               Firma del Padre', 1, 1, '');
            for ($fila = 1; $fila <= $terapia['sesiones']; $fila++) {
                $this->_pdf->Cell(0, 10, ' ', 1, 1, '');
            }
            $this->_pdf->Cell(0, 10, ' ', 0, 1, '');
        }

        $this->_pdf->Output();
    }
    
    public function facturacion($id)
    {
        $this->_modeloPaciente = $this->cargarModelo('index', 'Paciente');
        $pacientes = $this->_modeloPaciente->getPacientesByOs($id);
//        $i = $id;
//        $i++;
        $this->_pdf->AddPage('p', 'legal');
//        $this->_cabeceraPhSrl($this->_pdf);
//        if ($id = 1){
            $this->_facturaIps($this->_pdf, $pacientes);
//        }
//        $this->_pdf->Output();
    }
    
    private function _cabeceraPhSrl($pdf)
    {
        $pdf->image(IMAGEN_PUBLICA . 'reportes/logoph.jpg', 20, 10, 30);
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(41);
        $pdf->Cell(0, 17, utf8_decode('Pequeño Hogar SRL'), 0, 1, 'L');
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->Cell(41);
        $pdf->Cell(0, -5, utf8_decode('Salta 1957 - Tel. (0376) 439049'), 0, 1, 'L');
        $pdf->Cell(41);
        $pdf->Cell(0, 15, utf8_decode('3300  Posadas  Misiones'), 0, 1, 'L');
        $this->_pdf->Line(20, 33, 195, 33);
        return;
    }
    
    private function _facturaIps($pdf, $pacientes)
    {
        $pdf->SetFont('Helvetica', '', 12);
        setlocale(LC_TIME , 'es_ES');
        $pdf->Cell(185, 17, utf8_decode('Posadas, ' . strftime("%d %B %Y")), 0, 1, 'R');
        $pdf->Cell(20);
        $pdf->Cell(0, 17, utf8_decode('Sres. Obra Social I.P.S.'), 0, 1, 'L');
        $pdf->Cell(20);
        $pdf->Cell(0, 0, utf8_decode('S/D'), 0, 1, 'L');
        $pdf->Cell(60);
        $pdf->Cell(0, 15, utf8_decode('Adjuntamos a la presente liquidación correspondiente al mes de'), 0, 1, 'L');
        $pdf->Cell(20);
        $pdf->Cell(0, 0, utf8_decode(strftime("%B") . '/' . strftime("%Y") . 
                ' Factura "A" Nº 0001-00000568 de la Atención Integral por Discapacidad'),
                0, 1, 'L');
        $pdf->Cell(20);
        $pdf->Cell(0, 15, utf8_decode('recibida por los afiliados de esa  Obra Social en "Pequeño Hogar SRL", con el consi-'), 0, 1, 'L');
        $pdf->Cell(20);
        $pdf->Cell(0, 0, utf8_decode('guiente detalle de facturas:'),0, 1, 'L');
        $this->_pdf->Cell(0, 10, '', 0, 2, '');
        $pdf->Cell(30);
        $num = 1;
        foreach ($pacientes as $paciente) {
            $this->_pdf->Cell(0, 10, utf8_decode($num . ' ' . strtoupper($paciente['apellidos']) . ', ' . strtoupper($paciente['nombres'])), 0, 2, '');
            $this->_pdf->Cell(0, 10, utf8_decode('Nombre y Apellido: ' . utf8_decode($paciente->getApellidos() .
                                ', ' . utf8_decode($paciente->getNombres()))), 0, 0);
            $this->_pdf->Cell(50, -45, utf8_decode('DNI: ' . utf8_decode($paciente->getNro_doc())), 0, 1);
            $num++;
        }
    }

}
