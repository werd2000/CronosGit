<?php

//require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'ManejadorBaseDeDatosInterface.php';
//require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'Sql.php';

//require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'ResultDecorator.php';
//'App/LibQ/Bd/ResultDecorator.php';
//require_once 'App/LibQ/Bd/Decoradores/ObjetoResultDecorator.php';
//require_once 'App/LibQ/Bd/Decoradores/ArrayResultDecorator.php';

class BaseDeDatos
{
    private $_manejador;
    private $_tipoDatos='Objeto';    //Array, Json, String, Xml
    
    public function __construct (ManejadorBaseDeDatosInterface $manejador)
    {
        $this->_manejador = $manejador;
    }
    
    public function setTipoDatos($tipo)
    {
        $this->_tipoDatos = $tipo;
    }
    
    public function setTipoResultado($tipoResultado)
    {
        $this->_tipoResultado = $tipoResultado;
    }
    
    public function query ($sql)
    {
        if (!is_a($sql, 'Sql')){
            $sql = new Sql($sql);
        }
        $this->_manejador->getInstance();
        $this->_manejador->query($sql); 
//        switch ($this->_tipoDatos){
//            case 'Objeto':
//                $resultDecorator = new ResultDecorator($datos);
//                $result = new ObjetoResultDecorator ($resultDecorator);
//                $retorno = $result->getObjeto();
//                break;
//            case 'Array':
//                $resultDecorator=new ResultDecorator($datos);
//                $result = new ArrayResultDecorator ($resultDecorator);
//                $retorno = $result->getArray();
//                break;
//            case 'JSON':
//                $resultDecorator=new ResultDecorator($datos);
//                $result = new JSONResultDecorator ($resultDecorator);
//                $retorno = $result->getJson();
//                break;
//            case 'XML':
//                $resultDecorator=new ResultDecorator($datos);
//                $result = new XMLResultDecorator ($resultDecorator);
//                $retorno = $result->displayXML();
//                break;
//        }
//        return $datos;
    }
    
    public function fetchRow()
    {
        $this->_manejador->getInstance();
        $result = $this->_manejador->fetchRow(); 
        return $result;
    }
    
    /**
     * Inserta un registro en la BD
     * regresa el núm de id del último registro
     * @param string $table
     * @param array $data
     * @return int $insertId
     */
    public function insert($table, array $data)
    {
        $this->_manejador->getInstance();
        $insertId = $this->_manejador->insert($table, $data);
        return $insertId;
    }
    
    /**
     * Actualiza los datos de una tabla de acuerdo a la condición dada
     * Regresa la cantidad de filas afectadas
     * @param string $table
     * @param array $data
     * @param string $condicion
     * @return int AffectedRows 
     */
    public function editar($table, array $data, $condicion)
    {
        $this->_manejador->getInstance();
        $affectedRows = $this->_manejador->update($table, $data, $condicion);
        return $affectedRows;
    }
    
    /**
     * Elimina los datos de una tabla de acuerdo a la condición dada
     * Regresa la cantidad de filas afectadas
     * @param string $table
     * @param string $condicion
     * @return int AffectedRows 
     */
    public function eliminar($table, $condicion = '')
    {
        $this->_manejador->getInstance();
        $affectedRows = $this->_manejador->delete($table, $condicion);
        return $affectedRows;
    }
 
    public function fetchAll()
    {
        $this->_manejador->getInstance();
        $result = $this->_manejador->fetchAll(); 
        return $result;
        
    }
    
    private function _configurarDato($datos)
    {
        switch ($this->_tipoDatos){
            case 'Objeto':
                var_dump($datos);
                $resultDecorator = new ResultDecorator($datos);
                $result = new ObjetoResultDecorator ($resultDecorator);
                $retorno = $result->getObjeto();
                break;
            case 'Array':
                $resultDecorator=new ResultDecorator($datos);
                $result = new ArrayResultDecorator ($resultDecorator);
                $retorno = $result->getArray();
                break;
            case 'JSON':
                $resultDecorator=new ResultDecorator($datos);
                $result = new JSONResultDecorator ($resultDecorator);
                $retorno = $result->getJson();
                break;
            case 'XML':
                $resultDecorator=new ResultDecorator($datos);
                $result = new XMLResultDecorator ($resultDecorator);
                $retorno = $result->displayXML();
                break;
        }
        return $retorno;
    }
}

