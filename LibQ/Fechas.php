<?php

/**
 * Clase fecha. Permite crear y obtener fechas en formatos ar y bd
 * @see DateTime
 */
class LibQ_Fecha extends DateTime {

    /**
     * Fecha y hora actual
     * @var Object
     */
    private $_fecha;

    /**
     * Zona horaria por defecto
     * @var Object
     */
    private $_zone = 'America/Argentina/Buenos_Aires';
    private $_diasSemana = array(
        '1' => 'Lunes',
        '2' => 'Martes',
        '3' => 'Miércoles',
        '4' => 'Jueves',
        '5' => 'Viernes',
        '6' => 'Sábado',
        '7' => 'Domingo'
    );
    private $_mesAnio = array(
        '01' => 'Enero',
        '02' => 'Febrero',
        '03' => 'Marzo',
        '04' => 'Abril',
        '05' => 'Mayo',
        '06' => 'Junio',
        '07' => 'Julio',
        '08' => 'Agosto',
        '09' => 'Septiembre',
        '10' => 'Octubre',
        '11' => 'Noviembre',
        '12' => 'Diciembre'
    );

    /**
     * Class constructor
     * @param $fecha
     * @param String  $zone, timezone
     */
    public function __construct($fecha = 'now', $zone = NULL) {
        if ($fecha != 'now') {
            $this->_fecha = parent::__construct(trim($fecha), new DateTimeZone($this->_zone));
        }
        $this->_fecha = strtotime($fecha);
        $this->time_details = $this->toArray($fecha);
    }

    private function _date($date = '') {
        return $date == '' ? $this->_fecha : new DateTime($date);
    }

    /**
     * Obtiene la fecha y hora y retorna el detalle como objeto
     * @param string $date_time
     * @return Object con (Year, Month, Day, WeekOfTheMonth, Day Position, Day name)
     */
    function toArray($date_time = '') {
        if ($date_time == '') {
            return $this->time_details;
        }

        $time = $this->getTimestamp($date_time);

        $time_details = new stdClass();
        $time_details->y = ($y = intval(date("Y", $time))) ? $y : null;
        $time_details->m = ($m = intval(date("m", $time))) ? $m : null;
        $time_details->d = ($d = intval(date("d", $time))) ? $d : null;
        $time_details->h = ($h = intval(date("h", $time))) ? $h : null;
        $time_details->i = ($i = intval(date("i", $time))) ? $i : null;
        $time_details->s = ($s = intval(date("s", $time))) ? $s : null;
        $time_details->day_position = date('N', $time);
        $time_details->week_of_the_month = ceil(date('j', $time) / 7);
        return $time_details;
    }

    /**
     * Obtiene la fecha en formato d-m-Y
     * @return date
     */
    public function getFecha($separador ='-') {
        return date('d'.$separador.'m'.$separador.'Y', $this->_fecha);
    }
    
    /**
     * Obtiene la fecha en formato Y-m-d
     * @return date
     */
    public function getDate($separador ='-') {
        return date('Y'.$separador.'m'.$separador.'d', $this->_fecha);
    }
    
    /**
     * Obtiene la fecha en formato Y-m
     * @return date
     */
    public function getYearMonth($separador ='-') {
        return date('Y'.$separador.'m', $this->_fecha);
    }
    
    /**
     * Obtiene la fecha en formato m-Y
     * @return date
     */
    public function getMonthYear($separador ='-') {
        return date('m'.$separador.'Y', $this->_fecha);
    }

    public function __toString() {
        if($this->_fecha){
            return date($this->_fecha);    
        }else{
            return date('now');
        }
    }

    /**
     * Time difference between two dates
     * @param String $from
     * @param String $to
     * @return Time, difference in the two DateTime objects
     */
    public function differenceAsObject($from = '', $to = '') {
        $from = $this->_date($from);
        $to = $this->_date($to);

        return $to->diff($from);
    }

    /**
     * Obtiene los años entre 2 fechas
     * @param date $desde
     * @param date $hasta
     * @return int
     */
    public static function edadHasta($desde, $hasta = '') {
        list($dia, $mes, $anio) = explode("-", $desde);
        if ($hasta == '') {
            $anio_dif = date("Y") - $anio;
            $mes_dif = date("m") - $mes;
            $dia_dif = date("d") - $dia;
        } else {
            list($diah, $mesh, $anioh) = explode("-", $hasta);
            $anio_dif = $anioh - $anio;
            $mes_dif = $mesh - $mes;
            $dia_dif = $diah - $dia;
        }
//        echo $dia_dif . '-' . $mes_dif;
        if ($dia_dif < 0 && $mes_dif <= 0) {
            $anio_dif--;
        }
        return $anio_dif;
    }

    public static function edad($edad) {
        list($anio, $mes, $dia) = explode("-", $edad);
        $anio_dif = date("Y") - $anio;
        $mes_dif = date("m") - $mes;
        $dia_dif = date("d") - $dia;
        if ($dia_dif < 0 || $mes_dif < 0)
            $anio_dif--;
        return $anio_dif;
    }

    public static function getFechaBd($fecha) {
        if (stripos($fecha, '/') > 0) {
            $myFecha = implode('/', array_reverse(explode('/', $fecha)));
        } else {
            $myFecha = implode('-', array_reverse(explode('-', $fecha)));
        }
        return $myFecha;
    }

    public static function getFechaAr($fecha) {
        if (stripos($fecha, '/') > 0) {
            $myFecha = implode('/', array_reverse(explode('/', $fecha)));
        } else {
            $myFecha = implode('-', array_reverse(explode('-', $fecha)));
        }
        return $myFecha;
    }

    /**
     * Obtiene el día de la fecha creada
     * @return int
     */
    public function getDia() {
        return date('d', $this->_fecha);
    }

    /**
     * Obtiene el mes de la fecha creada
     * @return int
     */
    public function getMes() {
        return date('m', $this->_fecha);
    }

    /**
     * Obtiene el año del la fecha creada
     * @return int
     */
    public function getAnio() {
        return date('Y', $this->_fecha);
    }

    /**
     * Obtiene la posicion del día en la semana
     * 1 (para lunes) hasta 7 (para domingo)
     * @return int
     */
    public function getPosicionDia() {
        return date('N', $this->_fecha);
    }

    /**
     * Obtiene el día de la semana
     * @return string
     */
    public function getDiaSemana() {
        return $this->_diasSemana[date('N', $this->_fecha)];
    }

    public function getMesAnio() {
        return $this->_mesAnio[date('m', $this->_fecha)];
    }

    /**
     * Obtiene la semana del mes
     * @return int
     */
    public function getSemanaDelMes() {
        return ceil(date('j', $this->_fecha) / 7);
    }

    public function getSemanaDelAnio() {
        return date('W', $this->_fecha);
    }

    public static function fechaLocal() {
        if (isset($_GET['getV'])) {
            $getV = $_GET['getV'];
            return $getV;
        } else {
            echo '<script type="text/javascript">' . "\n" . ' 
            var MyFecha = new Date() 
            var mes = MyFecha.getMonth() + 1 
            var dia = MyFecha.getDate() 
            var anyo = MyFecha.getFullYear() 
            getTo_php = dia + "/" + mes + "/" + anyo;';
            echo "location.href=\"${_SERVER['SCRIPT_NAME']}?${_SERVER['QUERY_STRING']}"
            . "&getV=\" + getTo_php;\n";
            echo '</script>';
        }
    }

    /**
     * Get the time difference as words
     * @param String $from
     * @param String $to
     * @param String $prefix, to add as a prefix
     * @param String $suffix, to add as a suffix
     * @return the time difference as string
     */
    public function differenceAsWords($from = '', $to = '', $prefix = 'about', $suffix = 'ago') {
        $words = array();
        $difference = '';
        $diff_in_seconds = 0;

        if ($from) {
            $diffs = $this->differenceAsObject($from, $to);

            foreach ($diffs as $index => $value) {
                $diff_in_seconds += $value * intval($this->_keys[$index][1]);
            }

            if ($diff_in_seconds < 60) { //less than a minute
                $difference = 'less than a minute ' . $suffix;
            } elseif ($diff_in_seconds > 1500 & $diff_in_seconds < 2100) { //25 - 35 minutes
                $difference = $prefix . ' half an hour ' . $suffix;
            } elseif ($diff_in_seconds > 3300 & $diff_in_seconds < 3900) { //55 - 65 minutes
                $difference = $prefix . ' an hour ' . $suffix;
            } else {
                foreach ($this->differenceAsObject($from, $to) as $index => $value) {
                    $words[] = $this->_stringify($this->_keys[$index][0], $value);
                }

                $difference = trim($prefix . ' ' . implode($words, ' ')) . ' ' . $suffix;
            }
        }

        return trim($difference);
    }

    /**
     * Next Repeat date
     *
     * @param String $date
     * @param int $interval
     * @param string $key, repetition type
     * @return Repeated date before/after ($day) day
     */
    public function nextRepeatDate($date = '', $interval = 1, $key = 'm') {
        $date = $date ? $date : date('Y-m-d h:i:s');
        $key = $this->_keys[$key][0] . 's';
        return date('Y-m-d h:i:s', strtotime("$date $interval $key"));
    }

    /**
     * Repeat date by week
     * For backward compatibility, will be remove from next year
     *
     * @param String $date
     * @param int $week
     * @return Repeated date after/before ($week) week
     */
    function repeatDateByWeek($date, $week = 1) {
        $this->nextRepeatDate($date, $week, 'w');
    }

    public function getTimestamp($date = '') {
        if ($date == '') {
            return parent::getTimestamp();
        }
        return strtotime($date);
    }

    /**
     * Convert time to string and pluralize
     * @param String $word, ex: hour, minute
     * @param int $value
     * @return pluralized word with string conversion
     */
    private function _stringify($word, $value = 0) {
        $str = '';
        if ($word) {
            if ($value == 1) {
                $str = $value . ' ' . $word;
            } elseif ($value > 1) {
                $str = $value . ' ' . $word . 's';
            }
        }

        return $str;
    }

    /**
     * Permite agregar días, meses y años
     * @param datetime $fecha
     * @param int $day
     * @param int $mth
     * @param int $yr
     * @return datetime
     */
    function add_date($fecha, $day = 0, $mth = 0, $yr = 0) {
        $cd = strtotime($fecha);
//        echo date('d-m-Y', $cd);
        $newdate = date('d-m-Y', mktime(date('h', $cd), date('i', $cd), date('s', $cd), date('m', $cd) + $mth, date('d', $cd) + $day, date('Y', $cd) + $yr));
        return $newdate;
    }

    function s_datediff($str_interval, $dt_menor, $dt_maior, $relative = false) {
        if (is_string($dt_menor))
            $dt_menor = date_create($dt_menor);
        if (is_string($dt_maior))
            $dt_maior = date_create($dt_maior);

        $diff = date_diff($dt_menor, $dt_maior, !$relative);

        switch ($str_interval) {
            case "y":
                $total = $diff->y + $diff->m / 12 + $diff->d / 365.25;
                break;
            case "m":
                $total = $diff->y * 12 + $diff->m + $diff->d / 30 + $diff->h / 24;
                break;
            case "d":
                $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h / 24 + $diff->i / 60;
                break;
            case "h":
                $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i / 60;
                break;
            case "i":
                $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s / 60;
                break;
            case "s":
                $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i) * 60 + $diff->s;
                break;
        }
        if ($diff->invert)
            return -1 * $total;
        else
            return $total;
    }

}
