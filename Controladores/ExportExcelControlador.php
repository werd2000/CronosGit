<?php

require_once BASE_PATH . 'LibQ/PHPExcel.php';

class ExportExcelControlador extends Controlador
{

    private $_excel;
    private $_cols = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

    public function __construct()
    {
        parent::__construct();
        parent::getLibreria('PHPExcel');
        $this->_excel = new PHPExcel();
    }

    public function index()
    {
        ;
    }

    public function pacientes()
    {
        // Set properties
        $this->_excel->getProperties()->setCreator(APP_AUTOR);
        $this->_excel->getProperties()->setLastModifiedBy(APP_AUTOR);
        $this->_excel->getProperties()->setTitle("Listado de Pacientes");
        $this->_excel->getProperties()->setSubject("Office 2007 XLSX Listado de Pacientes");
        $this->_excel->getProperties()->setDescription("Listado de Pacientes for Office 2007 XLSX, generated using PHP classes.");

        //Cargar datos
        $modeloPaciente = $this->cargarModelo('index', 'paciente');
        $pacientes = $modeloPaciente->getPacientesArray();
        // Add some data
        $this->_excel->setActiveSheetIndex(0);
        $fila = 2;
        $col  = 0;
        $encabezados = array_keys($pacientes[0]);
        foreach ($encabezados as $key=>$columna) {
            $this->_excel->getActiveSheet()->SetCellValue($this->_cols[$col].'1', $columna);
            $col++;
        }
        foreach ($pacientes as $paciente) {
            $col  = 0;
            foreach ($paciente as $key=>$dato) {
                $this->_excel->getActiveSheet()->SetCellValue($this->_cols[$col].$fila, $dato);
                $col++;
            }
            $fila++;
        }

        

        // Rename sheet
        $this->_excel->getActiveSheet()->setTitle('Pacientes');

        $this->_outputExcel2007('Pacientes');
//        
        
    }
    
    public function contactos_pacientes()
    {
        // Set properties
        $this->_excel->getProperties()->setCreator(APP_AUTOR);
        $this->_excel->getProperties()->setLastModifiedBy(APP_AUTOR);
        $this->_excel->getProperties()->setTitle("Listado de Contactos Pacientes");
        $this->_excel->getProperties()->setSubject("Office 2007 XLSX Listado de Contactos Pacientes");
        $this->_excel->getProperties()->setDescription("Listado de Contactos Pacientes for Office 2007 XLSX, generated using PHP classes.");

        //Cargar datos
        $modeloPaciente = $this->cargarModelo('contactoPaciente', 'paciente');
        $pacientes = $modeloPaciente->getListadoContactos();
//        print_r($pacientes);
        // Add some data
        $this->_excel->setActiveSheetIndex(0);
        $fila = 2;
        $col  = 0;
        $encabezados = array_keys($pacientes[0]);
        foreach ($encabezados as $key=>$columna) {
            $this->_excel->getActiveSheet()->SetCellValue($this->_cols[$col].'1', $columna);
            $col++;
        }
        foreach ($pacientes as $paciente) {
            $col  = 0;
            foreach ($paciente as $key=>$dato) {
                $this->_excel->getActiveSheet()->SetCellValue($this->_cols[$col].$fila, $dato);
                $col++;
            }
            $fila++;
        }

        

        // Rename sheet
        $this->_excel->getActiveSheet()->setTitle('Listado Contactos Pacientes');

        $this->_outputExcel2007('Listado Contactos Pacientes');
//        
        
    }
    
    public function personal()
    {
        // Set properties
        $this->_excel->getProperties()->setCreator(APP_AUTOR);
        $this->_excel->getProperties()->setLastModifiedBy(APP_AUTOR);
        $this->_excel->getProperties()->setTitle("Listado de Personal");
        $this->_excel->getProperties()->setSubject("Office 2007 XLSX Listado de Personal");
        $this->_excel->getProperties()->setDescription("Listado de Personal for Office 2007 XLSX, generated using PHP classes.");

        //Cargar datos
        $modeloPersonal = $this->cargarModelo('index', 'personal');
        $personal = $modeloPersonal->getTodoPersonal();
        // Add some data
        $this->_excel->setActiveSheetIndex(0);
        $fila = 2;
        $col  = 0;
        $encabezados = array_keys($personal[0]);
        foreach ($encabezados as $key=>$columna) {
            $this->_excel->getActiveSheet()->SetCellValue($this->_cols[$col].'1', $columna);
            $col++;
        }
        foreach ($personal as $unPersonal) {
            $col  = 0;
            foreach ($unPersonal as $key=>$dato) {
                $this->_excel->getActiveSheet()->SetCellValue($this->_cols[$col].$fila, $dato);
                $col++;
            }
            $fila++;
        }

        

        // Rename sheet
        $this->_excel->getActiveSheet()->setTitle('Personal');

        $this->_outputExcel2007('Personal');
//        
        
    }
    
    public function contactos_personal()
    {
        // Set properties
        $this->_excel->getProperties()->setCreator(APP_AUTOR);
        $this->_excel->getProperties()->setLastModifiedBy(APP_AUTOR);
        $this->_excel->getProperties()->setTitle("Listado de Contactos Personal");
        $this->_excel->getProperties()->setSubject("Office 2007 XLSX Listado de Contactos Personal");
        $this->_excel->getProperties()->setDescription("Listado de Contactos Personal for Office 2007 XLSX, generated using PHP classes.");

        //Cargar datos
        $modeloPersonal = $this->cargarModelo('contacto', 'personal');
        $pacientes = $modeloPersonal->getListadoContactos();
//        print_r($pacientes);
        // Add some data
        $this->_excel->setActiveSheetIndex(0);
        $fila = 2;
        $col  = 0;
        $encabezados = array_keys($pacientes[0]);
        foreach ($encabezados as $key=>$columna) {
            $this->_excel->getActiveSheet()->SetCellValue($this->_cols[$col].'1', $columna);
            $col++;
        }
        foreach ($pacientes as $paciente) {
            $col  = 0;
            foreach ($paciente as $key=>$dato) {
                $this->_excel->getActiveSheet()->SetCellValue($this->_cols[$col].$fila, $dato);
                $col++;
            }
            $fila++;
        }

        

        // Rename sheet
        $this->_excel->getActiveSheet()->setTitle('Listado Contactos Personal');

        $this->_outputExcel2007('Listado Contactos Personal');
//        
        
    }
    
    private function _outputExcel5($nombre)
    {
        // redirect output to client browser
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $nombre . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->_excel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    private function _outputExcel2007($nombre)
    {
        // Save Excel 2007 file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombre . '.xlsx"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->_excel, 'Excel2007');
        $objWriter->save('php://output');
    }

}
