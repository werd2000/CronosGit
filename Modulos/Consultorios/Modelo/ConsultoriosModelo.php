<?php

//require_once LibQ . 'ModelBase.php';
//require_once DIR_MODULOS . 'Consultorios/Controlador/CriterioFiltro.php';
/**
 *  Clase para interactuar con la BD en el modulo Consultorios
 *  @author Walter Ruiz Diaz
 *  @see LibQ_ModelBase
 *  @category Modelo
 *  @package Consultorios
 */
class ConsultoriosModelo extends ModelBase
{

    function __construct()
    {
        parent::__construct();
    }

    public function guardar($datos=array())
    {
        try {
            $this->_db->insert('conta_consultorios', $datos);
            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actualizar($datos=array(), $where='')
    {
        $this->_db->update('conta_consultorios', $datos, $where);
    }

    /**
     * Busca los datos de un salón según condición de búsqueda
     * @param array $where. La condicion de búsqueda.
     * @return Zend_Db_Table_Row_Abstract|null 
     */
    public function buscarSalon($where = array())
    {
        if (!is_array($where)) {
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('id,
        		consultorio,
        		division,
        		turno,
        		docente
                        ');
        $sql->addTable('conta_consultorios');
        foreach ($where as $condicion) {
            $sql->addWhere($condicion);
        }
        $sql->addFuncion('SELECT');
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);
        return $resultado;
    }

    public function listadoConsultorios($inicio,$fin, $orden,  $filtro,  $campos=array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('
        	conta_consultorios as consultorios LEFT JOIN conta_docentes as docentes ON consultorios.docente=docentes.id
                LEFT JOIN conta_turnos as turnos ON consultorios.turno=turnos.id
        ');
        $sql->addOrder('consultorios.'.$orden);
        $sql->addWhere('consultorios.eliminado=' . $this->_verEliminados);
        if (is_object($filtro)){
            $sql->addWhere($filtro->__toString());
        }
        $fin = $inicio + $this->_limite ;
        if ($fin > 0){
            $sql->addLimit($inicio, $fin);
        }
//        echo $sql;
        try {
            $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
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
        $sql->addSelect('conta_consultorios.id,
        		conta_consultorios.consultorio,
        		conta_consultorios.turno
                        ');
        $sql->addTable('conta_consultorios');
        
        if (!$filtro == '') {
            $sql->addWhere($filtro);
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return count($resultado);
    }

}
