<?php

interface ManejadorBaseDeDatosInterface {

    /**
     * Conecta con la BD
     * @return void
     */
    public function conectar();

    /**
     * Desconecta de la BD
     * @return void
     */
    public function desconectar();

    /**
     * Ejecuta una consulta para insert y update
     * @param SQL $sql
     * @return unknown_type
     */
    public function query(SQL $sql);

    /**
     * Obtiene multiples registros
     * @return array Retorna un arreglo con o sin resultados
     */
    public function fetchAll();

    /**
     * Obtiene un solo registro
     * @param string $sql Consulta SQL
     * @return array Retorna un arreglo con o sin resultados
     */
    public function fetchRow();
    
    /**
     * Ejecuta una consulta Select
     * @param $table, $where, $fields, $order, $limit, $offset
     * @return unknown_type
     */
    public function select($table, $where, $fields, $order, $limit, $offset);

    /**
     * Ejecuta una consulta Insert
     * @param $table, $data
     * @return int
     */
    public function insert($table, array $data);

    /**
     * Ejecuta una consulta Update
     * @param $table, $data, $where
     * @return int
     */
    public function update($table, array $data, $where);

    /**
     * Ejecuta una consulta Delete
     * @param $table, $where
     * @return int
     */
    public function delete($table, $where);

    /**
     * Obtiene el útlimo id
     * @return int
     */
    public function getInsertId();

    /**
     * Obtiene la cantidad de registros
     * @return int
     */
    public function countRows();

    /**
     * Obtiene la cantidad de filas afectadas
     * por la consulta Update y/o Delete
     * @return int
     */
    public function getAffectedRows();
}