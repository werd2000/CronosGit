<?php

class LibQ_ValidarFormulario
{

    private $_validType = array('text', 'numeric', 'email', 'empty', 'null');
    private $_errString = "";
    private $_retval = false;
    private $_errtxtPrefix = '<div class="errortxt">';
    private $_errtxtSufix = '</div>';
//    private $_errPrefix = '<fieldset  class="errorhead"><legend>Error de Validación</legend>';
    private $_errPrefix = ''; //'Error de Validación:';
//    private $_errSufix = '<br /></fieldset>';
    private $_errSufix = '<br />';

    public function __construct()
    {
        
    }

    public function IsEmail($value)
    {
        if (!$this->IsEmpty($value)) {
            if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $value)) {
                return false;
            } else {
                return true;
            }
        } elseif ($this->IsEmpty($value)) {
            return false;
        }
    }

    public function IsText($value)
    {
        $this->_retval = false;
        if (!$this->IsEmpty($value)) {
            if (is_string($value)) {
                return true;
            } else {
                return false;
            }
        } elseif ($this->IsEmpty($value)) {
            return false;
        }
    }

    public function IsNull($value)
    {
        if (is_null($value)) {
            return true;
        } else {
            return false;
        }
    }

    public static function IsHtml()
    {
        
    }

    public function IsSCheck($value)
    {
        if (count($value) <= 0) {
            return false;
        } else {
            return true;
        }
    }

    public function IsMCheck($value)
    {
        if (count($value) <= 1) {
            return false;
        } else {
            return true;
        }
    }

    public function IsEmpty($value)
    {
        if (strlen(trim($value)) == 0 || trim($value) == "") {
            return true;
        } else {
            return false;
        }
    }

    public function IsValidType($Type)
    {
        if (in_array($this->_validType)) {
            return true;
        } else {
            return false;
        }
    }

    public function IsNumeric($value)
    {
        if (ctype_digit($value)) {
            $retorno = 1;
        } else {
            $retorno = 0;
        }
        return $retorno;
    }

    public function IsNumericLong($value)
    {
        $retorno = 0;
        if (ctype_digit($value)) {
            if (is_long($value)) {
                $retorno = 1;
            }
        }
        return $retorno;
    }

    public function IsNumeroFactura($value)
    {
        $retorno = 0;
        $existeGuion = stripos($value, '-');
        if ($existeGuion === false) {   //no tiene guión
            if (strlen($value) == 11 AND ctype_digit($value)) {
                $retorno = 1;
            }
        } else {
            $pv = substr($value, 0,4);
            $nf = substr($value, 5);
            if (strlen($value) == 13 AND ctype_digit($pv)==1 AND ctype_digit($nf)) {
                $retorno = 1;
            }  else {
                $this->SetError('Número Comprobante: Longitud inválida');    
            }
        }
        return $retorno;
    }
    
    public function IsMoneda($value)
    {
        $retorno = 0;
        if (is_float($value)) {
            $retorno = 1;
        }
        return $retorno;
    }
    
    public function IsFecha($value, $format = 'Y-m-d')
    {
        $version = explode('.', phpversion());
        if (((int) $version[0] >= 5 && (int) $version[1] >= 2 && (int) $version[2] > 17)) {
            $d = DateTime::createFromFormat($format, $value);
        } else {
            $d = new DateTime(date($format, strtotime($value)));
        }
        return $d && $d->format($format) == $value;
    }

    public function SetError($Err)
    {
        $this->_errString[] = $this->_errPrefix . $this->_errtxtPrefix . $Err . $this->_errtxtSufix;
    }

    public function ValidField($Value, $CType, $ErrText = "Invalid Error Type", $Params = array('Default' => '', 'Min' => false, 'Max' => false))
    {
        switch ($CType) {
            case "moneda":
                if ($this->IsMoneda($Value) == 0){
                    $this->SetError($ErrText);
                    $this->_retval = TRUE;
                }
                break;
            case "numeroFactura":
                if ($this->IsNumeroFactura($Value) == 0){
                    $this->SetError($ErrText);
                    $this->_retval = TRUE;
                }
                break;
            case "date":
                if ($this->IsFecha($Value) == 0){
                    $this->SetError($ErrText);
                    $this->_retval = TRUE;
                }
                break;
            case "text":
                if (!$this->IsText($Value)) {
                    $this->SetError($ErrText);
                    $this->_retval = TRUE;
                }
                break;
            case "numeric":
                echo $Value;
                if (!$this->IsNumeric($Value)) {
                    $this->SetError("Número Inválido" . ': ' . $ErrText);
                    $this->_retval = TRUE;
                }
                break;
            case "email":
                echo 'valido email';
                break;
            case "empty":
                echo 'valido vacio';
                break;
            case "null":
                echo 'valido null';
                break;
            case "SCheck":
                echo 'evaluo scheck';
                break;
            case "MCheck":
                echo 'evaluo mcheck';
                break;
            case "RCheck":
                echo 'evaluo rcheck';
                break;
            case "Select":
                echo 'evaluo select';
                break;
            default:
                $this->SetError($this->_errString);
                break;
        }
//        echo $this->_errString;
        return $this->_retval;
    }

//    public function ValidField($Value, $CType, $ErrText = "Invalid Error Type", $Params = array('Default' => '', 'Min' => false, 'Max' => false))
//    {
//        switch ($CType) {
//            case "text":
//                if (!$this->IsEmpty($Value) && $Params['Min'] && strlen(trim($Value)) < $Params['Min']) {
//                    $ErrText = " Required Minimum  " . $Params['Min'] . " Character";
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                } elseif (!$this->IsEmpty($Value) && $Params['Max'] && strlen(trim($Value)) > $Params['Max']) {
//                    $ErrText = " Required Maximum " . $Params['Max'] . " Character";
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                } elseif (!$this->IsText($Value)) {
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                } else {
//                    $this->_retval = true;
//                }
//                break;
//            case "numeric":
//                //Si no esta vacio y tiene min y la long es menor al min
//                if (!self::IsEmpty($Value) && $Params['Min'] && strlen(trim($Value)) < $Params['Min']) {
//                    $ErrText = " Required Minimum  " . $Params['Min'] . " Number";
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                //Si no esta vacio y tiene max y la long es mayor al max
//                } elseif (!self::IsEmpty($Value) && $Params['Max'] && strlen(trim($Value)) > $Params['Max']) {
//                    $ErrText = " Required Maximum  " . $Params['Max'] . " Number";
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                //Si es numerico o double
//                } elseif ($this->IsNumeric($Value)==0  || is_float($Value)) {
//                    $ErrText1 = "Número Inválido";
//                    $this->SetError($ErrText1.': '.$ErrText);
//                    $this->_retval = TRUE;
//                //Si está vacio
//                } elseif (self::IsEmpty($Value)) {                    
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                } else {
////                    $this->_retval = true;
//                }
//                break;
//            case "email":
//                if (!self::IsEmail($Value)) {
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                } else {
//                    $this->_retval = true;
//                }
//                break;
//            case "empty":
//                if (self::IsEmpty($Value)) {
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                } else {
//                    $this->_retval = true;
//                }
//                break;
//            case "null":
//                if (self::IsNull($Value)) {
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                } else {
//                    $this->_retval = true;
//                }
//                break;
//            case "SCheck":
//                if (!$this->IsSCheck($Value)) {
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                } else {
//                    $this->_retval = true;
//                }
//                break;
//            case "MCheck":
//                if (!$this->IsMCheck($Value)) {
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                } else {
//                    $this->_retval = true;
//                }
//                break;
//            case "RCheck":
//                if (!isset($Value) || !$this->IsSCheck($Value)) {
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                } else {
//                    $this->_retval = true;
//                }
//                break;
//            case "Select":
//                if (!isset($Value) || $Value == $Params['Default']) {
//                    $this->SetError($ErrText);
//                    $this->_retval = false;
//                } else {
//                    $this->_retval = true;
//                }
//                break;
//            default:
//                $this->SetError($this->_errString);
//                break;
//        }
////        echo $this->_errString;
//        return $this->_retval;
//    }

    /**
     * Devuelve true si la validación falló
     * @return boolean
     */
    public function getRetEval()
    {
        return $this->_retval;
    }

    public function getErrString()
    {
        return $this->_errString;
    }

}
