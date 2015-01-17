$(document).ready(function(){
    $('#nuevo_paciente').validate({
        rules:{
            apellidos:{
                required: true
            },
            nombres:{
                required: true
            },
            nro_doc:{
                required: true
            }
        },
        messages:{
            apellidos: {
                required: "Debe introducir el apellido"
            },
            nombres:{
                required: "Debe introducir el nombre"
            },
            nro_doc:{
                required: "Debe introducir el número de documento"
            }
        }
    });
});

