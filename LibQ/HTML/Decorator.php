<?php
/**
 * Description of Decorator
 *
 * @author WERD
 */
class LibQ_HTML_Decorator
{
    protected $_retorno;
    protected $_pre;
            
    function __construct($param = null)
    {
        if(is_null($param)){
            $this->_pre = '';
        }
    }
    
}
