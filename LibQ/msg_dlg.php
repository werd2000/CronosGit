<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LibQ;

/**
 * Description of msg_dlg
 *
 * @author WERD
 */
class msg_dlg {
    
    private $_retorno;
    
    public function __construct($msg='hola') {
        $this->_retorno = '<div id="dialog-message" title="">';
        $this->_retorno .= '<p>';
        $this->_retorno .= '<span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>';
        $this->_retorno .= '<div id="msg"></div>';
        $this->_retorno .= '</p>';
        $this->_retorno .= '</div>';
    }
    
    public function render(){
        return $this->_retorno;
    }
    
    
}
