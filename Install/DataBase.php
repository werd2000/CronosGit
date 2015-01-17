<?php

require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'BaseDeDatos.php';
require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'MySQL.php';

class App_DataBase extends BaseDeDatos
{
    public function __construct()
    {
        $adapter = MySQLAdapter::getInstance(array(DB_HOST,DB_USER,DB_PASS,DB_NAME));
        parent::__construct($adapter);        
    }
}