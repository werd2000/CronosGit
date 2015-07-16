<div id="turnosdiaAM" class="container-fluid">
    <div id="col_horario">
        <?php
        foreach ($this->horario as $celda) {
            echo $celda->render();
        }
        ?>
    </div>
    <?php
    foreach ($this->profesionalesDiaAM as $turno) {
        $class = '';
        echo '<div id="col_horarioProfesional" idEncProfesional="' . $turno->getPersonal()->getId() .
        '" turno="AM" class=' . $class . '>';

        foreach ($this->horarioProfesionalAM[$turno->getPersonal()->getId()] as $horario) {
            if (is_a($horario, 'Personal')) {
                echo '<div id="' . $turno->getId() . '" idProfesional="' . $horario->getId() . '" ' .
                'turno="AM" class="cel_horario turno_profesional' . $class . '">' .
                substr($horario,0,13) . '</div>';
            } else {
                $class = $this->estadosTurnos[$horario->getEstadoTurno()];
                $class_observaciones = '';
                $title = '';
                if ($horario->getObservaciones() == '') {
                    $class_observaciones = '';
                } else {
                    $class_observaciones = ' observaciones_turno';
                    $title = ' title="' . ltrim($horario->getObservaciones()) . '"';
                }
                $idPaciente = 0;
                $ayn = '';
                if (is_a($horario->getPaciente(), 'Paciente_Modelos_Paciente')) {
                    $idPaciente = $horario->getPaciente()->getId();
                    $ayn = $horario->getPaciente()->getAyN();
                }
                echo '<div id="' . $horario->getId() . '" idPaciente="' .
                $idPaciente . '" turno="AM" ' .
                'class="turno_paciente ' . $class .
                $class_observaciones . '" ' . $title . ' data-toggle="tooltip">' .
                $ayn . '</div>';
            }
        }
        echo '</div>';
    }
    ?>
</div>
<div id="personal-toolbar-options">
    <a href="?option=Turnos&sub=index&met=personalAusente"><i class="icon-personal_ausente_14"></i></a>
</div>
<div id="paciente-toolbar-options">
    <a href="?option=Turnos&sub=index&met=personal_ausente"><i class="icon-personal_ausente_14"></i></a>
    <a href="?option=Turnos&sub=index&met=personal_tarde"><i class="icon-personal_tarde_14"></i></a>
    <a href="?option=Turnos&sub=index&met=paciente_ausente"><i class="icon-paciente_ausente_14"></i></a>
    <a href="?option=Turnos&sub=index&met=paciente_tarde"><i class="icon-paciente_tarde_14"></i></a>
    <a href="?option=Turnos&sub=index&met=reunion_equipo"><i class="icon-reunion_equipos24"></i></a>
    <a href="?option=Turnos&sub=index&met=devolucion"><i class="icon-devolucion24"></i></a>
    <a href="observaciones_turno"><i class="icon-pencil_24"></i></a>
</div>
<div id="dialog-form" title="Observaciones">
    <form>
        <textarea name="observaciones" id="observaciones" rows="8" cols="42" class="text ui-widget-content ui-corner-all">
            "<?php echo $this->titulo; ?>"</textarea>
    </form>
</div>