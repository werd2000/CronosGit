<?php
require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'Sql.php';
require_once BASE_PATH . 'LibQ' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php';
require_once BASE_PATH . 'LibQ' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph_bar.php';

/**
 * Clase Paciente Controlador 
 */
class GraficoControlador extends pacienteControlador {
    private $_paciente;
    private $_datosTerapia;
    private $_datosContacto;
    private $_datosFamilia;
    private $_datosOSocial;
    private $_listaSexos;
    private $_personal;
    private $_listaProfesionales;
    private $_estadoPaciente = array('EVALUACION', 'ACTIVO');

    /**
     * Constructor de la clase Index
     * Inicializa los modelos 
     */
    public function __construct() {
        parent::__construct();
        $this->_paciente = $this->cargarModelo('index');
        $this->_datosContacto = $this->cargarModelo('contactoPaciente');
        $this->_datosTerapia = $this->cargarModelo('terapia');
        $this->_datosFamilia = $this->cargarModelo('familia');
        $this->_datosOSocial = $this->cargarModelo('osocial');
        $this->_personal = $this->cargarModelo('personalPaciente');
        $this->_listaSexos = array('VARON', 'MUJER');
    }

    public function graficaSexos($t, $v, $m) {
        $dataX = array('T','V','M');
        $dataY = array($t,$v,$m);
        $titulo = "Grafica lineal";
        $tituloX = "Datos X";
        $tituloY = "Datos Y";
        $color = "#FFF400";

        $ydata = $dataY;
        $graph = new Graph(150, 90, "auto");
        
        $graph->SetScale("textlin");
        $xdata = $dataX;
        $graph->xaxis->SetTickLabels($xdata);
        $graph->img->SetMargin(0, 0, 0, 22); //I-D-S-I
//        $graph->title->Set($titulo);
//        $graph->xaxis->title->Set($tituloX);
//        $graph->yaxis->title->Set($tituloY);
//        $graph->ygrid->SetFill(true,'#EFEFEF@0.5','#F9BB64@0.5');
        $graph->SetShadow();
        $barplot = new BarPlot($ydata);
        $barplot->SetWidth(15); // 30 pixeles de ancho para cada barra
        $barplot->SetColor($color);
        // Un gradiente Horizontal de morados
        $barplot->SetFillGradient("#FFF400", "#FFFDC8", GRAD_HOR);

        $graph->Add($barplot);
        $img = $graph->Stroke();
        return $graph;
    }

}
