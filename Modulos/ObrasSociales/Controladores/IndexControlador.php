<?php

/**
 * Clase ObraSocial Controlador 
 */
class indexControlador extends obrasSocialesControlador
{

    private $_obraSocial;
    private $_modeloPaciente;
    private $_textoFacturacion;

    public function __construct()
    {
        parent::__construct();
        $this->_obraSocial = $this->cargarModelo('index');
        setlocale(LC_TIME , 'es_ES');
//        $this->_textoFacturacion = 'Adjuntamos a la presente liquidación correspondiente al mes de ' .
//                strftime("%B") . '/' . strftime("%Y") .
//                ' Factura "A" Nº ' . $ingresos['nro_comprobante'] . ' de la Atención Integral por Discapacidad' .
//                ' recibida por los afiliados de esa  Obra Social en "Pequeño Hogar SRL",' .
//                ' con el consiguiente detalle de facturas:';
    }

    public function index($pagina = false)
    {
        $menu = array(
            array(
                'onclick' => '',
                'href' => "?option=exportExcel&sub=ObrasSociales",
                'title' => 'Exportar',
                'class' => 'icono-exportar32'
            ),
            array(
                'onclick' => '',
                'href' => "?option=ObrasSociales&sub=index&cont=nuevo",
                'title' => 'Nuevo',
                'class' => 'icono-nuevo32'
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
        $this->_vista->_barraHerramientas = $menu;

        parent::getLibreria('paginador');
        parent::getLibreria('Fechas');
        $paginador = new Paginador();
        $datos = $this->_obraSocial->getObrasSociales();
//        foreach ($datos as $obraSocial) {
//            $todos[]=$obraSocial;
//        }
        $this->_vista->datos = $paginador->paginar($datos, $pagina);
        $this->_vista->paginacion = $paginador->getView('prueba', '?option=ObrasSociales&sub=index');
        $this->_vista->titulo = 'Obras Sociales';
        if ($pagina == 0) {
            $this->_vista->i = 1;
        } else {
            $this->_vista->i = (($pagina - 1) * LIMITE_REGISTROS) + 1;
        }
        $this->_vista->renderizar('index', 'obraSocial');
    }

    public function nuevo()
    {
//        Session::accesoEstricto(array('usuario'),true);
        $this->_vista->titulo = 'Nuevo ObraSocial';
        $this->_vista->setJs(array('jquery.validate.min'));
        $this->_vista->setVistaJs(array('validarNuevo'));

        $this->_vista->datos = $_POST;

        if (parent::getInt('guardar') == 1) {
            if (!parent::getTexto('denominacion')) {
                $this->_vista->_msj_error = 'Debe ingresar la denominacion';
                $this->_vista->renderizar('nuevo', 'obraSocial');
                exit;
            }

            if ($this->_obraSocial->insertarObraSocial(array(
                        'denominacion' => parent::getPostParam('denominacion'),
                        'direccion' => parent::getPostParam('direccion'),
                        'provincia' => parent::getPostParam('provincia'),
                        'cuit' => parent::getPostParam('cuit'),
                    ))) {
                $this->_msj_error = 'Datos Guardados';
            } else {
                $this->_msj_error = 'No se guardo';
            }

            parent::redireccionar('option=ObrasSociales');
        }
        $this->_vista->renderizar('nuevo', 'obraSocial');
    }

    public function editar($id)
    {
//        $this->_acl->acceso('editar_post');
        $menu = array(
            array(
                'onclick' => '',
                'href' => "javascript:void()",
                'title' => 'Imprimir',
                'class' => 'icono-imprimir32 dropdown',
                'children' => array(
                    0 => array(
                        'title' => 'FACTURACION',
                        'href' => "index.php?option=pdfphsrl&sub=facturacion&id=$id",
                        'children' => Array(),
                    ),
                    1 => array(
                        'title' => 'RECIBIDO',
                        'href' => "index.php?option=pdfphsrl&sub=recibido_planillas&id=$id",
                        'children' => Array(),
                    )
            )),
            array(
                'onclick' => '',
                'href' => "?option=ObrasSociales&sub=index&cont=nuevo",
                'title' => 'Nuevo',
                'class' => 'icono-nuevo32'
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
        $this->_vista->_barraHerramientas = $menu;

        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=ObrasSociales');
        }

        if (!$this->_obraSocial->getObraSocial($this->filtrarInt($id))) {
            $this->redireccionar('option=ObrasSociales');
        }

        $this->_vista->titulo = 'Editar ObraSocial';
        $this->_vista->setJs(
                array(
                    'jquery.validate.min',
                    'validarNuevo',
                    'util',
                    'tinymce/jscripts/tiny_mce/tiny_mce',
                    'iniciarTinyMce'));

        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;

            if (!parent::getTexto('denominacion')) {
                $this->_vista->_msj_error = 'Debe ingresar la denominación';
                $this->_vista->renderizar('editar', 'obraSocial');
                exit;
            }

            if (!parent::getTexto('direccion')) {
                $this->_vista->_msj_error = 'Debe ingresar la dirección';
                $this->_vista->renderizar('editar', 'obraSocial');
                exit;
            }
            if ($this->_obraSocial->editarObraSocial(array(
                        'denominacion' => parent::getPostParam('denominacion'),
                        'direccion' => parent::getPostParam('direccion'),
                        'provincia' => parent::getPostParam('provincia'),
                        'cuit' => parent::getPostParam('cuit'),
                            ), 'id = ' . $this->filtrarInt($id)
                    ) > 0) {
                $this->_msj_error = 'Datos Modificados';
            } else {
                $this->_msj_error = 'No se modificó';
            }

            $this->redireccionar('option=ObrasSociales');
        }
        //Si no es para guardar lleno el form con datos de la bd
        $this->_vista->datos = $this->_obraSocial->getObraSocial($this->filtrarInt($id));
        $this->_modeloPaciente = $this->cargarModelo('pacientes', TRUE);
        $this->_vista->pacientes = $this->_modeloPaciente->getPacientesByOs($id);
//        print_r($this->_modeloPaciente->getPacientesByOs($id));
        $this->_vista->textoFacturacion = $this->_textoFacturacion;
        $this->_vista->renderizar('editar', 'ObraSocial');
    }

    /**
     * Elimina los datos de un obraSocial
     * @param type $id 
     */
    public function eliminar($id)
    {
        $this->_acl->acceso('eliminar_post');/**
         * Establezco el nivel de acceso 
         */
//        Session::acceso('admin');

        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=ObrasSociales');
        }

        if (!$this->_obraSocial->getObraSocial($this->filtrarInt($id))) {
            $this->redireccionar('option=ObrasSociales');
        }

        if ($this->_obraSocial->eliminarObraSocial('id = ' . $this->filtrarInt($id)) > 0) {
            $this->_msj_error = 'Datos Eliminados';
        } else {
            $this->_msj_error = 'No se pudo eliminar el registro';
        }
        $this->redireccionar('option=ObrasSociales');
    }

}