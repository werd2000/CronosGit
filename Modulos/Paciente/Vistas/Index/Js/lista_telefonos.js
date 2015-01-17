$(document).ready(function () {
    loc = location.hostname;
//    $('#lista_dirTelefonico').dataTable({
//        "order": [[1, "asc"]],
//        "language": {
//            "url": "http://" + loc + "/Cronos/Public/Js/Spanish.json"
//            },
//        stateSave: true,
//        "columnDefs": [{
//                "targets": [0],
//                "visible": true,
//                "searchable": false
//            }
//        ]
//    }
//    );
    
    var table = $('#lista_dirTelefonico').DataTable({
        "columnDefs": [
            { "visible": false, "targets": 1 }
        ],
        "language": {
            "url": "http://" + loc + "/Cronos/Public/Js/Spanish.json"
            },
        "order": [[ 1, 'asc' ]],
        "displayLength": 25,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="4">'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
    } );
    
    // Order by the grouping
//    $('#lista_dirTelefonico tbody').on( 'click', 'tr.group', function () {
//        var currentOrder = table.order()[0];
//        if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
//            table.order( [ 2, 'desc' ] ).draw();
//        }
//        else {
//            table.order( [ 2, 'asc' ] ).draw();
//        }
//    } );

    $('#lista_dirTelefonico tbody').on('click', 'tr', function () {
        var id = $('td', this).eq(0).text();
        loc = location.hostname;
        window.location.assign('http://' + loc + '/Cronos/index.php?option=Paciente&sub=index&cont=editar&id=' + Number(id))
    });
});