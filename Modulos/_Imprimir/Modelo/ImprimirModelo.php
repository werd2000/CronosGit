<?php

//include_once LibQ . 'ModelBase.php';
//require_once DIR_MODULOS . 'Imprimir/Controlador/CriterioFiltro.php';
/**
 *  Clase para interactuar con la BD en el modulo Login
 *  @author Walter Ruiz Diaz
 *  @see LibQ_ModelBase
 *  @category Modelo
 *  @package Login
 */
class ImprimirModelo extends ModelBase
{

    function __construct()
    {
        parent::__construct();
    }

    public function guardar($datos=array())
    {
        try {
            $this->_db->insert('cronos_imprimir', $datos);
            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actualizar($datos=array(), $where='')
    {
        $this->_db->update('cronos_imprimir', $datos, $where);
    }

    public function buscarProfesional($where = array())
    {
        if (!is_array($where)) {
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('cronos_imprimir.id,
        		cronos_imprimir.denominacion,
        		cronos_imprimir.direccion
                        ');
        $sql->addTable('cronos_imprimir');
        foreach ($where as $condicion) {
            $sql->addWhere($condicion);
        }
        $sql->addFuncion('SELECT');
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);
        return $resultado;
    }

    public function listadoImprimir($inicio,$fin,$orden,  $filtro,  $campos=array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('cronos_imprimir');
        $sql->addOrder($orden);
        $sql->addWhere('eliminado=' . $this->_verEliminados);
        if (is_object($filtro)){
            $sql->addWhere($filtro->__toString());
        }
        if ($fin > 0){
            $sql->addLimit($inicio, $fin);
        }
        try {
            $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
            $result = $this->_db->fetchAll($sql);
            return $result;
        }catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function getCantidadRegistros($filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('cronos_imprimir.id,
        		cronos_imprimir.apellidos,
        		cronos_imprimir.nombres
                        ');
        $sql->addTable('cronos_imprimir');
        
        if (!$filtro == '') {
            $sql->addWhere($filtro);
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return count($resultado);
    }

}
