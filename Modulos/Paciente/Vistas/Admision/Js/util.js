$(function() {
        $( "#tabs" ).tabs();
    });
$(function() {
        $( "#fechaNac" ).datepicker(
    {
        changeYear: true
    }
);
    });   
$(function() {
        $("#fechaIngreso").datepicker();
    }); 
    
$(function() {
        $("#fecha").datepicker();
    });
        
$(function() {
    $("#guardar_paciente").click(function () {
        var carnet_discapacidad = $("#carnet_discapacidad").attr("value");
        var idPaciente = $("#idPaciente").attr("value");
        var terapia = $("#terapia").attr("value");
        var terapia_txt = (jQuery("#terapia").find("option[value='" + jQuery("#terapia").val() + "']").text());
        var cantidad = $("#cantidad").attr("value");
        var observaciones = $("#observacionesTerapia").attr("value");
        $.post("http://www.pequehogar.com.ar/Cronos/index.php?option=Paciente&sub=terapia&met=nuevo",
          {guardar: guardar, idPaciente: idPaciente, terapia: terapia, cantidad: cantidad, observaciones: observaciones},
          function(data) {
            alert("Data Loaded: " + data);
            $("#contenedorDetalleDatosTerapia").append('<div class="datoContacto" id="' + data + '">' +
            '<div class="datoDetalle">' + terapia_txt + '</div>' +
            '<div class="datoDetalle">' + cantidad + '</div>' +
            '<div class="datoDetalle">' + observaciones + '</div>' +
            '<div class="editcontrol">' +
            '<a class="iconoX" href="JavaScript:void(0);" id="' + data + '" idPaciente="' + idPaciente + '" title="Eliminar"></a>' +
           '</div>' +
           '</div>');
          });        
    });
})
