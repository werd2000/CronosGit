$(function() {
    $("#tabs").tabs();
});
$(function() {
    $("#fechaNac").datepicker({
        changeYear: true
    }
    );
    $("#fechaNac").datepicker("option", "dateFormat", "dd-mm-yy");
});
$(function() {
    var fecha = $("#fechaNac").attr("value");
    $("#fechaIngreso").datepicker();
    $("#fechaNac").datepicker("setDate", fecha);
});

$(function() {
    $("#lista_terapia").click(function() {
        var idTerapia = $("#lista_terapia").val();
        $.post("?option=Paciente&sub=index&met=getProfesionalesTerapia", {
        idTerapia: idTerapia
    },
                function(data) {
                    var $combo = $("#profesional");
                    $combo.empty();
                    $combo.append(data);
                });
    })
});


$(function() {
    $("#agregarTerapiaPaciente").click(function() {
        var guardar = $("#guardarTerapiaPaciente").attr("value");
        var idPaciente = $("#idPaciente").attr("value");
        var terapia = $("#lista_terapia").val();
        var idProfesional = $("#profesional").val();
        var nombreProfesional = (jQuery("#profesional").find("option[value='" + jQuery("#profesional").val() + "']").text());
        var terapia_txt = (jQuery("#terapia").find("option[value='" + jQuery("#terapia").val() + "']").text());
        var cantidad = $("#cantidad").val();
        var observaciones = $("#observacionesTerapia").attr("value");
        $.post("?option=Paciente&sub=terapia&met=nuevo",
                {guardar: guardar, idPaciente: idPaciente, terapia: terapia, idProfesional: idProfesional,
                    cantidad: cantidad, observaciones: observaciones},
        function(data) {
            if (data > 0){
            alert("Datos Guardados");
            document.location.reload();
            $("#contenedorDetalleDatosTerapia").append('<tr class="detalleDato" id="' + data + '">' +
                    '<td class="datoDetalle4c">' + terapia_txt + '</td>' +
                    '<td class="datoDetalle4c">' + nombreProfesional + '</td>' +
                    '<td class="datoDetalle4c">' + cantidad + '</td>' +
                    '<td class="datoDetalle4c">'+
                        '<img src="Public/Img/pdf_file_16.png">'+
                    '</td>' +
                    '<td class="datoDetalle4c">' + observaciones + '</td>' +
                    '<td class="editcontrol">' +
                    '<a href="JavaScript:void(0);" idterapia="' + data + 
                        '" idPaciente="' + idPaciente +
                        '" contexto="terapia_paciente" title="eliminar">' +
                        '<span class="glyphicon glyphicon-remove"></span>'+
                    '</a>' +
                    '</td>' +
                    '</tr>');
        }else{
            alert("Los datos NO se guardaron");
        }
        });
    });
})

$(function() {
    $("#agregarContactoPaciente").click(function() {
        var guardar = $("#guardarDatosContacto").attr("value");
        var idPaciente = $("#idPaciente").attr("value");
        var tipo = $("#tipoContacto").val();
        var contacto = $("#valorContacto").val();
        var observaciones = $("#observacionesContactoPaciente").val();
        $.post("?option=Paciente&sub=contacto&met=nuevo",
                {guardar: guardar, idPaciente: idPaciente, tipo: tipo, contacto: contacto, observaciones: observaciones},
        function(data) {
            alert("Data Loaded: " + data);
            $("#contenedorDetalleDatos").append('<div class="datoContacto" id="' + data + '">' +
                    '<div class="datoDetalle">' + $("#tipoContacto").val() + '</div>' +
                    '<div class="datoDetalle">' + $("#valorContacto").val() + '</div>' +
                    '<div class="datoDetalle">' + $("#observacionesContactoPaciente").val() + '</div>' +
                    '<div class="editcontrol">' +
                    '<a class="iconoX" href="JavaScript:void(0);" idContacto="' + data + '" idPaciente="' + idPaciente + '" title="Eliminar"></a>' +
                    '</div>' +
                    '</div>');
        });
    });
})

$(function() {
    $("#agregarFamiliaPaciente").click(function() {
        var guardar = $("#guardarDatosContacto").val();
        var idPaciente = $("#idPaciente").val();
        var parentesco = $("#parentesco").val();
        var nombre = $("#nombreFamilia").val();
        var observaciones = $("#observacionesFamilia").val();
        $.post("?option=Paciente&sub=familia&met=nuevo",
                {guardar: guardar, idPaciente: idPaciente, parentesco: parentesco, nombre: nombre, observaciones: observaciones},
        function(data) {
            //            alert("Data Loaded: " + data);
            $("#contenedorDetalleDatosFamilia").append('<div class="datoContacto" id="' + data + '">' +
                    '<div class="datoDetalle">' + parentesco + '</div>' +
                    '<div class="datoDetalle">' + nombre + '</div>' +
                    '<div class="datoDetalle">' + observaciones + '</div>' +
                    '<div class="editcontrol">' +
                    '<a class="iconoX" href="JavaScript:void(0);" idContacto="' + data + '" idPaciente="' + idPaciente + '" title="Eliminar"></a>' +
                    '</div>' +
                    '</div>');
        });
    });
})

$(function() {
    $("#guardarObraSocialPaciente").click(function() {
        var guardar = $("#guardarOSPaciente").val();
        var id = $("#id").val();
        var idPaciente = $("#idPaciente").val();
        var idObraSocial = $("#idObraSocial").val();
        var nroAfiliado = $("#nroAfiliado").val();
        var observaciones = $("#observacionesOs").val();
        $.post("?option=Paciente&sub=Osocial&met=guardar",
                {guardar: guardar, id: id, idPaciente: idPaciente, idObraSocial: idObraSocial, nroAfiliado: nroAfiliado, observaciones: observaciones},
        function(data) {
            alert("Data Loaded: " + data);
        });
    });
})

$(function() {
    $("#guardarEducacionPaciente").click(function() {
        var guardar = $("#guardarEducPaciente").val();
        var id = $("#idEduc").val();
        var idPaciente = $("#idPaciente").val();
        var idEscuela = $("#idEscuela").val();
        var curso = $("#curso").val();
        var observaciones = $("#observacionesEducacion").val();
        $.post("?option=Paciente&sub=Educacion&met=guardar",
                {guardar: guardar, id: id, idPaciente: idPaciente, idEscuela: idEscuela, curso: curso, observaciones: observaciones},
        function(data) {
            alert("Data Loaded: " + data);
        });
    });
})

$(function() {
    $(".glyphicon-remove").click(function() {
        var v = $(this).parent('a').attr("idPaciente");
        var c = $(this).parent('a').attr("idContacto");
        var contexto = '';
        if (c > 0) {
            contexto = 'contacto'
        } else {
            c = $(this).parent('a').attr("idTerapia");
            if (c > 0) {
                contexto = 'terapia'
            } else {
                c = $(this).parent('a').attr("idFamilia");
                contexto = 'familia';
            }
        };
        $.get("?option=Paciente&sub=" + contexto + "&met=eliminar&id=" + c,
                function(data) {
                    if (data > 0) {
                        $("#" + c).fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);
                    }
                }
        );
    });
})

$(function() {
    $("#eliminarTerapiaPaciente").click(function() {
        var v = $(this).attr("idPaciente");
        var c = $(this).attr("idTerapia");
        if (c > 0)
            contexto = terapia;
        $.get("http://www.pequehogar.com.ar/Cronos/?option=Paciente&sub=terapia&met=eliminar&id=" + c,
                function(data) {
                    if (data > 0) {
                        $("#" + c).fadeOut(800).fadeIn(800).fadeOut(500).fadeIn(500).fadeOut(300);
                    }
                }
        );
    });
})

$(function() {
    $(".icono-buscar32").click(function() {
        $("#dialog-form-buscar").fadeToggle();
    });
})


$(function() {
    $(".upload").click(function() {
        $("#dialog-upload").dialog("open");
    });

    function checkLength(o, n, min, max) {
        //      if ( o.val().length > max || o.val().length < min ) {
        //        o.addClass( "ui-state-error" );
        //        updateTips( "Length of " + n + " must be between " +
        //          min + " and " + max + "." );
        //        return false;
        //      } else {
        return true;
        //      }
    }

    $("#dialog-upload").dialog({
        autoOpen: false,
        height: 200,
        width: 450,
        modal: true,
        buttons: {
            "Subir": function() {
                var bValid = true;
                //          allFields.removeClass( "ui-state-error" );

                bValid = bValid && checkLength(archivo, "archivo", 3, 16);

                if (bValid) {
                    $("#nuevo_archivo").submit();
                    $(this).dialog("close");
                }
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {
            //        allFields.val( "" ).removeClass( "ui-state-error" );
        }
    });
})

