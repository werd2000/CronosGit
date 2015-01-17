<?php

/**
 * Interface para implementación de la clase Contacto
 * @access public
 * @author WERD
 */
interface iContacto {
    
    /**
     * Obtiene un contacto
     * @access public
     * @author WERD
     * @return Objeto Contacto
     */
    public function getContacto();

    /**
     * Obtiene la lista de contactos
     * @access public
     * @author WERD
     * @return array Contacto
     */
    public function getContactos();
}

