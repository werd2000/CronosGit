<?php


/**
 * Email
 *
 * @author WERD
 */
class Email
{
    protected $_etiquetaApertura = '<span>';
    protected $_etiquetaCierre = '</span>';
    
    public static function mostrarEmailHtml($email = '')
    {
        $retorno = '';
        if ($email != ''){
            $retorno = $this->_etiquetaApertura . 
                    '<a href=' .
                    $email 
                    . $this->_etiquetaCierre;
        }
        return $retorno;
    }
}


