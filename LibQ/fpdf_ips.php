<?php
require_once 'fpdf.php';
class FPDF_ips extends FPDF
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Header()
    {
        $this->image(IMAGEN_PUBLICA . 'reportes/ips_misiones.jpg', 10, 10, 190);
        $this->SetFont('Helvetica', 'B', 10);
        $this->Cell(0, 65, 'Junin 1789  -  www.ips_misiones.gov.ar  -  3300  Posadas  Misiones', 0, 1, 'C');
        
        return;
    }

}