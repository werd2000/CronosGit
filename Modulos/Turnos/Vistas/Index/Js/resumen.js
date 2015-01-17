$(function() {
    $( "#tablaResumen" ).accordion({
      collapsible: true
    });
  });
  
$(function() {
    $("#pacienteResumen").change(function() {
        var idPac = $("#pacienteResumen").val();
        document.location.href = "?option=Turnos&sub=index&cont=resumenPaciente&paciente=" +
                idPac + '&mes=actual';
    });
});