<?php

class postModelo extends Modelo
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getPosts()
    {
        $post = $this->_db->ejecutar("select * from cronos_profesionales");
        return $post->fetchall();
        
        return $post;
    }
    
    public function insertarPost($apellidos, $nombres)
    {
        $this->_db->prepare("INSERT INTO cronos_profesionales VALUES (null, :apellidos, :nombres)")
                ->execute(
                        array(
                           ':apellidos' => $apellidos,
                           ':nombres' => $nombres
                        ));
    }
}