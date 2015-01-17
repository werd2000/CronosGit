<?php

class App_Modelo
{
    protected $_db;
    
    public function __construct()
    {
        $this->_db = new App_DataBase();
    }
}