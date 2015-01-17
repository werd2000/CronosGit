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
    $( "#fechaIngreso" ).datepicker();
}); 
   


   
$(function() {
    $("#imprimir").click(function () {
        var id = $("#idObraSocial").attr("value");
        var datos = new Object();
        var pacientes;
        var leyenda = $("#leyenda").attr("value");
        var i = 0
        $('.paciente').each(function(){
            var checkbox = $(this);
            if(checkbox.is(':checked')){
                pacientes += (checkbox.attr('value')) + '-';
            }
            i++;
            datos[0]=pacientes;
            datos[1]=leyenda;
        });
        var jObject={};
        for(i in datos){
            jObject[i] = datos[i];
        }
    
        $.post("http://www.pequehogar.com.ar/Cronos/index.php?option=pdfphsrl&sub=facturacion&id="+id,
          {datos: jObject},
          function(data) {
            alert("Data Loaded: " + data);
          });
    
    
//        var res = 'a:'+datos.length+':{';
//        for(i=0; i<datos.length; i++){
//        res += 'i:'+i+';s:'+datos[i].length+':"'+datos[i]+'";';
//        }
//        res += '}';
//        location.href='index.php?option=pdfphsrl&sub=facturacion&id='+id+'&datos='+res ;
    });
})
