<?php

class App_Install 
{
    public function __construct()
    {
        if(is_readable(BASE_PATH . 'Install')){
//            header('location:' . BASE_URL . 'Install' . DS .'index.php');
        }
    }
}
