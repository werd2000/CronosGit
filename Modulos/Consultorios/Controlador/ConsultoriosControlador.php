<?php

//require_once 'class/Usuario.php';
require_once 'Zend/View.php';
require_once LibQ . 'ControlarSesion.php';
//require_once 'App/LibQ/Input.php';
//require_once 'Zend/Session/Namespace.php';
require_once 'App/LibQ/ControladorBase.php';
//require_once 'App/modelos/LoginModelo.php';

/**
 *  Clase Controladora del Modulo Consultorios
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Login
 * 
 */
class ConsultoriosControlador extends ControladorBase
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
        'consultorios.id',
        'consultorios.consultorio',
        'consultorios.division',
        'turnos.turno',
        'CONCAT_WS(", ", docentes.apellidos,docentes.nombres)'
    );

    /**
     * Propiedad usada para establecer los títulos de los campos de la BD
     * @var type Array
     */
    private $_tituloCampos = array(
        'id' => 'Id',
        'consultorio' => 'Salón',
        'division'=>'División',
        'turno' => 'Turno',
        'CONCAT_WS(", ", docentes.apellidos,docentes.nombres)' => 'Docente'
    );
    
    /**
     * Propiedad usada para conectar a la tabla docentes
     * @var type Model
     */
    private $_modeloDocentes;
    
    /**
     * Propiedad usada para conectar a la tabla turnos
     * @var type Model
     */
    private $_modeloTurnos;
    
    /**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevoSalon = array(
        'href' => 'index.php?option=consultorios&sub=agregar',
        'classIcono' => 'icono-nuevo32'
        );
    
    /**
     * Propiedad usada para configurar el boton FILTRAR
     * @var type array
     */
    private $_paramBotonFiltrar = array(
        'class' => 'btn_filtrar' ,
        'evento' => "onclick=\"javascript: submitbutton('filtrar')\"" ,
        'href'=>"\"javascript:void(0);\""
        );
    
    /**
     * Propiedad usada para configurar el botón VOLVER
     * @var type Array
     */
    private $_paramBotonVolver = array('href'=>'index.php?option=consultorios');
    
    /**
     * Propiedad usa para configurar el botón GUARDAR ALUMNO
     * @var type Array
     */
    private $_paramBotonGuardar = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Guardar')\"" ,
        );
    
    /**
     * Propiedad usada para configurar el botón NUEVA INSCRIPCION
     * @var type Array
     */
    private $_paramBotonNuevo = array(
        'href' => 'index.php?option=consultorios&sub=agregar' ,
        'classIcono' => 'icono-nuevo32' ,
        );
    
    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonLista = array(
        'href' => 'index.php?option=consultorios&sub=listar',
        'classIcono' => 'icono-lista32'
        );

    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */

    function __construct()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Consultorios/Vista');
        require_once DIRMODULOS . 'Consultorios/Modelo/ConsultoriosModelo.php';
        $this->_modelo = new ConsultoriosModelo();
    }

    public function index()
    {
        $this->_layout->content = $this->_vista->render('ConsultoriosVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }

    /**
     * Método que lleva a la pag donde se cargan los Consultorios
     * Recibe los datos a guardar por POST y los guarda.
     * @return void
     */
    public function agregar()
    {
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once LibQ . 'MyFechaHora.php';
        require_once DIRMODULOS . 'Docentes/Modelo/DocentesModelo.php';
        require_once DIRMODULOS . 'Consultorios/Forms/CargaConsultorios.php';
        $listaTurnos = $this->_listarTurnos();
        $this->_modeloDocentes = new DocentesModelo();
        $docentes = $this->_modeloDocentes->listadoDocentes('0', 'apellidos','');
        foreach ($docentes as $docente){
            $listaDocentes[] = array($docente['id']=>$docente['apellidos'].', '.$docente['nombres']);
        }
        $this->_form = new Form_CargaConsultorios($listaDocentes, $listaTurnos, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
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
        $this->_layout->content = $this->_vista->render('AgregarConsultoriosVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    private function _listarTurnos()
    {
        require_once DIRMODULOS . 'Configuracion/Modelo/ConfiguracionModelo.php';
        $listaTurnos = array();
        $this->_modeloTurnos = new ConfiguracionModelo();
        $turnos = $this->_modeloTurnos->listadoTurnos(0, 0, 'desde',  '') ;
        foreach ($turnos as $turno) {
            $listaTurnos[] = array($turno['id']=>$turno['turno']);
        }
//         print_r($listaTurnos);
       return $listaTurnos;
    }

    /**
     * Metodo para editar los datos de un docente
     * @param Array $arg 
     * @access public
     */
    public function editar($arg)
    {
        require_once DIRMODULOS . 'Consultorios/Forms/CargaConsultorios.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        $consultorioBuscado = $this->_modelo->buscarSalon($arg);
        if (is_object($consultorioBuscado)) {
            $this->_varForm['id'] = $consultorioBuscado->id;
            $this->_varForm['consultorio'] = $consultorioBuscado->consultorio;
            $this->_varForm['division'] = $consultorioBuscado->division;
            $this->_varForm['turno'] = $consultorioBuscado->turno;
            $this->_varForm['docente'] = $consultorioBuscado->docente;
        } else {
            $this->_varForm['id'] = '0';
            $this->_varForm['consultorio'] = '';
            $this->_varForm['division'] = '';
            $this->_varForm['turno'] = '';
            $this->_varForm['docente'] = '';
        }
        $listaDocentes = $this->_obtenerListaDocentes();
        $listaTurnos = $this->_listarTurnos();
        $this->_form = new Form_CargaConsultorios($listaDocentes,$listaTurnos, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                print_r($values);
                $this->_modelo->actualizar($values, $arg);
                $messages[] = DATOSGUARDADOS;
                $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
            }
        }
        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevoSalon); 
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
//        $bh->addBoton('Eliminar', 'consultorios', $this->_varForm['id']);
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $foto = '<div id=mostrarFoto><img src="' . IMG . 'fotos/id' . $this->_varForm['id'] . '.png" class="mostrarFoto"/></div>';
        $this->_vista->foto = $foto;
        $this->_layout->content = $this->_vista->render('AgregarConsultoriosVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    private function _obtenerListaDocentes()
    {
        $listaDocentes = array();
        require_once DIRMODULOS . 'Docentes/Modelo/DocentesModelo.php';
        $this->_modeloDocentes = new DocentesModelo();
        $docentes = $this->_modeloDocentes->listadoDocentes('0', 'apellidos','');
        foreach ($docentes as $docente){
            $listaDocentes[] = array($docente['id']=>$docente['apellidos'].', '.$docente['nombres']);
        }
        return $listaDocentes;
    }

    /**
     * Metodo para eliminar un docente.
     * La eliminacion no es real, sino que establece el campo 'eliminado' en verdadero
     * para no mostrarlo en las proximas oportunidades
     * @param Array $arg 
     * @access public
     */
    public function eliminar($arg='')
    {
        $where = '';
        $values = '';
        $where = implode(',', $arg);
        $values['eliminado'] = '1';
        $this->_modelo->actualizar($values, $arg);
        $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSELIMINADOS, 'info');
        parent::_redirect(LIVESITE . '/index.php?option=consultorios&sub=listar');
    }

    /**
     * Metodo para listar los consultorios enla grilla.
     * @param Array $arg 
     * @access public
     * @see LibQ/Grilla.php, LibQ/LibQ_BarraHerramientas.php
     */
    public function listar($arg='')
    {
        require_once LibQ . 'JQGrid.php';
        require_once LibQ . 'LibQ_BarraHerramientas.php';
        require_once DIR_MODULOS . 'Consultorios/Forms/FiltroConsultorios.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('SALONES');
        $grilla->setUrl(LIVESITE . '/index.php?option=consultorios&sub=jsonListarConsultorios');
        $grilla->setColNames(array(
            "'consultorios.id'"=>"'Id'",
            "'consultorios.consultorio'"=>"'Salon'",
            "'consultorios.division'"=>"'Div.'",
            "'consultorios.turno'"=>"'Turno'",
            "'CONCAT_WS(\",\", docentes.apellidos,docentes.nombres)'"=>"'Docente'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'consultorios.id', 'width' => '45', 'align'=>"right"),
            array('name' => 'Salon', 'index' => 'consultorios.consultorio', 'width' => '100'),
            array('name' => 'Div.', 'index' => 'consultorios.division', 'width' => '60'),
            array('name' => 'Turno', 'index' => 'consultorios.turno', 'width' => '100'),
            array('name' => 'Docente', 'index' => 'CONCAT_WS(", ", docentes.apellidos,docentes.nombres)', 'width' => '255')
        ));
        $grilla->setOnSelectRow(FALSE);
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(TRUE);
        $grilla->setActionOnDblClickRow('/index.php?option=consultorios&sub=editar&id=');

        $bh = new LibQ_BarraHerramientas($this->_vista);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=inscriptos';
        } else {
            $filtroBoton = '&lista=inscriptos';
        }
//        $bh->addBoton('Exportar', array('href' => 'index.php?option=alumnos&sub=exportarInscriptos' . $filtroBoton,
//        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->LibQ_BarraHerramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoConsultoriosVista.php');
        echo $this->_layout->render();
    }
    
    public function jsonListarConsultorios($arg='')
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
                $orden = 'consultorio';
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
        $ciclo = date('Y');
        $json = new Zend_Json();
        $todos = $this->_modelo->listadoConsultorios(0, 0,$orden,'', $this->_campos);
        $total_pages = ceil(count($todos) / 30);
        $result = $this->_modelo->listadoConsultorios($inicio, 30, $orden,'', $this->_campos );
//        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = count($todos);
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array(
                $row['id'],
                $row['consultorio'],
                $row['division'],
                $row['turno'],
                $row['CONCAT_WS(", ", docentes.apellidos,docentes.nombres)']
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
            $file = DIRMODULOS . 'Consultorios/Controlador/' . $clase . '.php';
            require_once ($file);
            $filtro = new $clase($valorRecibido);
        }
        return $filtro;
    }

    private function _ifExisteClase($class)
    {
        $file = DIRMODULOS . 'Consultorios/Controlador/' . 'Filtro' . ucfirst($class) . '.php';
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
        $exp->setTitulo('LISTADO DE Consultorios');
        $exp->setEncabezadoPagina('&L&G&C&HPequeno Hogar 0476');
        $exp->setPiePagina('&RPag &P de &N');
        foreach ($this->_tituloCampos as $key => $value) {
            $encCol[] = $value;
        }
        $encCol = implode(',', $encCol);
//        $encBD = $this->_campos;
//        $exp->setEncBD($encBD);
        $exp->setFormatoCol(array(
            'id' => 'entero',
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
        $campos = array(
            'consultorios.id',
            'consultorios.consultorio',
            'consultorios.division',
            'consultorios.turno',
            'CONCAT_WS(", ", docentes.apellidos,docentes.nombres)',
            );
        $camposBD = array(
            'id',
            'consultorio',
            'division',
            'turno',
            'CONCAT_WS(", ", docentes.apellidos,docentes.nombres)',
            );
        $exp->setEncBD($camposBD);
        $datos = $this->_modelo->listadoConsultorios($inicio, $orden, $filtro, $campos);
//        print_r($encCol);
        $exp->exportar($datos);

        echo 'exportar';
    }


}
