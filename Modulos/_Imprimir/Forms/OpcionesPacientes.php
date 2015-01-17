<?php

require_once LibQ . 'Zend/Form.php';
require_once LibQ . 'Form/Decorator/IconoInformacion.php';
require_once DIRMODULOS . 'Pacientes/Paciente.php';
//require_once LibQ . 'Form/Decorator/FotoInformacion.php';

/**
 *  Clase para armar el formulario donde se cargan los obrassociales
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package ObrasSociales
 */
class Form_OpcionesPacientes extends Zend_Form
{

    private $_varForm = array();
    private $_paciente;
    
//    private $_config;
    public $elementRequeridoDecorators = array(
        'ViewHelper',
        array('Description', array('tag' => 'span', 'class' => 'element-description')),
        array('Errors'),
        //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array('IconoInformacion', array('placement' => 'append')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $elementDecorators = array(
        'ViewHelper',
        array('Description', array('tag' => 'span', 'class' => 'element-description')),
        array('Errors'),
        //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $elementZendDecorators = array(
        'UiWidgetElement',
        array('Description', array('tag' => 'span', 'class' => 'element-description')),
        array('Errors'),
//        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
        array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
    );
    
    protected $_tipo_informes = array(
        'NOTA PEDIDO IPS'=>'NOTA PEDIDO IPS',
        'CONSTANCIA DE REHABILITACION'=>'CONSTANCIA DE REHABILITACION'
        );

    function __construct(Paciente $paciente)
    {
        $this->addPrefixPath('App_LibQ_Form_Decorator', 'App/LibQ/Form/Decorator', 'decorator');
        $this->addPrefixPath('App_LibQ_ZendX_JQuery_Form_Decorator', 'App/LibQ/ZendX/JQuery/Form/Decorator', 'decorator');
        $this->_paciente = $paciente;
//        $this->_varForm = $varForm;
        parent::__construct();
    }

    public function mostrar()
    {
        $this->setMethod("POST");
        if ($_POST) {
            $valorId = Input::get('id');
            $valorAyN = $_POST['ayn'];
            $valorTipoInforme = $_POST['tipo_informe'];
        }else{
            $valorId = Input::get('id');
            $valorAyN = $this->_paciente->getAyN();
            $valorTipoInforme = '';
        }
        if ($valorId == 0) {
            $this->setAction('index.php?option=imprimir&sub=paciente&id=' . $valorId);
        } else {
            $this->setAction('index.php?option=imprimir&sub=paciente&id=' . $valorId);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmimprimirpaciente');
        $this->setAttrib('enctype', 'multipart/form-data');

        /** Id  * */
        $id = $this->createElement('hidden', 'id', array('value' => $valorId));

        /** Apellidos * */
        $apellidos = $this->createElement('text', 'ayn', array('decorators' => $this->elementDecorators, 'value' => $valorAyN));
        $apellidos->setLabel('Apellidos y Nombre:');
//        $apellidos->setAttrib('disabled', TRUE);
        
        /** Tipo de informe **/
        $tipoInforme = new Zend_Form_Element_Select('tipo_informe');
        $tipoInforme->setDecorators($this->elementDecorators);
        $tipoInforme->setValue($valorTipoInforme);
        $tipoInforme->setOptions(array('multiOptions' => $this->_tipo_informes));
        $tipoInforme->setLabel('Elegir un Informe:');
        
        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($apellidos);
        $this->addElement($tipoInforme);
        
        return $this;
    }

}
