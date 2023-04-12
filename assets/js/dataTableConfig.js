$(document).ready(function () {

    const language = {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
    };

    let params = {};
    window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(str,key,value) {
        params[key] = value;
    });

    let confirmModal = null;
    createModalConfirm('Supprimer ?', 'Etes-vous sûr de vouloir supprimer ?', 'Supprimer');

    function createModalConfirm(title, messageConfirmModal, textButtonConfirm) {
        confirmModal = $('<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="confirmLabel" aria-hidden="true"> ' +
            '<div class="modal-dialog modal-confirm" role="document"> ' +
            '<div class="modal-content"> <div class="modal-header"> <h5 class="modal-title" id="confirmLabel"> ' + title + ' </h5> ' +
            '<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div> ' +
            '<div class="modal-body"> ' + messageConfirmModal + ' </div> <div class="modal-footer">  ' +
            '<button class="btn btn-danger" id="confirmDelete" data-dismiss="modal"> ' + textButtonConfirm + ' </button> ' +
            ' <button class="btn btn-secondary" data-dismiss="modal"> Annuler </button>' +
            '</div> </div> </div> </div>');
    }

    // Tableau simple par défaut.
    $('.tableDefault').DataTable({
        "bLengthChange": false,
        "bFilter": false,
        "bInfo": false,
        language: language
    });

    // Tableau des arbres.
    $('#tableTree').DataTable({
        "ajax": "/admin/trees/datatable",
        "responsive": true,
        "language": language,
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "order": [[7, "desc"]],
        "columnDefs": [
            {targets: [9], className: "td-actions"},
            {targets: [0, 2, 9], orderable: false}
        ],
        "columns": [
            {
                data: "active",
                render: function (data, type, row) {
                    if (type === 'display') {
                        return '<input type="checkbox" class="dt-active" data-id="' + row.id + '">';
                    }
                    return data;
                },
                className: "dt-body-center"
            },
            { data: "status", responsivePriority: 3, className: "text-center" },
            { data: "id", responsivePriority: 2 },
            { data: "name", responsivePriority: 4 },
            { data: "greenhouse", responsivePriority: 5 },
            { data: "cultureTable" },
            { data: "segment" },
            { data: "tableColumn" },
            { data: "columnRow" },
            { data: "actions", responsivePriority: 1 }
        ],
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-12 col-md-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            {
                className: 'buttons-action btn btn-info',
                text: '<i class="material-icons">qr_code</i> Regénérer QrCodes',
                action: function (e, dt, node, config) {
                    let trees = [];

                    $('input.dt-active:checked').each(function (index, input) {
                        trees.push($(input).data('id'));
                    });

                    window.location = '/admin/trees/regenerateQrcodes?trees='+trees;
                }
            },
            {
                className: 'buttons-action btn btn-danger',
                text: '<i class="material-icons">delete</i> Supprimer',
                action: function (e, dt, node, config) {
                    confirmModal.on('shown.bs.modal', function () {
                        $('#confirmDelete').on('click', function () {
                            let trees = [];

                            $('input.dt-active:checked').each(function (index, input) {
                                trees.push($(input).data('id'));
                            });

                            $.ajax({
                                method: "POST",
                                url: "/admin/trees/multiDelete",
                                data: { trees: trees }
                            }).done(function( msg ) {
                                location.reload();
                            });
                        })
                    });

                    confirmModal.modal('show');
                }
            }
        ],
        initComplete: function () {
            this.api().columns([1, 3, 4]).every( function () {
                var column = this;
                var select = $('<select class="form-control"><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );

                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                switch (column.index()) {
                    case 1:
                        $.ajax({
                            method: "GET",
                            url: "/admin/trees/datatable/filter",
                            data: {type: 'status'}
                        }).done(function( allStatus ) {
                            $.each(allStatus, function (index, status) {
                                select.append( '<option value="' + index + '">' + status + '</option>' );
                            })
                        });
                        break;

                    case 3:
                        $.ajax({
                            method: "GET",
                            url: "/admin/trees/datatable/filter",
                            data: {type: 'species'}
                        }).done(function( allSpecies ) {
                            $.each(allSpecies, function (index, species) {
                                select.append( '<option value="' + species + '">' + species + '</option>' );
                            })
                        });
                        break;

                    case 4:
                        $.ajax({
                            method: "GET",
                            url: "/admin/trees/datatable/filter",
                            data: {type: 'greenhouse'}
                        }).done(function( allLocation ) {
                            $.each(allLocation, function (index, greenhouse) {
                                select.append( '<option value="' + greenhouse + '">' + greenhouse + '</option>' );
                            })
                        });
                        break;

                    default:
                        column.data().unique().sort().each( function ( d, j ) {
                            if (d.length)
                            {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            }
                        } );
                }
            } );
        }
    });

    // Tableau des arbres dans une serre
    $('.tableLotGreenhouse').DataTable({
        "bPaginate": false,
        "bFilter": false,
        "bInfo": false,
        language: language,
        order: [[ 4, 'asc' ]],
        dom: "<'col-sm-12 text-left'B><'col-sm-12 col-md-4'f>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        columnDefs: [
            {targets: [0], orderable: false}
        ],
        buttons: [
            {
                className: 'buttons-action btn btn-info',
                text: '<i class="fa fa-pagelines mr-1"></i> Mettre dans un lot',
                action: function (e, dt, node, config) {
                    let trees = [];

                    $('input.dt-active:checked').each(function (index, input) {
                        trees.push($(input).data('id'));
                    });

                    $('#modalMoveInLot').modal('show');
                    $('#modalMoveInLot').find('.selectpicker').selectpicker('val', trees);
                }
            },
            {
                className: 'buttons-action btn btn-danger',
                text: '<i class="material-icons">logout</i> Retirer de la serre',
                action: function (e, dt, node, config) {
                    let trees = [];

                    $('input.dt-active:checked').each(function (index, input) {
                        trees.push($(input).data('id'));
                    });

                    if (trees.length > 1)
                    {
                        messageConfirmModal = 'Etes-vous sûr de vouloir retirer ces arbres de cette serre ?';
                    } else {
                        messageConfirmModal = 'Etes-vous sûr de vouloir retirer cette arbre de cette serre ?';
                    }

                    createModalConfirm('Retirer ?', messageConfirmModal, 'Retirer');

                    confirmModal.on('shown.bs.modal', function () {
                        $('#confirmDelete').on('click', function () {
                            $.ajax({
                                method: "POST",
                                url: "/admin/trees/removeTreeGreenhouse",
                                data: { trees: trees }
                            }).done(function (msg) {
                                location.reload();
                            });
                        })
                    });

                    confirmModal.modal('show');
                }
            }
        ],
    });

    // Tableau des lots.
    $('#tableLot').DataTable({
        "ajax": "/admin/lots/datatable",
        "responsive": true,
        "language": language,
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "order": [[1, "desc"]],
        "columnDefs": [
            {targets: [6], className: "td-actions"},
            {targets: [0, 6], orderable: false}
        ],
        "columns": [
            {
                data: "active",
                render: function (data, type, row) {
                    if (type === 'display') {
                        return '<input type="checkbox" class="dt-active" data-id="' + row.id + '">';
                    }
                    return data;
                },
                className: "dt-body-center"
            },
            {data: "id"},
            {data: "name"},
            {data: "place"},
            {data: "postalCode"},
            {data: "city"},
            {data: "actions"}
        ],
        dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            {
                className: 'buttons-action btn btn-info',
                text: '<i class="material-icons">qr_code</i> Regénérer QrCodes',
                action: function (e, dt, node, config) {
                    let lots = [];

                    $('input.dt-active:checked').each(function (index, input) {
                        lots.push($(input).data('id'));
                    });

                    window.location = '/admin/lots/regenerateQrcodes?lots='+lots;
                }
            },
            {
                className: 'buttons-action btn btn-danger',
                text: '<i class="material-icons">delete</i> Supprimer',
                action: function (e, dt, node, config) {
                    confirmModal.on('shown.bs.modal', function () {
                        $('#confirmDelete').on('click', function () {
                            let lots = [];

                            $('input.dt-active:checked').each(function (index, input) {
                                lots.push($(input).data('id'));
                            });

                            $.ajax({
                                method: "POST",
                                url: "/admin/lots/multiDelete",
                                data: { lots: lots }
                            }).done(function( msg ) {
                                location.reload();
                            });
                        })
                    });

                    confirmModal.modal('show');
                }
            }
        ]
    });

    let todo = params.todo ? params.todo : 0;
    // Tableau des travaux des arbres.
    $('#tableTreeWork').DataTable({
        "ajax": "/admin/tree-works/datatable?todo=" + todo,
        "language": language,
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "order": [[0, "desc"]],
        "columnDefs": [
            {targets: [6], className: "td-actions"}
        ],
        "columns": [
            {data: "date"},
            {data: "tree"},
            {data: "work"},
            {data: "species"},
            {data: "greenhouse"},
            {data: "user"},
            {data: "actions"}
        ]
    });

    // Tableau des obsercations des arbres.
    $('#tableObservation').DataTable({
        "ajax": "/admin/observations/datatable",
        "language": language,
        "responsive": true,
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "order": [[0, "desc"]],
        "columnDefs": [
            {targets: [6], className: "td-actions"}
        ],
        "columns": [
            {data: "date", responsivePriority: 1},
            {data: "tree", responsivePriority: 2},
            {data: "species"},
            {data: "greenhouse"},
            {data: "user"},
            {data: "comment", responsivePriority: 3},
            {data: "actions", responsivePriority: 4}
        ]
    });

    // Tableau des rangs.
    $('#tableColumnRow').DataTable({
        "ajax": "/admin/column-rows/datatable",
        "language": language,
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            {targets: [2], className: "td-actions"}
        ],
        "columns": [
            {data: "id"},
            {data: "name"},
            {data: "actions"}
        ]
    });

    // Tableau des colonnes.
    $('#tableColumn').DataTable({
        "ajax": "/admin/table-columns/datatable",
        "language": language,
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            {targets: [2], className: "td-actions"}
        ],
        "columns": [
            {data: "id"},
            {data: "name"},
            {data: "actions"}
        ]
    });

    // Tableau des segments.
    $('#tableSegment').DataTable({
        "ajax": "/admin/segments/datatable",
        "language": language,
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            {targets: [2], className: "td-actions"}
        ],
        "columns": [
            {data: "id"},
            {data: "name"},
            {data: "actions"}
        ]
    });

    // Tableau des tables de culture.
    $('#tableCultureTable').DataTable({
        "ajax": "/admin/culture-tables/datatable",
        "language": language,
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            {targets: [2], className: "td-actions"}
        ],
        "columns": [
            {data: "id"},
            {data: "name"},
            {data: "actions"}
        ]
    });

    // Tableau des adoptants.
    $('#tableOwners').DataTable({
        "ajax": "/admin/owners/datatable",
        "language": language,
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            {targets: [6], className: "td-actions"}
        ],
        "columns": [
            {data: "name"},
            {data: "postalCode"},
            {data: "nbAdoption"},
            {data: "species"},
            {data: "email"},
            {data: "phone"},
            {data: "actions"}
        ]
    });

    // Tableau des espèces
    $('#tableSpecies').DataTable({
        "ajax": "/admin/species/datatable",
        "language": language,
        "responsive": true,
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "order": [[ 4, "asc", 0, "desc"]],
        "columnDefs": [
            {targets: [5], className: "td-actions"}
        ],
        "columns": [
            {data: "name", responsivePriority: 1},
            {data: "latinName", responsivePriority: 3},
            {data: "leafType"},
            {data: "statusUicn"},
            {data: "validate"},
            {data: "actions", responsivePriority: 2}
        ]
    });

    // ============= EVENEMENTS GENERALS =============
    $('table').on('change', 'input.dt-active', function () {
        let nbActive = $('input.dt-active:checked').length;

        if (nbActive) {
            $('.buttons-action').show();
        } else {
            $('.buttons-action').hide();
        }
    });

    $('#select_all').on('click', function () {
        let rows = $('.table').DataTable().rows({'active': 'applied'}).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
});
