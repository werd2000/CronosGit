$(document).ready(function () {
    loc = location.hostname;
    $('#lista_pacientes').dataTable({
        "order": [[1, "asc"]],
        "language": {
            "url": "http://" + loc + "/Cronos/Public/Js/Spanish.json"
            },
        stateSave: true,
        "columnDefs": [{
                "targets": [0],
                "visible": true,
                "searchable": false
            }
        ]
    }
    );

    $('#lista_pacientes tbody').on('click', 'tr', function () {
        var id = $('td', this).eq(0).text();
        loc = location.hostname;
        window.location.assign('http://' + loc + '/Cronos/index.php?option=Paciente&sub=index&cont=editar&id=' + Number(id))
    });
});