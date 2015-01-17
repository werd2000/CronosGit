<?php

require_once 'Zend/View.php';
require_once LibQ . 'ControlarSesion.php';

require_once 'App/LibQ/ControladorBase.php';
require_once DIRMODULOS . 'Imprimir/Forms/OpcionesPacientes.php';
require_once DIRMODULOS . 'Pacientes/Controlador/PacientesControlador.php';
require_once DIRMODULOS . 'Pacientes/Modelo/PacientesModelo.php';
require_once LibQ . 'LibQ_BarraHerramientas.php';
require_once LibQ . 'MyFechaHora.php';


/**
 *  Clase Controladora del Modulo Imprimir
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Imprimir
 * 
 */
class ImprimirControlador extends ControladorBase
{

    /**
     * Propiedad usada para enviar los elementos del formulario
     * @var type Array
     */
    private $_varForm = array();

    /**
     * Propiedad usada para establecer los campos de la BD
     * @var type Array
     */
    private $_campos = array(
        'id',
        'denominacion',
        'direccion'
    );

    /**
     * Propiedad usada para establecer los títulos de los campos de la BD
     * @var type Array
     */
    private $_tituloCampos = array(
        'id' => 'Id',
        'denominacion' => 'Denominación',
        'direccion' => 'Dirección'
    );
    
     /**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonImprimir = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Imprimir')\"" ,
    );


    
    /**
     * Propiedad usada para configurar el botón VOLVER
     * @var type Array
     */
    private $_paramBotonVolver = array('href'=>'index.php?option=imprimir');
    
    /**
     * Propiedad usa para configurar el botón GUARDAR ALUMNO
     * @var type Array
     */
    private $_paramBotonGuardar = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Guardar')\"" ,
        );
    
   
    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonLista = array(
        'href' => 'index.php?option=imprimir&sub=listar',
        'classIcono' => 'icono-lista32'
        );
    
    private $_modeloPaciente;


    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */
    function __construct()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Imprimir/Vista');
        require_once DIRMODULOS . 'Imprimir/Modelo/ImprimirModelo.php';
        $this->_modelo = new ImprimirModelo();
        $this->_modeloPaciente = new PacientesModelo();
    }

    public function index()
    {
        $this->_layout->content = $this->_vista->render('ImprimirVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }
    
    public function paciente($arg=array())
    {
        $pacienteBuscado = $this->_modeloPaciente->buscarPaciente($arg);
        $paciente = new Paciente($pacienteBuscado);
        $this->_form = new Form_OpcionesPacientes($paciente);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $this->informe($values, $paciente);
            }
        }
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Imprimir', $this->_paramBotonImprimir);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        
        
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('ImprimirPaciente.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    public function informe($values, $paciente)
    {
        if ($values) {
            print_r($values);
            switch ($values['tipo_informe']) {
                case 'NOTA PEDIDO IPS':
                    $this->_crearNotaPedidoPDF($values, $paciente);
                    break;

                default:
                    break;
            }
        }
    }
    
    private function _crearNotaPedidoPDF($values, $paciente)
    {
        require_once 'Zend/Pdf.php';
        $pdf = new Zend_Pdf();
//        $pdf->pages[] = ($page = $pdf->newPage('A4')); //(595:842)
        $pdf->pages[] = new Zend_Pdf_Page('612:1034');
        $pdf->pages[] = new Zend_Pdf_Page('612:1034');
//        $pdf->pages[] = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
        $pdf->pages = array_reverse($pdf->pages);
        $estilo = new Zend_Pdf_Style();
        $estilo->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_ITALIC);
        $estilo->setFont($font, 10);
        $nroPag = 1;
        foreach ($pdf->pages as $page){
            $page->setStyle($estilo);
            if ($nroPag == 1){
                try {
                    $imageFile = IMG . '/reportes/ips_misiones.jpg';
                    $stampImage = Zend_Pdf_Image::imageWithPath($imageFile);
                } catch (Zend_Pdf_Exception $e) {
                    if ($e->getMessage() != 'Image extension is not installed.' &&
                        $e->getMessage() != 'JPG support is not configured properly.') {
                        throw $e;
                    }
                    $stampImage = null;
                }
                if ($stampImage != null) {
                    //15,772,580,822 //A4
                    $page->drawImage($stampImage, 15, 954, 580, 1014); //oficio
//                    $page->drawText('Junin 1789  -  www.ips_misiones.gov.ar  -  3300  Posadas  Misiones', 120, 760); //A4
                    $page->drawText('Junin 1789  -  www.ips_misiones.gov.ar  -  3300  Posadas  Misiones', 140, 942); //Oficio
                    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
                    $estilo->setFont($font, 12);
                    $page->setStyle($estilo);
//                    $page->drawText('SOLICITUD DE PRESTACION ESPECIAL', 150, 740); //A4
                    $page->drawText('SOLICITUD DE PRESTACIÓN ESPECIAL', 160, 920,'UTF-8'); //Oficio
//                    $page->drawText(htmlentities('CENTRO:  PEQUEÑO HOGAR S.R.L.'), 30, 720); //A4
                    $page->drawText(("CENTRO:  PEQUEÑO HOGAR S.R.L."), 30, 900, 'UTF-8'); //Oficio
                    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
                    $estilo->setFont($font, 12);
                    $page->setStyle($estilo);
//                    $page->drawText('Nombre y Apellido: '.$paciente->getAyn().
//                            '          DNI: '.$paciente->getNroDoc(), 30, 700);   //A4
                    $page->drawText('Nombre y Apellido: '.$paciente->getAyn(), 30, 880, 'UTF-8'); //oficio
                    $page->drawText('DNI: '.$paciente->getNroDoc(),350,880);
//                    $page->drawText('Nº de Afiliado: '.$paciente->getNroAfiliado().
//                            '    Fecha de Nacimiento: '.  MyFechaHora::getFechaAr($paciente->getFechaNac()). 
//                            '     Edad: '.$paciente->getEdad().' Años', 30, 680);     //A4
                    $page->drawText('Nº de Afiliado: '.$paciente->getNroAfiliado().
                            '    Fecha de Nacimiento: '.  MyFechaHora::getFechaAr($paciente->getFechaNac()). 
                            '     Edad: '.$paciente->getEdad().' Años', 30, 860,'UTF-8');
                     $page->drawText('Diagnóstico: '.$paciente->getDiagnostico(),30,840, 'UTF-8');   //30,660);
                     $page->drawText('Corresponde al mes de: '.date('n-Y'),30,820);     //30,640);
                     $page->drawText('Solicito atención permanente en Centro Interdisciplinario en las siguientes áreas:',30,800, 'UTF-8');      //30,600);
                     $page->drawText('TERAPIAS',45,780);        //45,580);
                     $page->drawText('CANTIDAD DE SESIONES',245,780);
                     $fila = 750;       //550
                     $datosTerapias = $paciente->getTerapias();
                     foreach ($datosTerapias as $terapia) {
                         $page->drawText($terapia->terapia,45,$fila, 'UTF-8');
                         $page->drawText($terapia->sesiones,290,$fila);
                         $fila = $fila - 20;
                     }
//                     $page->drawText('MODULO: '.$paciente->getModulo(),45,350);
                     $page->drawText('MODULO: '.$paciente->getObservacionesOs(),45,350, 'UTF-8');
//                     $page->drawText('Codigo: '.$paciente->getModulo(),245,350);
                     $page->drawText('Firma y Aclaracion Responsable ',45,250, 'UTF-8');
                     $page->drawLine(30, 230, 580, 230);
                     $page->drawText('DATOS A COMPLETAR POR EL PROFESIONAL AUDITOR DEL IPS',30,200, 'UTF-8');
                     $page->drawText('Observaciones: ............................................................................................................................',30,180, 'UTF-8');
                     $page->drawText('En base a la solicitud requerida por la responsable solicitante considero que SI - NO corresponde',30,160, 'UTF-8');
                     $page->drawText('Fecha: ................/.............../.................',30,140, 'UTF-8');
                     $page->drawText('(La presente autorización debe acompañar a la facturación)',30,120, 'UTF-8');
               }
                $nroPag++;
            }else{
                $datosTerapias = $paciente->getTerapias();
                $inicioRX = 996; //800;
                $finRX = 1014;  //820;
                $inicioTX = 1001;    //805;
                foreach ($datosTerapias as $terapia) {
                    $page->drawRectangle(15, $inicioRX, 580, $finRX, $fillType = Zend_Pdf_Page::SHAPE_DRAW_STROKE);
                    $page->drawText('Terapia: ' . $terapia->terapia,20,$inicioTX);
                    $page->drawText('Firma del Profesional ' ,200,$inicioTX);
                    $page->drawRectangle(15, $inicioRX - 20, 580, $finRX - 20, $fillType = Zend_Pdf_Page::SHAPE_DRAW_STROKE);
                    $page->drawText('Fecha',20,$inicioTX - 20);
                    $page->drawText('Firma del Padre' , 200,$inicioTX - 20);
                    $inicioFila = $inicioRX - 40;
                    $finFila = $inicioFila + 20;
                    for ($fila = 1; $fila <= $terapia->sesiones; $fila++) {
                        $page->drawRectangle(15, $inicioFila, 580, $finFila, $fillType = Zend_Pdf_Page::SHAPE_DRAW_STROKE);
                        $inicioFila = $inicioFila - 20;
                        $finFila = $finFila - 20;
                    }
                    $inicioRX = $inicioFila - 20;
                    $finRX = $finFila - 20;
                    $inicioTX = $inicioRX + 5;
                }
            }
//            $width  = $page->getWidth();
//            $height = $page->getHeight();
//            $page->drawText('Ancho:'.$width.' alto:'.$height, 1, 1);
        }
//        echo $pdf->save('prueba.pdf');
        echo $pdf->render();
//        header("Content-Type: application/pdf");
        header("Content-Type: application/pdf; charset=utf-8");
        // Si queremos que se devuelva como un fichero adjunto
//        header("Content-Disposition: attachment; filename=\"prueba.pdf\"");
        
    }

    /**
     * Método que lleva a la pag donde se cargan los Imprimir
     * Recibe los datos a guardar por POST y los guarda.
     * @return void
     */
    public function agregar()
    {
        require_once DIRMODULOS . 'Imprimir/Forms/CargaImprimir.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        $this->_form = new Form_CargaImprimir($this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['fechaNac'] = MyFechaHora::getFechaBd($values['fechaNac']);
                $this->_modelo->guardar($values);
                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
            }
        }
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        
        
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarImprimirVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    /**
     * Metodo para editar los datos de un profesional
     * @param Array $arg 
     * @access public
     */
    public function editar($arg)
    {
        require_once DIRMODULOS . 'Imprimir/Forms/CargaImprimir.php';
        include_once LibQ . 'MyFechaHora.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'Zend/File/Transfer.php';
        $profesionalBuscado = $this->_modelo->buscarProfesional($arg);
        if (is_object($profesionalBuscado)) {
            $this->_varForm['id'] = $profesionalBuscado->id;
            $this->_varForm['denominacion'] = $profesionalBuscado->denominacion;
            $this->_varForm['direccion'] = $profesionalBuscado->direccion;
        } else {
            $this->_varForm['id'] = '0';
            $this->_varForm['denominacion'] = '';
            $this->_varForm['direccion'] = '';
        }
        $this->_form = new Form_CargaImprimir($this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $this->_modelo->actualizar($values, $arg);
                
                $file = $this->_form->foto;
                // Creamos el adapter de Zend_File_Transfer
                $adapter = new Zend_File_Transfer_Adapter_Http();
                // Set a new destination path for all files
                $ruta = realpath(IMG.'fotos/');
                $ruta .= '/id'.$values['id']. '.png';
//                $adapter->setDestination($ruta);
                $file = $adapter->getFileInfo();
                
//                echo $ruta;
                $adapter->addFilter('Rename', array('target' => $ruta , 'overwrite' => true));
                if (!$adapter->receive()) {
                    $messages[] = implode('=>',$adapter->getMessages());
                    $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
//                    var_dump ($messages);
                }
                $messages[] = DATOSGUARDADOS;
                $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
            }
        }
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $bh->addBoton('Eliminar', 
                array('href' => 'index.php?option=imprimir&sub=eliminar&id='. $this->_varForm['id']
                    ));
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $foto = '<div id=mostrarFoto><img src="' . IMG . 'fotos/id' . $this->_varForm['id'] . '.png" class="mostrarFoto"/></div>';
        $this->_vista->foto = $foto;
        $this->_layout->content = $this->_vista->render('AgregarImprimirVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    /**
     * Metodo para eliminar un profesional.
     * La eliminacion no es real, sino que establece el campo 'eliminado' en verdadero
     * para no mostrarlo en las proximas oportunidades
     * @param Array $arg 
     * @access public
     */
    public function eliminar($arg='')
    {
        $where = '';
        $values = array();
        $where = implode(',', $arg);
        $values['eliminado'] = '1';
        $this->_modelo->actualizar($values, $arg);
        $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSELIMINADOS, 'info');
        parent::_redirect(LIVESITE . '/index.php?option=imprimir&sub=listar');
    }

    /**
     * Metodo para listar los imprimir enla grilla.
     * @param Array $arg 
     * @access public
     * @see LibQ/Grilla.php, LibQ/LibQ_BarraHerramientas.php
     */
    public function listar()
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('DOCENTES');
        $grilla->setUrl(LIVESITE . '/index.php?option=imprimir&sub=jsonListarImprimir');
        $grilla->setColNames(array(
            "'id'" => "'Id'",
            "'denominacion'" => "'Denominación'",
            "'direccion'" => "'Dirección'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => '55', 'align'=>"right"),
            array('name' => 'Denominación', 'index' => 'denominacion', 'width' => '150'),
            array('name' => 'Dirección', 'index' => 'direccion', 'width' => '150'),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(TRUE);
        $grilla->setActionOnDblClickRow('/index.php?option=imprimir&sub=editar&id=');

        $bh = new LibQ_BarraHerramientas($this->_vista);
//        $bh->addBoton('Exportar', array('href' => 'index.php?option=alumnos&sub=exportar' . $filtroBoton,
//        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoImprimirVista.php');
        echo $this->_layout->render();
    }
    
    public function jsonListarImprimir($arg='')
    {
        $responce = '';
        /** Me fijo si hay argumentos */
        if (isset($arg)) {
            /** Me fijo si existe el argumento page */
            if (!empty($_GET['page'])) {
                $pag = Input::get('page');
            } else {
                $pag = 1;
            }
            $inicio = ($pag - 1) * 30;
            /** Me fijo si existe el argumento de orden */
            if (!empty($_GET['sidx'])) {
                $orden = Input::get('sidx');
            } else {
                $orden = 'id';
            }
            /** Me fijo si el argumento es el tipo de orden (ASC o DESC) */
            if (!empty($_GET['sord'])) {
                $orden .= ' ' . Input::get('sord');
            } else {
                $orden .= ' ASC';
            }
            /** Si el argumento es un array entonces creo el filtro */
            if (is_array($arg)) {
                $filtroBoton = '&' . implode("&", $arg);
            } else {
                $filtroBoton = '';
            }
        }
        $json = new Zend_Json();
        $campos = array('id','denominacion', 'direccion');
        $todos = count($this->_modelo->listadoImprimir(0,0,$orden,'',$campos ));
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->listadoImprimir($inicio, 30,$orden,'',$campos );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array(
                $row['id'],
                $row['denominacion'],
                $row['direccion'],
                '<img src="' . IMG . 'fotos/id' . $row['id'] . '.png" class="foto_usuario_32"',
                );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    
    private function _crearFiltro($pag)
    {
        $filtro = '';
        $valorRecibido = Input::get('valor');
        if ($valorRecibido != 'valor' && $valorRecibido != '') {
            $campoRecibido = Input::get('campo');
            $clase = self::_ifExisteClase($campoRecibido);
            $file = DIRMODULOS . 'Imprimir/Controlador/' . $clase . '.php';
            require_once ($file);
            $filtro = new $clase($valorRecibido);
        }
        return $filtro;
    }

    private function _ifExisteClase($class)
    {
        $file = DIRMODULOS . 'Imprimir/Controlador/' . 'Filtro' . ucfirst($class) . '.php';
        if (!file_exists($file)) {
            die('No se puede crear el filtro');
        }
//	require_once ($file);
        return 'Filtro' . ucfirst($class);
    }

    public function exportar($filtro='')
    {
        require_once LibQ . 'ExportToExcel.php';
        $exp = new ExportToExcel();
        $exp->setTitulo('LISTADO DE DOCENTES');
        $exp->setEncabezadoPagina('&L&G&C&HPequeno Hogar 0476');
        $exp->setPiePagina('&RPag &P de &N');
        foreach ($this->_tituloCampos as $key => $value) {
            $encCol[] = $value;
        }
        $encCol = implode(',', $encCol);
        $encBD = $this->_campos;
        $exp->setEncBD($encBD);
        $exp->setFormatoCol(array(
            'id' => 'entero',
            'nro_doc' => 'entero',
            'fechaNac' => 'fecha'
        ));
        $exp->setEncabezados($encCol);
        $exp->setIfTotales(FALSE);
        $inicio = 0;
        if (isset($filtro)) {
            if (!empty($_GET['pg'])) {
                $pag = Input::get('pg');
            } else {
                $pag = 1;
            }
            $inicio = 0 + ($pag - 1) * 30;
            if (!empty($_GET['sidx'])) {
                $orden = Input::get('sidx');
            } else {
                $orden = 'id DESC';
            }
        }
        $filtro = $this->_crearFiltro($pag);
        $datos = $this->_modelo->listadoImprimir($inicio, $orden, $filtro, $this->_campos);
        $exp->exportar($datos);

        echo 'exportar';
    }

    private function _manejarFoto($values)
    {
        // Creamos el adapter de Zend_File_Transfer
        $adapter = new Zend_File_Transfer_Adapter_Http();
        $file = $adapter->getFileInfo();
        // Set a new destination path for all files
        $adapter->addFilter('Rename', array($this->_rutaFoto . $values['id'] . '.png', 'overwrite' => true));
        if (!$adapter->receive()) {
            $messages = $adapter->getMessages();
            $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
        }
        if (!$adapter->isValid($file)) {
            $messages = $adapter->getMessages();
            $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
        }
    }

}
