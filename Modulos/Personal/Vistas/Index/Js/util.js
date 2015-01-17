$(function() {
    $( "#tabs" ).tabs();
  });
$(function() {
        $( "#fechaNac" ).datepicker({
        changeYear: true
        });
    $("#fechaNac").datepicker( "option", "dateFormat", "dd-mm-yy" );
    });   
$(function() {
        var fecha = $("#fechaNac").attr("value");
        $( "#fechaIngreso" ).datepicker();
        $("#fechaNac").datepicker( "setDate", fecha );
    }); 
$(function() {
    $("#agregarDatosLaborales").click(function () {
        var guardar = $("#guardarDatosLaborales").attr("value");
        var id = $("#id").attr("value");
        var idProfesional = $("#idProfesional").attr("value");
        var fechaIngreso = $("#fechaIngreso").val();
        var ocupacionEmpresa = $("#valorOcupacionEmpresa").val();
        var observaciones = $("#observacionesDatosLaborales").val();
        $.post("?option=Personal&sub=laboral&met=nuevo",
          {guardar: guardar, id: id, idProfesional: idProfesional, fechaIngreso: fechaIngreso, puesto: ocupacionEmpresa, observaciones: observaciones},
          function(data) {
            alert("Data Loaded: " + data);
            document.location.reload();
          });        
    });
})

$(function() {
    $("#agregarContactoProfesional").click(function () {
        var guardar = $("#guardarDatosContacto").attr("value");
        var idProfesional = $("#idPersonal").attr("value");
        var tipo = $("#tipoContacto").val();
        var contacto = $("#valorContacto").val();
        var observaciones = $("#observacionesContactoProfesional").val();
        $.post("http://www.pequehogar.com.ar/Cronos/index.php?option=Personal&sub=contacto&met=nuevo",
          {guardar: guardar, idProfesional: idProfesional, tipo: tipo, contacto: contacto, observaciones: observaciones},
          function(data) {
            alert("Data Loaded: " + data);
            $("#contenedorDetalleDatos").append('<div class="datoContacto" id="' + data + '">' +
            '<div class="datoDetalle">' + $("#tipoContacto").val() + '</div>' +
            '<div class="datoDetalle">' + $("#valorContacto").val() + '</div>' +
            '<div class="datoDetalle">' + $("#observacionesContactoProfesional").val() + '</div>' +
            '<div class="editcontrol">' +
            '<a class="iconoX" href="JavaScript:void(0);" idContacto="' + data + '" idProfesional="' + idProfesional + '" title="Eliminar"></a>' +
           '</div>' +
           '</div>');
          });        
    });
})

$(function() {
    $(".iconoX").click(function () {
       var v = $(this).attr("idprofesional");
       var c = $(this).attr("idContacto");
       $.get("http://www.pequehogar.com.ar/Cronos/?option=Personal&sub=contacto&met=eliminar&id="+c,
       function(data){
            if (data > 0){
                $("#"+c).fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);
            }
       }
    );
    });
})


$(function() {
    $("#foto").change(function () {
        ajaxFileUpload;
    });
})



function ajaxFileUpload()
	{
		//starting setting some animation when the ajax starts and completes
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});
		
		/*
			prepareing ajax file upload
			url: the url of script file handling the uploaded files
                        fileElementId: the file type of input element id and it will be the index of  $_FILES Array()
			dataType: it support json, xml
			secureuri:use secure protocol
			success: call back function when the ajax complete
			error: callback function when the ajax failed
			
                */
		$.ajaxFileUpload
		(
			{
				url:'doajaxfileupload.php', 
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							alert(data.msg);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;

	}  