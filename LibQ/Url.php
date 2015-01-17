<?php

/**
 * Manejador de URL
 *
 * @author WERD
 */
class Url
{
    /**
     * Las clases de caracteres para la validación de expresiones regulares
     */
    const CHAR_ALNUM    = 'A-Za-z0-9';
    const CHAR_MARK     = '-_.!~*\'()\[\]';
    const CHAR_RESERVED = ';\/?:@&=+$,';
    const CHAR_SEGMENT  = ':@&=+$,;';
    const CHAR_UNWISE   = '{}|\\\\^`';
    
    
    /**
     * Url port
     * @var string
     */
    protected $_port = '';
    
    /**
     * esquema de esta Url (http, ftp, etc.)
     * @var string
     */
    protected $_scheme = '';
    
    /**
     * Global configuration array
     * @var array
     */
    static protected $_config = array(
        'allow_unwise' => false
    );
    
    
    /**
     * Constructor acepta un esquema en string $scheme (e.g., http, https)
     * y un string de url (ej example.com/path/to/resource?query=param#fragment)
     *
     * @param  string $scheme         The scheme of the URI
     * @param  string $schemeSpecific The scheme-specific part of the URI
     * @throws Exception cuando la Url no es valida
     */
    protected function __construct($scheme, $schemeSpecific = '')
    {
        // Setea el esquema
        $this->_scheme = $scheme;

        // Establecer reglas gramaticales para la validación a través de expresiones regulares.
        // Estos son para ser utilizado con barra delimitados por cadenas de expresiones regulares.

        // Caracteres especiales escapados (ej '%25' para '%')
        $this->_regex['escaped']    = '%[[:xdigit:]]{2}';

        // Caracteres no reservados
        $this->_regex['unreserved'] = '[' . self::CHAR_ALNUM . self::CHAR_MARK . ']';

        // Segmento puede utilizar escapado, sin reservas o un conjunto de caracteres adicionales
        $this->_regex['segment']    = '(?:' . $this->_regex['escaped'] . '|[' .
            self::CHAR_ALNUM . self::CHAR_MARK . self::CHAR_SEGMENT . '])*';

        // Path puede ser una serie de segmentos char strings seperated by '/'
        $this->_regex['path']       = '(?:\/(?:' . $this->_regex['segment'] . ')?)+';

        // URI characters can be escaped, alphanumeric, mark or reserved chars
        $this->_regex['uric']       = '(?:' . $this->_regex['escaped'] . '|[' .
            self::CHAR_ALNUM . self::CHAR_MARK . self::CHAR_RESERVED .

        // Si se permiten caracteres imprudentes, agregarlos a la clase caracteres URI
            (self::$_config['allow_unwise'] ? self::CHAR_UNWISE : '') . '])';

        // Si no hay esquema específico. No hay nada que hacer
        if (strlen($schemeSpecific) === 0) {
            return;
        }

        // Analizar las el esquema específico
        $this->_parseUri($schemeSpecific);

        // Validar la URI
        if ($this->valid() === false) {
            throw new Exception('Url inválido');
        }
    }

    public static function obtenerURL()
    {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"])) {
            if ($_SERVER["HTTPS"] == "on") {
                $pageURL .= "s";
            }
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }

        return $pageURL;
    }

    public static function validarQueryString($queryString)
    {
        $query = strip_tags($queryString);
        $query1 = utf8_decode($query);
        $query2 = urldecode($query1);
        if (strpos($query2, 'Transcripts.php') === false && strpos($query2, 'medical alert') === false) {

            $search = array("..//", "*", "../", "/.", "<", ">", "alert", "(", ")", "script", "javascript", "///", "union", "%3dalert", "{", "}", "\n", "%22", "%27", " ' ", "%23", "%3C", "%2F", "%", "%3E", "%3D", "%7B", "%7D", "%3F", "%3B", "%25", "%28", "%29", "%2A", "%26");
            $VAL = str_replace($search, "#", $queryString);
            $ddd = preg_match("/([\#\'\%\*])/ ", $VAL);
            if ($ddd == 1) {
                return false;
            }
        }
        return true;
    }

}

