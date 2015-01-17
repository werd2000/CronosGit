<?php
require_once APP_PATH . 'Modelo.php';

class Modelos_IndexModelo extends App_Modelo
{
    
    /**
     * Obtiene una lista de los gastos ordenados por id
     * @return array Gasto
     */
    public function getUltimasCompras()
    {
        $this->_verEliminados = 0;
        $retorno = '';
        $sql = 'SELECT * FROM conta_compras WHERE eliminado = '. $this->_verEliminados . ' ORDER BY id DESC LIMIT 3';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $array = $this->_db->fetchall();
        if(is_array($array)){
            foreach ($array as $value) {
                $retorno[] = new Compra($value);
            }
        }
        return $retorno;
    }

    /**
     * Obtiene el total de los egresos
     * @return double
     */
    public function getTotalEgresos()
    {
        $this->_verEliminados = 0;
        $retorno = '';
        $sql = 'SELECT SUM(total) as TotalEgresos FROM conta_compras WHERE eliminado = '. $this->_verEliminados;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $array = $this->_db->fetchRow();
        return $array;
    }

    /**
     * Obtiene el total de los sueldos
     * @return double
     */
    public function getTotalSueldos()
    {
        $this->_verEliminados = 0;
        $retorno = '';
        $sql = 'SELECT SUM(total) as TotalSueldos FROM conta_sueldos WHERE eliminado = '. $this->_verEliminados;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $array = $this->_db->fetchRow();
        return $array;
    }

    
    /**
     * Obtiene el total de los ingresos
     * @return double
     */
    public function getTotalIngresos()
    {
        $this->_verEliminados = 0;
        $retorno = '';
        $sql = 'SELECT SUM(total) as TotalIngresos FROM conta_ingresos WHERE eliminado = '. $this->_verEliminados;
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $array = $this->_db->fetchRow();
        return $array;
    }
    
    /**
     * Obtiene una lista de los ingresos ordenados por id
     * @return array Venta
     */
    public function getUltimasVentas()
    {
        $this->_verEliminados = 0;
        $retorno = '';
        $sql = 'SELECT * FROM conta_ingresos WHERE eliminado = '. $this->_verEliminados . ' ORDER BY id DESC LIMIT 3';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $array = $this->_db->fetchall();
        if(is_array($array)){
            foreach ($array as $value) {
                $retorno[] = new Venta($value);
            }
        }
        return $retorno;
    } 
    
    
    /**
     * Obtiene una lista de los gastos ordenados por id
     * @return array Honorario
     */
    public function getUltimosHonorarios()
    {
        $retorno = array();
        $this->_verEliminados = 0;
        $sql = 'SELECT * FROM conta_honorarios WHERE eliminado = ' . $this->_verEliminados . ' ORDER BY id DESC LIMIT 3';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $array = $this->_db->fetchall();
        if(is_array($array)){
            foreach ($array as $value) {
                $retorno[] = new Honorario($value);
            }
        }
        return $retorno;
    }
    
    /**
     * Obtiene una lista de los gastos ordenados por id
     * @return array Sueldo
     */
    public function getUltimosSueldos()
    {
        $retorno = array();
        $this->_verEliminados = 0;
        $sql = 'SELECT * FROM conta_sueldos WHERE eliminado = ' . $this->_verEliminados . ' ORDER BY id DESC LIMIT 3';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        $array = $this->_db->fetchall();
        if(is_array($array)){
            foreach ($array as $value) {
                $retorno[] = new Sueldo($value);
            }
        }
        return $retorno;
    }
    
    /**
     * Obtiene una lista de los clientes ordenados por id
     * @return array Cliente
     */
    public function getClientes()
    {
        $this->_verEliminados = 0;
        $sql = 'SELECT * FROM conta_clientes WHERE eliminado = '. $this->_verEliminados . ' ORDER BY id';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return Cliente::getClientes($this->_db->fetchall());
    }
    
    /**
     * Obtiene un cliente
     * @return Object Cliente
     */
    public function getCliente($condicion)
    {
        $this->_verEliminados = 0;
        $sql = 'SELECT * FROM conta_clientes WHERE ' . $condicion .
                ' AND eliminado = '. $this->_verEliminados . ' ORDER BY id';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return new Cliente($this->_db->fetchRow());
    }
    

    public function listadoProfesionales($campos = array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_profesionales');
        $sql->addOrder('apellido, nombre');
        $sql->addWhere('eliminado=' . $this->_verEliminados);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->_db->fetchAll($sql);
        return $result;
    }

    public function listadoCuentas($campos = Array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_cuentas');
        $sql->addOrder('cuenta');
        $sql->addWhere('grupo_cuenta = 3');
        $sql->addWhere('eliminado=' . $this->_verEliminados);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $this->_db->fetchAll($sql);
        return $result;
    }

    public function guardar($datos = array())
    {
        $this->_db->insert('conta_clientes', $datos);
        return $this->_db->lastInsertId();
    }

    public function listadoClientes($inicio, $orden)
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('clientes.id,
        		clientes.razon_social,
        		clientes.domicilio,
        		clientes.condicion_iva,
        		clientes.cuit,
        		clientes.tel,
                        clientes.cel,
                        clientes.email
        ');
        //        $sql->addSelect('conta_profesionales.apellido');
        $sql->addTable('conta_clientes as clientes');
        $sql->addLimit($inicio, 30);
        $sql->addOrder($orden);
        $sql->addWhere('clientes.eliminado=' . $this->_verEliminados);
//                echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function getCantidadRegistros()
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_clientes.id');
        $sql->addTable('conta_clientes');
        $sql->addWhere('eliminado=' . $this->_verEliminados);
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        $cantidad = count($resultado);
        return $cantidad;
    }

    public function buscarHonorario($where)
    {
        if (!is_string($where)) {
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_clientes.id,
                        conta_clientes.cuenta,
                        conta_clientes.profesional,
                        conta_clientes.fecha_comprobante,
                        conta_clientes.comprobante,
                        conta_clientes.tipo_comprobante,
                        conta_clientes.nro_comprobante,
                        conta_clientes.importe_gravado,
                        conta_clientes.importe_nogravado,
                        conta_clientes.iva_inscripto,
                        conta_clientes.iva_diferencial,
                        conta_clientes.percepcion,
                        conta_clientes.total
        ');
        $sql->addTable('conta_clientes');
        $sql->addWhere($where);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);
        //        var_dump($resultado);
        return $resultado;
    }

    public function actualizar($datos = array(), $where = '')
    {
        try {
            $regModif = $this->_db->update('conta_clientes', $datos, $where);
            return $regModif;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function eliminar($where = '')
    {
        $n = $this->_db->delete('conta_clientes', $where);
        return $n;
    }

}
