<?php
require_once BASE_PATH . 'Modulos' . DS . 'Usuarios' . DS . 'Modelos' . DS .  'Usuario.php';
require_once 'UsuarioTareaModelo.php';
require_once BASE_PATH . 'LibQ' . DS . 'Fechas.php';

/**
 * Description of Tarea
 *
 * @author WERD
 */
class Tarea
{
    protected $_id;
    protected $_depende_de;
    protected $_id_creador;
    protected $_tipo_tarea;
    protected $_fechaInicio;
    protected $_fechaFin;
    protected $_descripcion;
    protected $_estado;
    protected $_observaciones;
    protected $_eliminado;
    protected $_objCreador;
    protected $_caracter;


    public function getId()
    {
        return $this->_id;
    }
    
    public function getDepende_de()
    {
        return $this->_depende_de;
    }
    
    public function getId_creador()
    {
        return $this->_id_creador;
    }
    
    public function getTipo_tarea()
    {
        return $this->_tipo_tarea;
    }
    
    public function getFechaInicio()
    {
        $fecha = new LibQ_Fecha($this->_fechaInicio);
        return $fecha->getDate();
    }
    
    public function getFechaFin()
    {
        $fecha = new LibQ_Fecha($this->_fechaFin);
        return $fecha->getDate();
    }
    
    public function getDescripcion()
    {
        return $this->_descripcion;
    }
    
    public function getEstado()
    {
        return $this->_estado;
    }
    
    public function getObservaciones()
    {
        return $this->_observaciones;
    }
    
    public function getEliminado()
    {
        return $this->_eliminado;
    }
    
    public function getObjCreador()
    {
        return $this->_objCreador;
    }
    
    public function getCaracter()
    {
        return $this->_caracter;
    }


    public function __construct($tarea)
    {
        $this->_id = $tarea['id'];
        $this->_depende_de = $tarea['depende_de'];
        $this->_id_creador = $tarea['id_creador'];
        $this->_tipo_tarea = $tarea['tipo_tarea'];
        $this->_fechaInicio = $tarea['fechaInicio'];
        $this->_fechaFin = $tarea['fechaFin'];
        $this->_descripcion = $tarea['descripcion'];
        $this->_estado = $tarea['estado'];
        $this->_observaciones = $tarea['observaciones'];
        $this->_eliminado = $tarea['eliminado'];
        $this->_objCreador = $this->_getObjCreador($tarea['id_creador']);
        $this->_caracter = $this->_setCaracter();
    }
    
    private function _setCaracter()
    {
        $caracter = '';
        $hoy = new DateTime("now");
        $datetime2 = date_create($this->_fechaFin);
        $intervalo = $hoy->diff($datetime2);
        if ($intervalo->format('%R%a')>=2 && $intervalo->format('%R%a')<=5){
            $caracter = 'proximo';
        }elseif ($intervalo->format('%R%a')<=2) {
            $caracter = 'urgente';
        }
        return $caracter;
    }

        public static function getTareas($lista)
    {
        $resultado = array();
        if (is_array($lista)){
            foreach ($lista as $tarea) {
                $resultado[] = new Tarea($tarea);
            }
        }
        return $resultado;
    }
    
    private function _getObjCreador($id)
    {
        $usuario = new Tareas_Modelos_usuarioTareaModelo();
        return new Usuario($usuario->getUsuario($id));
    }
}

