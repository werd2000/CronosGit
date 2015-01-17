$(function() {
    $( "#fechaInicio" ).datepicker({
        changeYear: true
    }
);
    $( "#fechaFin" ).datepicker({
        changeYear: true
    }
);
    tinyMCE.init({selector : "textarea"});
    
    $("#fechaInicio").datepicker( "option", "dateFormat", "dd-mm-yy" );
    $("#fechaFin").datepicker( "option", "dateFormat", "dd-mm-yy" );

});   

$(function() {
    var fecha = $("#fechaInicio").attr("value");
    $( "#fechaInicio" ).datepicker();
    $("#fechaInicio").datepicker( "setDate", fecha );
});

$(function() {
    var fecha = $("#fechaFin").attr("value");
    $( "#fechaFin" ).datepicker();
    $("#fechaFin").datepicker( "setDate", fecha );
});

