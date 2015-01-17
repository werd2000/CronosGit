$(document).ready(function(){
    $('#nuevo_paciente').validate({
        rules:{
            fechaInicio:{
                required: true
            },
            descripcion:{
                required: true
            }
        },
        messages:{
            fechaInicio: {
                required: "Debe introducir fecha de Inicio"
            },
            descripcion:{
                required: "Debe introducir la descripcion"
            }
        }
    });
});

