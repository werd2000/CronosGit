$(function() {
    $( document ).tooltip({
        items:"[titulo]",
        content: function() {
            var element = $( this );
            if ( element.is( "[titulo]" ) ) {
                return element.attr( "titulo" );
            }
        },
      position: {
        my: "center bottom-20",
        at: "center top",
        using: function( position, feedback ) {
          $( this ).css( position );
          $( "<div>" )
            .addClass( "arrow" )
            .addClass( feedback.vertical )
            .addClass( feedback.horizontal )
            .appendTo( this );
        }
      }
    });
  });
  
$(function() {
    $("#tabs").tabs();
});


$(function() {
    $("#fechaTurno").datepicker({
        changeYear: true
    })
    $("#fechaTurno").datepicker("option", "dateFormat", "dd-mm-yy");
});
$(function() {
    var fecha = $("#fechaTurno").attr("value");
    $("#fechaTurno").datepicker("setDate", fecha);
});

$(function() {
    $(".turno_paciente").bind("click", function(event) {
        var idPaciente = $(this).attr('idPaciente');
        var idTurno = $(this).attr('id');
        var idProfesional = $(this).parents("div").attr('idencprofesional');
        mostrarListaPacientes(event, idProfesional, idPaciente);
        $("#paciente").attr('idPaciente', idPaciente);
        $("#paciente").attr('rem', idTurno);
        seleccionarPaciente(idPaciente);
    })

    $(".turno_paciente").bind("dblclick", function() {
        alert('dbl');
    });
});

$(function() {
    $(".turno_profesional").click(function() {
        var idProfesional = $(this).attr('idprofesional'); //obtenerId($(this));
        mostrarListaProfesionales(event);
        $("#profesional").attr('idProfesional', idProfesional);
        $("#personal-toolbar-options a").attr('idProfesional', idProfesional);
        $("#profesional").attr('rem', idProfesional);
        $("#profesional").attr('turno', $(this).parent().attr('turno'));
        seleccionarProfesional(idProfesional);
    })
});
$(function() {
    $(".turno_profesional").toolbar({
        content: '#personal-toolbar-options',
        position: 'top',
        hideOnClick: 'true'});
    $('.turno_profesional').on('toolbarItemClick',
            function(event, selector) {
                var turno = 'PM';
                var fecha = $("#fechaTurno").attr("value");
                if ($("#tabs").find("#tabs-1").attr("aria-expanded")==true) {
                    turno = "AM";
                    var idprofesional = $("#turnosdiaAM").find(".pressed").attr("idprofesional");
                }else{
                    var idprofesional = $("#turnosdiaPM").find(".pressed").attr("idprofesional");
                }
                $.post(selector, {idProfesional: idprofesional, turno: turno, fecha: fecha},
                function(data) {
                    alert(data);
                    window.location.reload();
                })
            })
});

$(function() {
    $(".turno_paciente").toolbar({
        content: '#paciente-toolbar-options',
        position: 'top',
        hideOnClick: 'true'});
    $('.turno_paciente').on('toolbarItemClick',
            function(event, selector) {
                var id = $("#turnosdiaAM").find(".pressed").attr("id");
                if (typeof id == 'undefined'){
                    var id = $("#turnosdiaPM").find(".pressed").attr("id");
                    var observaciones = $("#turnosdiaPM").find(".pressed").attr("titulo");
                }
                if (selector.toString().substr(36) === 'observaciones_turno') {
                    $("#observaciones").val(observaciones);
                    $("#dialog-form").attr('idTurno',id);
                    $("#dialog-form").dialog("open");
                } else {
                    $.post(selector, {id: id},
                    function(data) {
                        alert(data);
                        window.location.reload();
                    })
                }
            })
});

function seleccionarProfesional(elemento) {
    var combo = document.getElementById('profesional');
    var cantidad = combo.length;
    for (i = 0; i < cantidad; i++) {
        if (combo[i].value == elemento) {
            combo[i].selected = true;
        }
    }
}

function seleccionarPaciente(elemento) {
    var combo = document.getElementById('paciente');
    var cantidad = combo.length;
    for (i = 0; i < cantidad; i++) {
        if (combo[i].value == elemento) {
            combo[i].selected = true;
        }
    }
}

function mostrarListaProfesionales(event) {
    $("#turnosProfesional").dialog({
        autoOpen: true,
        show: {
            effect: "blind",
            duration: 500
        },
        hide: {
            effect: "explode",
            duration: 200
        },
        position: {my: "left+3 top-10", collision: "fit", of: event}
    });
}

function mostrarListaPacientes(event, idProfesional, idPaciente) {
    $.post("?option=Turnos&sub=index&met=getPacientesProfesional", {
        idProfesional: idProfesional, idPaciente: idPaciente
    },
                function(data) {
                    var $combo = $("#paciente");
                    $combo.empty();
                    $combo.append(data);
                    $('.chosen-container').css('width','250px');
                });
    $("#turnosPaciente").dialog({
        autoOpen: true,
        show: {
            effect: "blind",
            duration: 500
        },
        hide: {
            effect: "explode",
            duration: 200
        },
        position: {my: "left+3 top-10", collision: "fit", of: event}
    });
}

function obtenerId(algo) {
    var id = algo.attr("idpaciente");
    if (!id) {
        id = algo.attr("idprofesional");
    }
    return id;
}

function dondeClick(algo) {
    var donde = 'paciente';
    var id = algo.attr("idpaciente");
    if (!id) {
        id = algo.attr("idprofesional");
        donde = 'profesional';
        if (!id) {
            donde = 'nada';
        }
    }
    return donde;
}


$(function() {
    $("#profesional").change(function() {
        var idProf = $("#profesional").val();
        var turno = $("#profesional").attr("turno");
        var idDiv = $("#profesional").attr("rem");
        var nombreProf = $("#profesional option:selected").text();
        var fecha = $("#fechaTurno").val();
        $("#turnosProfesional").dialog("close");
        $("div[idProfesional=" + idDiv + "]").text(nombreProf);

        $("div[idProfesional$=" + idDiv + "]").attr("idprofesional", idProf);
        $("div[idprofesional$=" + idProf + "]").attr("name", idProf);
        //guardar el cambio
        $.post("?option=Turnos&sub=index&met=guardarTurnoProfesional",
                {idProfesional: idProf, idDiv: idDiv, turno: turno, fecha: fecha},
        function(data) {
            $("#dialog-message").attr("title", 'Atención');
            $("#msg").text(data);
            mostrarDialogo();
        });
    });
});

$(function() {
    $("#paciente").change(function() {
        var idNewPac = $("#paciente").val();
        var idDiv = $("#paciente").attr("rem");
        var nombrePac = $("#paciente option:selected").text();
        $("#turnosPaciente").dialog("close");
//        document.getElementById('turnosPaciente').style.display = 'none';
        $("#" + idDiv).text(nombrePac);
        $("#" + idDiv).attr("idpaciente", idNewPac);
        $.post("?option=Turnos&sub=index&met=guardarTurnoPaciente",
                {idPaciente: idNewPac, idDiv: idDiv},
        function(data) {
            recorrerDivs();
            $("#dialog-message").attr("title", 'Atención');
            $("#msg").text(data);
            mostrarDialogo();
        });
    });
});

$(function() {
    $("#fechaTurno").change(function() {
        var fecha = $("#fechaTurno").val();
        document.location.href = "?option=Turnos&sub=index&met=index&fecha=" + fecha;
    });
});

//$(function() {
//    $("select").change(function() {
//        var idTerapiaProfesional = '';
//        var terapiasPaciente = '';
//        var idPaciente = 0;
//        var idProfesional = 0;
//        var select = $(this);
//        var salon = $(this).attr("salon");//obtengo el valor del salon
//        var hora = $(this).attr("hora");//obtengo el valor de la hora
//        var valor = $(this).val();//obtengo el value del select
//        var fecha = $("#fechaTurno").val();//obtengo la fecha
//
//        if (hora == "hora") {  //Es la primer fila
//            idProfesional = valor;
//            idTerapiaProfesional = obtenerTerapiaProfesional(valor);//busco la terapia del profesional seleccionado
//            $("#idProfesional" + salon).val(valor);//pongo el valor en el campo hiden
//            $("#idProfesional" + salon).attr("idterapiaprofesional", idTerapiaProfesional);
//        } else {
//            idPaciente = valor;
//            //busco las terapias del paciente
//            terapiasPaciente = obtenerTerapiaPaciente(valor).split(",");
//            idProfesional = $("#idProfesional" + salon).val();
//            idTerapiaProfesional = $("#idProfesional" + salon).attr('idTerapiaProfesional');
//        }
//        //Controlo que el paciente tenga esa terapia
//        if (idPaciente > 0 && idProfesional > 0) {
//            $.get("?option=Turnos&sub=index&met=guardar&idPaciente=" +
//                    idPaciente + "&idProfesional=" + idProfesional + "&hora=" + hora +
//                    "&fecha=" + fecha + "&salon=" + salon,
//                    function(data) {
//                        alert(data);
//                    }
//            );
//        }
//
//        if (hora == "hora" && profesional_reptido(salon)) {
//            alert("Este profesional está ocupado en otro salón");
//            $(this).val(0);     //Quito el profesional y queda Seleccione
//        }
//        if (hora != "hora" && paciente_reptido(valor, hora) > 1) {  //Son filas de los pacientes
//            alert("Este paciente está ocupado con otra terapia");
//            $(this).val(0);     //Quito el paciente y queda Seleccione
//        }
//        if (hora != "hora" && cantidadMaximaPaciente(valor) > 6) {  //Son filas de los pacientes
//            alert("Este paciente ya tiene el máximo por este día");
//            $(this).val(0);     //Quito el paciente y queda Seleccione
//        }
//    });
//});


function profesional_reptido(salon)
{
    var j = 1;
    for (var i = 1; i <= 5; i++) {
        if (($("#idProfesional" + salon).attr("value") == $("#idProfesional" + i).attr("value")) && (salon != i)) {
            return true;
        }
    }
    return false;
}

function paciente_reptido(idPaciente, hora)
{
    var cant = 0;
    $.each($("select"), function(key) {
        if ($(this).val() == idPaciente && $(this).attr("hora") == hora) {
            cant++;
        }
    });
    return cant;
}

function cantidadMaximaPacienteTerapia(idPaciente, idTerapia, salon)
{
    var sesiones = 0;
    $.get("?option=Turnos&sub=index&met=getCantidadSesionesPorSemana&idPaciente=" + idPaciente + "&idTerapia=" + idTerapia,
            function(data) {
                alert(data);
                sesiones = data;
            }
    );

    var cant = 0;
    $.each($("select"), function(key) {
        if ($(this).val() == idPaciente && $(this).attr("salon") == salon) {
            cant++;
        }
    });
    if (cant > sesiones) {
        return true;
    } else {
        return false;
    }
}

function cantidadMaximaPaciente(idPaciente)
{
    var cant = 0;
    $.each($("select"), function(key) {
        if ($(this).val() == idPaciente) {
            cant++;
        }
    });
    return cant;
}

//function obtenerTerapiaProfesional(valor)
//{
//    var terapia = '';
//    $.get("?option=Personal&sub=laboral&met=getDatosLaborales&id=" + valor,
//            function(data) {
////                    alert(data);
//                terapia = data;
//            }
//    );
//    return terapia;
//}
//
//function obtenerTerapiaPaciente(valor)
//{
//    var terapia = '';
//    $.get("?option=Terapias&sub=pacientes&met=listaTerapiasPaciente&id=" + valor,
//            function(data) {
//                terapia = data;
//            }
//    );
//    return terapia;
//}

function recorrerDivs() {
    var div = new Array();
    var r = 0;
    $("#turnosdia div").each(function(index, elemento) {
        var celda = $(elemento).attr("idpaciente");
        if (celda > 0) {
            r += parseInt(div[celda]);
            console.log(r);
            if (r > 3) {
                $(elemento).css({"background-color": "#5fe"});
            }
        }
    })
}
$(function() {
    $("#dialog-form").dialog({
        autoOpen: false,
        height: 300,
        width: 350,
        modal: true,
        buttons: {
            "Guardar": function() {
                var observaciones = $("#observaciones").val();
                var idTurno = $("#dialog-form").attr('idTurno');
                $.post("?option=Turnos&sub=index&met=guardarObservaciones_turno",
                        {observaciones: observaciones, idTurno: idTurno},
                function(data) {
                    $("#dialog-message").attr("title", 'Atención');
                    $("#msg").text(data);
                    mostrarDialogo();
                });

                $(this).dialog("close");
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {
        }
    });
})

function imprimir_turnos(){
    $("#encabezado").hide();
    $("#encabezado2").hide();
    $("#barraherramientas").hide();
    $("#pie").hide();
    window.print();
    $("#encabezado").show();
    $("#encabezado2").show();
    $("#barraherramientas").show();
    $("#pie").show();
}

$(function() {
    $("#pacientes").chosen({no_results_text: "Oops, no se encuentra!"});
});
