$(document).ready(function () {
    var loc = location.hostname;
    var t = $('#lista_pacientes').dataTable({
        "order": [[1, "asc"]],
        "language": {
            "url": "http://" + loc + "/Cronos/Public/Js/Spanish.json"
            },
        stateSave: true,
        "columnDefs": [{
                "targets": 0,
                "searchable": false,
                "orderable": false
            }
        ]
    }
    );
    

    $('#lista_pacientes tbody').on('click', 'tr', function () {
        var id = this.id;
        loc = location.hostname;
        window.location.assign('http://' + loc + '/Cronos/index.php?option=Paciente&sub=index&cont=editar&id=' + Number(id));
    });
});