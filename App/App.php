<?php
require_once 'Install.php';
class App_App
{

    public function __construct()
    {
        new App_Install();
        try {
            $bootstrap = new App_Bootstrap(new App_Request());
            $bootstrap->run();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
