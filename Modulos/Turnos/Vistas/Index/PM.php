<div id="turnosdiaPM">
    <div id="col_horario">
        <?php
        foreach ($this->horarioPM as $celda) {
            echo $celda->render();
        }
        ?>
    </div>
    <?php foreach ($this->profesionalesDiaPM as $turno) { ?>
        <div id="col_horarioProfesional" idEncProfesional=" <?php echo $turno->getPersonal()->getId(); ?>"
             turno="PM" class="" <?php echo $class; ?> >
                 <?php
                 foreach ($this->horarioProfesionalPM[$turno->getPersonal()->getId()] as $horario) {
                     if (is_a($horario, 'Personal')) {
                         ?>
                    <div id="<?php echo $turno->getId(); ?>" idProfesional="<?php echo $horario->getId(); ?>"
                         turno="PM" class="cel_horario turno_profesional"<?php echo $class; ?> >
                    <?php echo $horario; ?>
                    </div>
                <?php
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
                    ?>
                    <div id="<?php echo $horario->getId(); ?>" idPaciente="<?php echo $horario->getPaciente()->getId(); ?>"
                         turno="PM" class="turno_paciente <?php
                         echo $class . $class_observaciones .
                         '" ' . $title;
                         ?> >
                    <?php echo $horario->getPaciente()->getAyN(); ?>
                    </div>
            <?php } ?>
        <?php } ?>
        </div>
        <?php
    }
    echo $this->msg_dlg;
    ?>
</div>
</div>