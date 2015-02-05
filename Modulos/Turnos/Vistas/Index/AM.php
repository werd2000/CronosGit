<div id="turnosdiaAM" class="container-fluid">
    <div id="col_horario">
        <?php
        foreach ($this->horario as $celda) {
            echo $celda->render();
        }
        ?>
    </div>
    <?php
//    $class = '';
    foreach ($this->profesionalesDiaAM as $turno) {
        $class = '';
        echo '<div id="col_horarioProfesional" idEncProfesional="' . $turno->getPersonal()->getId() .
        '" turno="AM" class=' . $class . '>';

        foreach ($this->horarioProfesionalAM[$turno->getPersonal()->getId()] as $horario) {
            if (is_a($horario, 'Personal')) {
                echo '<div id="' . $turno->getId(). '" idProfesional="' . $horario->getId() . '" ' .
                     'turno="AM" class="cel_horario turno_profesional' . $class . '">'.
                $horario . '</div>'; 
            } else {
                $class = $this->estadosTurnos[$horario->getEstadoTurno()];
                $class_observaciones = '';
                $title = '';
                if ($horario->getObservaciones() == '') {
                    $class_observaciones = '';
                } else {
                    $class_observaciones = ' observaciones_turno';
                    $title = 'titulo="' . $horario->getObservaciones() . '"';
                }
                $idPaciente = 0;
                $ayn = '';
                if(is_a($horario->getPaciente(),'Paciente_Modelos_Paciente')){
                    $idPaciente = $horario->getPaciente()->getId();
                    $ayn = $horario->getPaciente()->getAyN();
                }
                echo '<div id="' . $horario->getId() . '" idPaciente="' .
                        $idPaciente . '" turno="AM" ' .
                        'class="turno_paciente ' . $class . 
                        $class_observaciones . '" ' . $title . '>' .
                        $ayn . '</div>';
            }
        } 
        echo '</div>';
    }?>
</div>
        