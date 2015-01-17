<?php

require_once (BASE_PATH . 'LibQ' . DS . 'archivosYcarpetas.php');
require_once LIB_PATH . 'BarraHerramientas.php';
require_once BASE_PATH . 'Modelos' . DS . 'IndexModelo.php';
require_once LIB_PATH . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php';
require_once LIB_PATH . 'jpgraph' . DS . 'src' . DS . 'jpgraph_bar.php';

class Controladores_IndexControlador extends App_Controlador
{

    protected $_modelo;

    public function __construct()
    {
        parent::__construct();
        $this->_modelo = new Modelos_IndexModelo();
    }

    public function index()
    {
        $this->isAutenticado();
        $modulos = \LibQ_ArchivosYcarpetas::listar_directorios_ruta('Modulos/');
        $menu = $this->_crearMenuModulos($modulos);
        /** Barra de herramientas */
        $bh = new LibQ_BarraHerramientas();
        foreach ($menu as $key => $value) {
            $bh->addBoton($key, $value);
        }
        $this->_vista->_barraHerramientas_principal = $bh->render();
        /* Me fijo si hay modulos para cargar */
        if(is_array($this->_getModulosVista())){
            $this->_vista->modulos = $this->_getModulosVista();    
        }
        $this->_vista->titulo = APP_NAME;
        $this->_vista->renderizar('Index');
    }

    /**
     * Creacion y muestra de modulos de vista
     */
    private function _getModulosVista()
    {
        $sec_modulos = false;
//        $path = BASE_PATH . 'Modulos' . DS;
//        $sec_modulos = array(
//            $path . 'Compras' . DS . 'Vistas' . DS . 'Index' . DS . 'UltimasCompras.phtml',
//            $path . 'Ventas' . DS . 'Vistas' . DS . 'Index' . DS . 'UltimasVentas.phtml',
//            $path . 'Honorarios' . DS . 'Vistas' . DS . 'Index' . DS . 'UltimosHonorarios.phtml',
//            $path . 'Sueldos' . DS . 'Vistas' . DS . 'Index' . DS . 'UltimosSueldos.phtml',
//            'Vistas' . DS . 'Index' . DS . 'estadisticas.phtml',
//        );
        return $sec_modulos;
    }

    /**
     * Crea el menu de los modulos en forma de Array
     * @param Array $modulos
     * @return string
     */
    private function _crearMenuModulos($modulos)
    {
        foreach ($modulos as $modulo) {
            if ($this->_controlModuloVisible($modulo)) {
                $menu[$modulo] = array(
                    'id' => strtolower($modulo),
                    'titulo' => ucfirst($modulo),
                    'href' => BASE_URL . '?option=' . ucfirst($modulo),
                    'icono' => 'Modulos/' . ucfirst($modulo) .
                    '/Vistas/Index/Mod_' . ucfirst($modulo) . '.png',
                    'classIcono' => '',
                    'class' => 'btn btn-primary'
                );
            }
        }
        return $menu;
    }

    /**
     * Controla que el modulo sea visible.
     * Un módulo es invisible cuando comienza con "_".
     * @param String $modulo
     * @return boolean
     */
    private function _controlModuloVisible($modulo)
    {
        $retorno = false;
        $caracter = substr($modulo, 0, 1);
        if ($caracter != "_") {
            $retorno = true;
        }
        return $retorno;
    }

 
    /**
     * Calcula estadisticas para mostrar en el index
     * @return string
     */
    private function _datosEstadistica()
    {
        $egresos = $this->_modelo->getTotalEgresos();
        $sueldos = $this->_modelo->getTotalSueldos();
        $totalIngresos = $this->_modelo->getTotalIngresos();
        $estadisticas['TotalEgresos'] = $egresos['TotalEgresos'] + $sueldos['TotalSueldos'];
        $estadisticas['TotalIngresos'] = $totalIngresos['TotalIngresos'];
        $estadisticas['TotalResultado'] = $estadisticas['TotalIngresos'] -
                $estadisticas['TotalEgresos'];
        $estadisticas['grafica'] = BASE_URL . '?option=Grafico&met=graficaSexos&t=' .
                $estadisticas['TotalEgresos'] . '&v=' . $estadisticas['TotalIngresos'] .
                '&m=' . $estadisticas['TotalResultado'];
        return $estadisticas;
    }

}
