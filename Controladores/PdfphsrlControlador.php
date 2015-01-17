<?php

require_once BASE_PATH . 'LibQ/fpdfphsrl.php';

class PdfphsrlControlador extends Controlador
{

    private $_pdf;
    private $_modeloPaciente;
    private $_modeloIngresos;
    private $_modelo;
    private $_leyenda;

    public function __construct()
    {
        parent::__construct();
        parent::getLibreria('fpdfphsrl');
        parent::getLibreria('Fechas');
        $this->_pdf = new FPDFphsrl();
    }

    public function index()
    {
        ;
    }

//    public function contactos_pacientes($id)
//    {
//        $this->_modeloPaciente = $this->cargarModelo('index', 'Paciente');
//        $terapiaPaciente = $this->cargarModelo('contacto', 'Paciente');
//        $paciente = $this->_modeloPaciente->getListadoContactos();
//        $this->_pdf->AddPage('p', 'A4');
////        $this->_cabeceraIps($this->_pdf);
//        $this->_pdf->SetFont('Helvetica', 'B', 12);
//        $this->_pdf->Cell(0, -45, 'SOLICITUD DE PRESTACION ESPECIAL', 0, 1, 'C');
//        $this->_pdf->Cell(0, 60, utf8_decode('CENTRO:  PEQUEÑO HOGAR S.R.L.'), 0, 1);
//        $this->_pdf->SetFont('Helvetica', '', 12);
//        $this->_pdf->Cell(120, -45, utf8_decode('Nombre y Apellido: ' . utf8_decode($paciente['apellidos'] .
//                                ', ' . utf8_decode($paciente['nombres']))), 0, 0);
//        $this->_pdf->Cell(50, -45, utf8_decode('DNI: ' . utf8_decode($paciente['nro_doc'])), 0, 1);
//        $this->_pdf->Cell(50, 60, utf8_decode('Nº de Afiliado: ' . utf8_decode($paciente['nro_doc'])), 0, 0);
//        $this->_pdf->Cell(70, 60, utf8_decode('Fecha de Nacimiento: ' . utf8_decode($paciente['fecha_nac'])), 0, 0);
//        $this->_pdf->Cell(50, 60, 'Edad: ' . utf8_decode($paciente['fecha_nac']) . utf8_decode(' Años'), 0, 1);
//        $this->_pdf->Cell(0, -45, utf8_decode('Diagnóstico: ') . utf8_decode($paciente['diagnostico']), 0, 1);
//        $this->_pdf->Cell(0, 60, utf8_decode('Corresponde al més de: ') . date('n-Y'), 0, 1);
//        $this->_pdf->Cell(0, -45, utf8_decode('Solicito atención permanente en Centro Interdisciplinario en las siguientes áreas: '), 0, 1);
//        $this->_pdf->Cell(10);
//        $this->_pdf->Cell(70, 60, utf8_decode('TERAPIAS'), 0, 0);
//        $this->_pdf->Cell(0, 60, utf8_decode('CANTIDAD DE SESIONES'), 0, 1);
//        $terapias = $terapiaPaciente->getTerapias($id);
//        $i = 0;
//        foreach ($terapias as $terapia) {
//            if ($i == 0) {
//                $this->_pdf->Cell(10);
//                $this->_pdf->Cell(70, -45, utf8_decode($terapia['terapia']), 0, 0);
//                $this->_pdf->Cell(0, -45, utf8_decode($terapia['sesiones']), 0, 1);
//                $i = 1;
//            } else {
//                $this->_pdf->Cell(10);
//                $this->_pdf->Cell(70, 60, utf8_decode($terapia['terapia']), 0, 0);
//                $this->_pdf->Cell(0, 60, utf8_decode($terapia['sesiones']), 0, 1);
//                $i = 0;
//            }
//        }
//        $oSocial = $obraSocialPaciente->getOSocial($id);
//        $this->_pdf->Cell(10);
//        $this->_pdf->Cell(70, 60, utf8_decode($oSocial['observaciones']), 0, 1);
//        $this->_pdf->Cell(0, 0, utf8_decode('Firma y Aclaracion Responsable'), 0, 1);
//        $this->_pdf->Line(10, 235, 190, 235);
//        $this->_pieIps($this->_pdf);
//        /** Reverso */
//        $this->_pdf->AddPage('p', 'legal');
//        foreach ($terapias as $terapia) {
//            $this->_pdf->Cell(0, 10, 'Terapia: ' . $terapia['terapia'] . '         Firma del Profesional', 1, 1, '');
//            $this->_pdf->Cell(0, 10, 'Fecha                                                               Firma del Padre', 1, 1, '');
//            for ($fila = 1; $fila <= $terapia['sesiones']; $fila++) {
//                $this->_pdf->Cell(0, 10, ' ', 1, 1, '');
//            }
//            $this->_pdf->Cell(0, 10, ' ', 0, 1, '');
//        }
//
//        $this->_pdf->Output();
//    }

    public function imprimir_informe($id)
    {
        $datos = '';
        $this->_modelo = $this->cargarModelo('hTerapeutica', 'Paciente');
        $datos = $this->_modelo->getHTerapeuticaId($id);
        print_r(html_entity_decode($datos,ENT_NOQUOTES));
        setlocale(LC_TIME, 'es_ES');
        $this->_pdf->AddPage('p', 'legal');
        $this->_pdf->SetFont('Helvetica', '', 12);
//        $this->_pdf->MultiCell(0, 17, utf8_decode(strip_tags($datos['observacion'])));
        $this->_printChapter($datos);
        $this->_pdf->Output();
    }

    private function _chapterBody($datos)
    {
        // Times 12
        $this->_pdf->SetFont('Helvetica', '', 12);
        // Imprimimos el texto justificado
        $this->_pdf->setX(20);
        $this->_pdf->MultiCell(0, 8, html_entity_decode($datos,ENT_NOQUOTES));
//        $this->Justify(html_entity_decode($datos,ENT_NOQUOTES), 185, 8);
//        $this->_pdf->Write(5,$datos);
        // Salto de línea
        $this->_pdf->Ln();
    }

    function ChapterTitle($datos)
    {
        // Arial 12
        $this->_pdf->SetFont('Helvetica', 'B', 12);
        // Color de fondo
        $this->_pdf->SetFillColor(235, 235, 235);
        // Título
        $this->_pdf->setX(20);
        if ($datos['tipo'] == 1) {
            $tipo = 'Evolutivo';
        } else {
            $tipo = 'Evaluativo';
        }
        $this->_pdf->Cell(0, 7, utf8_decode("Informe ") . $tipo . utf8_decode(" del Área ") . $datos['terapia'], 0, 1, 'L', true);
        $this->_pdf->setX(20);
        $this->_pdf->Cell(0, 7, "Paciente: " . $datos['apellidos'] . ', ' . $datos['nombres'], 0, 1, 'L', true);
        $this->_pdf->setX(20);
        $this->_pdf->Cell(0, 7, "Edad: " . fecha::edadHasta($datos['fecha_nac'], $datos['fechaObservacion']) . utf8_decode(" años"), 0, 1, 'L', true);
        $this->_pdf->setX(20);
        $this->_pdf->MultiCell(0, 7, utf8_decode("Diagnóstico: " . $datos['diagnostico']), 0, 1, 'L', true);
        // Salto de línea
        $this->_pdf->Ln(4);
    }

    private function _printChapter($datos)
    {
        $this->ChapterTitle($datos);
        $this->_chapterBody(strip_tags($datos['observacion']));
    }

    public function facturacion($id)
    {
        $datos = '';
        $this->_modeloIngresos = $this->cargarModelo('ingresos');
        $datos['ingresos'] = $this->_modeloIngresos->getUltimoIngresoByOs($id);
        if ($this->getInt('facturacion') == 1) {
            if (parent::getPostParam('leyenda') != '') {
                $this->_leyenda = utf8_decode(strip_tags(parent::getPostParam('leyenda')));
            }
            if (parent::getPostParam('paciente')){
                $datos['paciente'] = parent::getPostParam('paciente');
            }
        } else {
            $this->_modeloPaciente = $this->cargarModelo('index', 'Paciente');
            $datos['paciente'] = $this->_modeloPaciente->getPacientesByOs($id);
        }
//        $datos['paciente'] = parent::getPostParam('paciente');
//        print_r($datos['ingresos']);
        $this->_pdf->AddPage('p', 'legal');
        if ($id == 1) {
            $this->_facturaIps($this->_pdf, $datos);
        }
        $this->_pdf->Output();
    }

//    private function _cabeceraPhSrl($pdf)
//    {
//        $pdf->image(IMAGEN_PUBLICA . 'reportes/logoph.jpg', 20, 10, 30);
//        $pdf->SetFont('Helvetica', 'B', 10);
//        $pdf->Cell(41);
//        $pdf->Cell(0, 17, utf8_decode('Pequeño Hogar SRL'), 0, 1, 'L');
//        $pdf->SetFont('Helvetica', '', 9);
//        $pdf->Cell(41);
//        $pdf->Cell(0, -5, utf8_decode('Salta 1957 - Tel. (0376) 439049'), 0, 1, 'L');
//        $pdf->Cell(41);
//        $pdf->Cell(0, 15, utf8_decode('3300  Posadas  Misiones'), 0, 1, 'L');
//        $this->_pdf->Line(20, 33, 195, 33);
//        return;
//    }

    private function _facturaIps($pdf, $datos)
    {
        $pacientes = $datos['paciente'];
        $ingresos = $datos['ingresos'];
        setlocale(LC_TIME, 'es_ES');
        if ($this->_leyenda == '') {
            $this->_leyenda = str_repeat(" ", 21) . 'Adjuntamos a la presente liquidación correspondiente al mes de ' .
                    strftime("%B") . '/' . strftime("%Y") .
                    ' Factura "A" Nº ' . $ingresos['nro_comprobante'] . ' de la Atención Integral por Discapacidad' .
                    ' recibida por los afiliados de esa  Obra Social en "Pequeño Hogar SRL",' .
                    ' con el consiguiente detalle de facturas:';
        }

        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(185, 17, utf8_decode('Posadas, ' . strftime("%d %B %Y")), 0, 1, 'R');
        $pdf->Cell(20);
        $pdf->Cell(0, 17, utf8_decode('Sres. Obra Social I.P.S.'), 0, 1, 'L');
        $pdf->Cell(20);
        $pdf->Cell(0, 0, utf8_decode('S/D'), 0, 1, 'L');
        $pdf->ln(5);
        $pdf->Cell(20);
        $pdf->MultiCell(0, 8, utf8_decode($this->_leyenda), 0, 'J', false);

        $this->_pdf->Cell(0, 5, '', 0, 2, '');
        $pdf->Cell(30);
        $num = 1;
        foreach ($pacientes as $paciente) {
            $this->_pdf->Cell(0, 10, $num . ' ' . utf8_decode(strtoupper($paciente)), 0, 2, '');
            $num++;
        }
    }

    public function recibido_planillas($id)
    {
        $datos = '';
        $this->_modeloPaciente = $this->cargarModelo('index', 'Paciente');
        $datos = $this->_modeloPaciente->getPacientesByOs($id);
        $this->_pdf->AddPage('p', 'legal');
        if ($id == 1) {
            $this->_recibidoIps($this->_pdf, $datos);
        }
        $this->_pdf->Output();
    }

    private function _recibidoIps($pdf, $datos)
    {
        setlocale(LC_TIME, 'es_ES');
        if ($this->_leyenda == '') {
            $this->_leyenda = str_repeat(" ", 21) . 'Adjuntamos a la presente planillas del mes de ' .
                    strftime("%B") . '/' . strftime("%Y") .
                    ' de los pacientes que reciben atención interdisciplinaria en esta institución' .
                    ' a fin de ser auditadas.' .
                    ' Lo saludamos atentamente:';
        }

        $pdf->SetFont('Helvetica', '', 12);
        $pdf->Cell(185, 17, utf8_decode('Posadas, ' . strftime("%d %B %Y")), 0, 1, 'R');
        $pdf->Cell(20);
        $pdf->Cell(0, 17, utf8_decode('Sres. Obra Social I.P.S.'), 0, 1, 'L');
        $pdf->Cell(20);
        $pdf->Cell(0, 0, utf8_decode('S/D'), 0, 1, 'L');
        $pdf->ln(5);
        $pdf->Cell(20);
        $pdf->MultiCell(0, 8, utf8_decode($this->_leyenda), 0, 'J', false);
        $this->_pdf->Cell(0, 5, '', 0, 2, '');
        $pdf->Cell(30);
        $num = 1;
        foreach ($datos as $paciente) {
//            print_r ($paciente);
            $txt = strtoupper($paciente->getAyN()) . ' - DNI:' . $paciente->getNro_Doc();
            $this->_pdf->Cell(0, 10, $num . ' ' . utf8_decode($txt), 0, 2, '');
//            $this->_pdf->Cell(0, 10, $num . ' ' . utf8_decode(strtoupper($paciente->getDni())), 0, 2, '');
            $num++;
        }
    }

    public function constanciaAsistenciaRegular($id)
    {
        $datos = '';
        $this->_modeloPacientes = $this->cargarModelo('index', 'Paciente');
        $paciente = $this->_modeloPacientes->getPaciente($id);

//        $this->_modeloTerapias = $this->cargarModelo('terapia', 'Paciente');
//        $terapias = $paciente->getTerapias();
//        foreach ($terapias as $terapia) {
//            $lista[]=$terapia->getTerapia();
//        }
//         print_r($lista);
//       $datos['terapias'] =  implode(', ', $lista);
        $this->_leyenda[] = 'La presente se expide a pedido de la interesada en ' .
                'Posadas – Misiones, a los 01 días del mes de septiembre del 2010' .
                ', para ser presentado a las autoridades que lo requieran';

//        $this->_modeloPaciente = $this->cargarModelo('index', 'Paciente');
//        $datos['pacientes'] = $this->_modeloPaciente->getPacientesByOs($id);

        $this->_pdf->AddPage('p', 'A4');
        $this->_constanciaAsistenciaRegular($this->_pdf, $paciente);
        $this->_pdf->Output();
    }

    private function _constanciaAsistenciaRegular($pdf, $paciente)
    {
//        $pacientes = $datos['pacientes'];
//        $ingresos = $datos['ingresos'];
        foreach ($paciente->getTerapias() as $terapia) {
            $lista[] = $terapia->getTerapia();
        }
        $litaTerapias = implode(', ', $lista);
        setlocale(LC_TIME, 'es_ES');
        if (!$this->_leyenda || $this->_leyenda != '') {
            $this->_leyenda = str_repeat(" ", 21) . 'Se hace constar que ' .
                    $paciente->getApellidos() .
                    ', ' . $paciente->getNombres() . ', DNI: ' . $paciente->getNro_doc() .
                    ', con diagnóstico médico ' . $paciente->getDiagnostico() .
                    ', asiste a nuestra institución semanalmente, recibiendo atención' .
                    ' terapéutica en las áreas de: ' . $litaTerapias . '.
                    La presente se expide a pedido ' .
                    'de la interesada en Posadas, Misiones, a los ' . strftime("%d") .
                    ' días del mes de ' . strftime("%B") . ' del ' . strftime("%Y") .
                    ', para ser presentado a las autoridades que lo requieran.';
        }

        $pdf->SetFont('Helvetica', '', 14);
        $pdf->Cell(185, 17, utf8_decode('CONSTANCIA DE ASISTENCIA REGULAR'), 0, 1, 'C');

        $pdf->SetFont('Helvetica', '', 12);
//        $pdf->Cell(185, 17, utf8_decode('Posadas, ' . strftime("%d %B %Y")), 0, 1, 'R');
        $pdf->Cell(20);
//        $pdf->Cell(0, 17, utf8_decode('Sres. Obra Social I.P.S.'), 0, 1, 'L');
//        $pdf->Cell(20);
//        $pdf->Cell(0, 0, utf8_decode('S/D'), 0, 1, 'L');
//        $pdf->ln(5);
//        $pdf->Cell(20);
        $pdf->MultiCell(0, 8, utf8_decode($this->_leyenda), 0, 'J', false);

        $this->_pdf->Cell(0, 5, '', 0, 2, '');
        $pdf->Cell(30);
//        $num = 1;
//        foreach ($pacientes as $paciente) {
//            if(is_array($paciente)){
//                $paciente= $num . ' ' . strtoupper($paciente['apellidos']) . ', ' . strtoupper($paciente['nombres']);
//            }
//            $this->_pdf->Cell(0, 10, utf8_decode($paciente), 0, 2, '');
//            $num++;
//        }
    }

    function Justify($text, $w, $h)
    {
        $tab_paragraphe = explode("\n", $text);
        $nb_paragraphe = count($tab_paragraphe);
        $j = 0;

        while ($j < $nb_paragraphe) {

            $paragraphe = $tab_paragraphe[$j];
            $tab_mot = explode(' ', $paragraphe);
            $nb_mot = count($tab_mot);

            // Handle strings longer than paragraph width
            $k = 0;
            $l = 0;
            while ($k < $nb_mot) {

                $len_mot = strlen($tab_mot[$k]);
                if ($len_mot < ($w - 5)) {
                    $tab_mot2[$l] = $tab_mot[$k];
                    $l++;
                } else {
                    $m = 0;
                    $chaine_lettre = '';
                    while ($m < $len_mot) {

                        $lettre = substr($tab_mot[$k], $m, 1);
                        $len_chaine_lettre = $this->_pdf->GetStringWidth($chaine_lettre . $lettre);

                        if ($len_chaine_lettre > ($w - 7)) {
                            $tab_mot2[$l] = $chaine_lettre . '-';
                            $chaine_lettre = $lettre;
                            $l++;
                        } else {
                            $chaine_lettre .= $lettre;
                        }
                        $m++;
                    }
                    if ($chaine_lettre) {
                        $tab_mot2[$l] = $chaine_lettre;
                        $l++;
                    }
                }
                $k++;
            }

            // Justified lines
            $nb_mot = count($tab_mot2);
            $i = 0;
            $ligne = '';
            while ($i < $nb_mot) {

                $mot = $tab_mot2[$i];
                $len_ligne = $this->_pdf->GetStringWidth($ligne . ' ' . $mot);

                if ($len_ligne > ($w - 5)) {

                    $len_ligne = $this->_pdf->GetStringWidth($ligne);
                    $nb_carac = strlen($ligne);
                    $ecart = (($w - 2) - $len_ligne) / $nb_carac;
                    $this->_pdf->_out(sprintf('BT %.3F Tc ET', $ecart * $this->_pdf->k));
                    $this->_pdf->MultiCell($w, $h, $ligne);
                    $ligne = $mot;
                } else {

                    if ($ligne) {
                        $ligne .= ' ' . $mot;
                    } else {
                        $ligne = $mot;
                    }
                }
                $i++;
            }

            // Last line
            $this->_pdf->_out('BT 0 Tc ET');
            $this->_pdf->MultiCell($w, $h, $ligne);
            $tab_mot = '';
            $tab_mot2 = '';
            $j++;
        }
    }

}
