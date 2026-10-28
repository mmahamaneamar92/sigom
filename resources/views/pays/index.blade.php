@extends('layouts.ap')

@section('content')

<div class="container py-5">

    <div id="success_message"></div>

    {{-- =========================================================
         MODAL AJOUT PAYS
    ========================================================== --}}

    <div class="modal fade"
         id="AddPaysModal"
         tabindex="-1"
         aria-labelledby="AddPaysModalLabel"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="AddPaysModalLabel">
                        Ajouter un pays
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <ul id="save_msgList"></ul>

                    <div class="mb-3">

                        <label class="form-label">
                            Nom du pays
                        </label>

                        <input type="text"
                               class="form-control nom"
                               placeholder="Ex : Mali">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Code ISO
                        </label>

                        <input type="text"
                               class="form-control code_iso"
                               maxlength="3"
                               placeholder="Ex : MLI">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Zone de majoration
                        </label>

                        <select class="form-select zone_majoration_id">

                            <option value="">
                                -- Sélectionner une zone --
                            </option>

                            @foreach($zones as $zone)

                                <option value="{{ $zone->id }}">
                                    {{ $zone->libelle }}
                                    ({{ $zone->taux }}%)
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Fermer

                    </button>

                    <button type="button"
                            class="btn btn-primary add_pays">

                        Enregistrer

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MODAL MODIFICATION PAYS
    ========================================================== --}}

    <div class="modal fade"
         id="editPaysModal"
         tabindex="-1"
         aria-labelledby="editPaysModalLabel"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Modifier le pays
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <ul id="update_msgList"></ul>

                    <input type="hidden" id="pays_id">

                    <div class="mb-3">

                        <label class="form-label">
                            Nom du pays
                        </label>

                        <input type="text"
                               id="edit_nom"
                               class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Code ISO
                        </label>

                        <input type="text"
                               id="edit_code_iso"
                               class="form-control"
                               maxlength="3">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Zone de majoration
                        </label>

                        <select id="edit_zone_majoration_id"
                                class="form-select">

                            <option value="">
                                -- Sélectionner une zone --
                            </option>

                            @foreach($zones as $zone)

                                <option value="{{ $zone->id }}">
                                    {{ $zone->libelle }}
                                    ({{ $zone->taux }}%)
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Fermer

                    </button>

                    <button type="button"
                            class="btn btn-primary update_pays">

                        Modifier

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MODAL SUPPRESSION
    ========================================================== --}}

    <div class="modal fade"
         id="DeletePaysModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Confirmation
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <h5>
                        Voulez-vous vraiment supprimer ce pays ?
                    </h5>

                    <input type="hidden"
                           id="deleteing_id">

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Annuler

                    </button>

                    <button type="button"
                            class="btn btn-danger delete_pays">

                        Oui, supprimer

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TABLEAU PAYS
    ========================================================== --}}

    <div class="row">

        <div class="col-md-12">

            <div class="card">

                <div class="card-header">

                    <h4 class="mb-0">

                        Pays

                        <button type="button"
                                class="btn btn-primary float-end"
                                data-bs-toggle="modal"
                                data-bs-target="#AddPaysModal">

                            Ajouter un pays

                        </button>

                    </h4>

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <x-text-input
                            id="search"
                            type="text"
                            placeholder="Recherche..."
                            class="form-control"
                        />

                    </div>

                    <div class="table-responsive">

                        <table id="tableau"
                               class="table table-bordered table-striped">

                            <thead>

                                <tr>

                                    <th>#</th>
                                    <th>Pays</th>
                                    <th>Code ISO</th>
                                    <th>Zone de majoration</th>
                                    <th>Taux</th>
                                    <th>Régions</th>
                                    <th width="80"></th>
                                    <th width="80"></th>

                                </tr>

                            </thead>

                            <tbody>
                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>

$(document).ready(function () {

    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]').attr('content')

        }

    });


    /*
    |--------------------------------------------------------------------------
    | FETCH PAYS
    |--------------------------------------------------------------------------
    */

    fetchpays();

    function fetchpays()
    {

        $.ajax({

            type: "GET",

            url: "/fetch-pays",

            dataType: "json",

            success: function (response)
            {

                $('#tableau tbody').html("");

                $.each(
                    response.pays,
                    function (key, item)
                    {

                        let zone = '-';
                        let taux = '-';

                        if (item.zone_majoration)
                        {

                            zone =
                                item.zone_majoration.libelle;

                            taux =
                                item.zone_majoration.taux + '%';

                        }

                        let regions =
                            item.regions_count ?? 0;


                        $('#tableau tbody').append(

                            '<tr>' +

                            '<td>' +
                            (key + 1) +
                            '</td>' +

                            '<td>' +
                            (item.nom ?? '') +
                            '</td>' +

                            '<td>' +
                            (item.code_iso ?? '-') +
                            '</td>' +

                            '<td>' +
                            zone +
                            '</td>' +

                            '<td>' +
                            taux +
                            '</td>' +

                            '<td>' +
                            regions +
                            '</td>' +

                            '<td>' +

                            '<button type="button" ' +
                            'value="' + item.id + '" ' +
                            'class="btn btn-primary editbtn btn-sm">' +

                            'Modifier' +

                            '</button>' +

                            '</td>' +

                            '<td>' +

                            '<button type="button" ' +
                            'value="' + item.id + '" ' +
                            'class="btn btn-danger deletebtn btn-sm">' +

                            'Supprimer' +

                            '</button>' +

                            '</td>' +

                            '</tr>'

                        );

                    }
                );

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | RECHERCHE
    |--------------------------------------------------------------------------
    */

    $('#search').on('keyup', function ()
    {

        var value =
            $(this).val().toLowerCase();

        $("#tableau tbody tr").filter(function ()
        {

            $(this).toggle(

                $(this)
                    .text()
                    .toLowerCase()
                    .indexOf(value) > -1

            );

        });

    });


    /*
    |--------------------------------------------------------------------------
    | AJOUT PAYS
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.add_pays',
        function (e)
        {

            e.preventDefault();

            $(this).text('Enregistrement...');


            var data = {

                'nom':
                    $('.nom').val(),

                'code_iso':
                    $('.code_iso').val(),

                'zone_majoration_id':
                    $('.zone_majoration_id').val()

            };


            $.ajax({

                type: "POST",

                url: "/pays",

                data: data,

                dataType: "json",

                success: function (response)
                {

                    if (response.status == 400)
                    {

                        $('#save_msgList')
                            .html("")
                            .addClass(
                                'alert alert-danger'
                            );


                        $.each(
                            response.errors,
                            function (key, err_value)
                            {

                                $('#save_msgList')
                                    .append(
                                        '<li>' +
                                        err_value +
                                        '</li>'
                                    );

                            }
                        );


                        $('.add_pays')
                            .text('Enregistrer');

                    }
                    else
                    {

                        $('#save_msgList')
                            .html("");

                        $('#success_message')
                            .removeClass()
                            .addClass(
                                'alert alert-success'
                            )
                            .text(
                                response.message
                            );


                        $('#AddPaysModal')
                            .find('input')
                            .val('');

                        $('.zone_majoration_id')
                            .val('');


                        $('.add_pays')
                            .text('Enregistrer');


                        $('#AddPaysModal')
                            .modal('hide');


                        fetchpays();

                    }

                },

                error: function (xhr)
                {

                    $('.add_pays')
                        .text('Enregistrer');

                    if (xhr.responseJSON)
                    {

                        $('#save_msgList')
                            .html("")
                            .addClass(
                                'alert alert-danger'
                            );

                        $.each(
                            xhr.responseJSON.errors,
                            function (key, errors)
                            {

                                $.each(
                                    errors,
                                    function (index, error)
                                    {

                                        $('#save_msgList')
                                            .append(
                                                '<li>' +
                                                error +
                                                '</li>'
                                            );

                                    }
                                );

                            }
                        );

                    }

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | OUVRIR MODIFICATION
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.editbtn',
        function (e)
        {

            e.preventDefault();

            var id = $(this).val();

            $('#editPaysModal')
                .modal('show');


            $.ajax({

                type: "GET",

                url: "/edit-pays/" + id,

                dataType: "json",

                success: function (response)
                {

                    if (response.status == 404)
                    {

                        $('#success_message')
                            .removeClass()
                            .addClass(
                                'alert alert-danger'
                            )
                            .text(
                                response.message
                            );

                        $('#editPaysModal')
                            .modal('hide');

                    }
                    else
                    {

                        $('#edit_nom')
                            .val(
                                response.pays.nom
                            );

                        $('#edit_code_iso')
                            .val(
                                response.pays.code_iso
                            );

                        $('#edit_zone_majoration_id')
                            .val(
                                response.pays.zone_majoration_id
                            );

                        $('#pays_id')
                            .val(id);

                    }

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | MODIFIER PAYS
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.update_pays',
        function (e)
        {

            e.preventDefault();

            $(this).text('Modification...');

            var id =
                $('#pays_id').val();


            var data = {

                'nom':
                    $('#edit_nom').val(),

                'code_iso':
                    $('#edit_code_iso').val(),

                'zone_majoration_id':
                    $('#edit_zone_majoration_id').val()

            };


            $.ajax({

                type: "PUT",

                url:
                    "/update-pays/" + id,

                data: data,

                dataType: "json",

                success: function (response)
                {

                    if (response.status == 400)
                    {

                        $('#update_msgList')
                            .html("")
                            .addClass(
                                'alert alert-danger'
                            );


                        $.each(
                            response.errors,
                            function (key, err_value)
                            {

                                $('#update_msgList')
                                    .append(
                                        '<li>' +
                                        err_value +
                                        '</li>'
                                    );

                            }
                        );


                        $('.update_pays')
                            .text('Modifier');

                    }
                    else
                    {

                        $('#update_msgList')
                            .html("");

                        $('#success_message')
                            .removeClass()
                            .addClass(
                                'alert alert-success'
                            )
                            .text(
                                response.message
                            );


                        $('.update_pays')
                            .text('Modifier');


                        $('#editPaysModal')
                            .modal('hide');


                        fetchpays();

                    }

                },

                error: function (xhr)
                {

                    $('.update_pays')
                        .text('Modifier');

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | OUVRIR SUPPRESSION
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.deletebtn',
        function ()
        {

            var id =
                $(this).val();

            $('#DeletePaysModal')
                .modal('show');

            $('#deleteing_id')
                .val(id);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER PAYS
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.delete_pays',
        function (e)
        {

            e.preventDefault();

            $(this).text('Suppression...');

            var id =
                $('#deleteing_id').val();


            $.ajax({

                type: "DELETE",

                url:
                    "/delete-pays/" + id,

                dataType: "json",

                success: function (response)
                {

                    if (response.status == 409)
                    {

                        $('#success_message')
                            .removeClass()
                            .addClass(
                                'alert alert-danger'
                            )
                            .text(
                                response.message
                            );

                    }
                    else
                    {

                        $('#success_message')
                            .removeClass()
                            .addClass(
                                'alert alert-success'
                            )
                            .text(
                                response.message
                            );

                        $('#DeletePaysModal')
                            .modal('hide');

                        fetchpays();

                    }

                    $('.delete_pays')
                        .text('Oui, supprimer');

                }

            });

        }
    );

});

</script>

@endsection