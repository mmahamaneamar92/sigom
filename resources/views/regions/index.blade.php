@extends('layouts.ap')

@section('content')

<div class="container py-5">

    {{-- Message général --}}
    <div id="success_message"></div>


    {{-- =========================================================
         MODAL AJOUT REGION
    ========================================================== --}}
    <div class="modal fade"
         id="AddRegionModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Ajouter une région
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
                            Pays
                        </label>

                        <select id="pays_id"
                                class="form-select pays_id">

                            <option value="">
                                -- Sélectionner un pays --
                            </option>

                            @foreach($pays as $pay)

                                <option value="{{ $pay->id }}">
                                    {{ $pay->nom }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Nom de la région
                        </label>

                        <input type="text"
                               id="nom"
                               class="form-control nom"
                               placeholder="Ex : Bamako">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Code
                        </label>

                        <input type="text"
                               id="code"
                               class="form-control code"
                               placeholder="Code de la région">

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Fermer

                    </button>

                    <button type="button"
                            class="btn btn-primary add_region">

                        Enregistrer

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MODAL MODIFICATION
    ========================================================== --}}
    <div class="modal fade"
         id="editRegionModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Modifier la région
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <ul id="update_msgList"></ul>

                    <input type="hidden"
                           id="region_id">

                    <div class="mb-3">

                        <label class="form-label">
                            Pays
                        </label>

                        <select id="edit_pays_id"
                                class="form-select">

                            <option value="">
                                -- Sélectionner un pays --
                            </option>

                            @foreach($pays as $pay)

                                <option value="{{ $pay->id }}">
                                    {{ $pay->nom }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Nom de la région
                        </label>

                        <input type="text"
                               id="edit_nom"
                               class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Code
                        </label>

                        <input type="text"
                               id="edit_code"
                               class="form-control">

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Fermer

                    </button>

                    <button type="button"
                            class="btn btn-primary update_region">

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
         id="DeleteRegionModal"
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
                        Voulez-vous vraiment supprimer cette région ?
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
                            class="btn btn-danger delete_region">

                        Oui, supprimer

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TABLEAU DES REGIONS
    ========================================================== --}}

    <div class="row">

        <div class="col-md-12">

            <div class="card">

                <div class="card-header">

                    <h4 class="mb-0">

                        Région

                        <button type="button"
                                class="btn btn-primary float-end"
                                data-bs-toggle="modal"
                                data-bs-target="#AddRegionModal">

                            Ajouter une région

                        </button>

                    </h4>

                </div>

                <div class="card-body">

                    {{-- Recherche --}}
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

                                    <th>Région</th>

                                    <th>Pays</th>

                                    <th>Code</th>

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

    /*
    |--------------------------------------------------------------------------
    | AJAX CSRF
    |--------------------------------------------------------------------------
    */

    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]').attr('content')

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Charger les régions
    |--------------------------------------------------------------------------
    */

    fetchregion();


    function fetchregion() {

        $.ajax({

            type: "GET",

            url: "/fetch-regions",

            dataType: "json",

            success: function (response) {

                $('#tableau tbody').html("");

                $.each(
                    response.regions,
                    function (key, item) {

                        let pays = '-';

                        if (item.pays) {

                            pays =
                                item.pays.nom;

                        }

                        $('#tableau tbody').append(

                            '<tr>' +

                            '<td>' +
                            (key + 1) +
                            '</td>' +

                            '<td>' +
                            (item.nom ?? '') +
                            '</td>' +

                            '<td>' +
                            pays +
                            '</td>' +

                            '<td>' +
                            (item.code ?? '-') +
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
    | Recherche
    |--------------------------------------------------------------------------
    */

    $('#search').on('keyup', function () {

        var value =
            $(this).val().toLowerCase();

        $("#tableau tbody tr").filter(function () {

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
    | Ajouter une région
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.add_region',
        function (e) {

            e.preventDefault();

            $(this).text('Enregistrement...');

            var data = {

                'pays_id':
                    $('.pays_id').val(),

                'nom':
                    $('.nom').val(),

                'code':
                    $('.code').val()

            };


            $.ajax({

                type: "POST",

                url: "/regions",

                data: data,

                dataType: "json",

                success: function (response) {

                    if (response.status == 400) {

                        $('#save_msgList')
                            .html("")
                            .removeClass()
                            .addClass(
                                'alert alert-danger'
                            );

                        $.each(
                            response.errors,
                            function (
                                key,
                                err_value
                            ) {

                                $('#save_msgList')
                                    .append(
                                        '<li>' +
                                        err_value +
                                        '</li>'
                                    );

                            }
                        );

                        $('.add_region')
                            .text('Enregistrer');

                    }

                    else {

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

                        $('#AddRegionModal')
                            .find('input')
                            .val('');

                        $('#pays_id')
                            .val('');

                        $('.add_region')
                            .text('Enregistrer');

                        $('#AddRegionModal')
                            .modal('hide');

                        fetchregion();

                    }

                },

                error: function (xhr) {

                    $('.add_region')
                        .text('Enregistrer');

                    if (xhr.status == 422) {

                        $('#save_msgList')
                            .html("")
                            .removeClass()
                            .addClass(
                                'alert alert-danger'
                            );

                        $.each(
                            xhr.responseJSON.errors,
                            function (
                                key,
                                errors
                            ) {

                                $.each(
                                    errors,
                                    function (
                                        index,
                                        error
                                    ) {

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
    | Ouvrir modification
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.editbtn',
        function (e) {

            e.preventDefault();

            var id = $(this).val();

            $('#editRegionModal')
                .modal('show');

            $.ajax({

                type: "GET",

                url:
                    "/edit-region/" + id,

                dataType: "json",

                success: function (response) {

                    if (response.status == 404) {

                        $('#success_message')
                            .removeClass()
                            .addClass(
                                'alert alert-danger'
                            )
                            .text(
                                response.message
                            );

                        $('#editRegionModal')
                            .modal('hide');

                    }

                    else {

                        $('#edit_pays_id')
                            .val(
                                response.region.pays_id
                            );

                        $('#edit_nom')
                            .val(
                                response.region.nom
                            );

                        $('#edit_code')
                            .val(
                                response.region.code
                            );

                        $('#region_id')
                            .val(id);

                    }

                }

            });

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Modifier une région
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.update_region',
        function (e) {

            e.preventDefault();

            $(this).text('Modification...');

            var id =
                $('#region_id').val();

            var data = {

                'pays_id':
                    $('#edit_pays_id').val(),

                'nom':
                    $('#edit_nom').val(),

                'code':
                    $('#edit_code').val()

            };


            $.ajax({

                type: "PUT",

                url:
                    "/update-region/" + id,

                data: data,

                dataType: "json",

                success: function (response) {

                    if (response.status == 400) {

                        $('#update_msgList')
                            .html("")
                            .removeClass()
                            .addClass(
                                'alert alert-danger'
                            );

                        $.each(
                            response.errors,
                            function (
                                key,
                                err_value
                            ) {

                                $('#update_msgList')
                                    .append(
                                        '<li>' +
                                        err_value +
                                        '</li>'
                                    );

                            }
                        );

                        $('.update_region')
                            .text('Modifier');

                    }

                    else {

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

                        $('.update_region')
                            .text('Modifier');

                        $('#editRegionModal')
                            .modal('hide');

                        fetchregion();

                    }

                },

                error: function (xhr) {

                    $('.update_region')
                        .text('Modifier');

                    if (xhr.status == 422) {

                        $('#update_msgList')
                            .html("")
                            .removeClass()
                            .addClass(
                                'alert alert-danger'
                            );

                        $.each(
                            xhr.responseJSON.errors,
                            function (
                                key,
                                errors
                            ) {

                                $.each(
                                    errors,
                                    function (
                                        index,
                                        error
                                    ) {

                                        $('#update_msgList')
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
    | Ouvrir suppression
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.deletebtn',
        function () {

            var id = $(this).val();

            $('#DeleteRegionModal')
                .modal('show');

            $('#deleteing_id')
                .val(id);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Supprimer une région
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.delete_region',
        function (e) {

            e.preventDefault();

            $(this).text('Suppression...');

            var id =
                $('#deleteing_id').val();


            $.ajax({

                type: "DELETE",

                url:
                    "/delete-region/" + id,

                dataType: "json",

                success: function (response) {

                    if (response.status == 404) {

                        $('#success_message')
                            .removeClass()
                            .addClass(
                                'alert alert-danger'
                            )
                            .text(
                                response.message
                            );

                    }

                    else if (
                        response.status == 409
                    ) {

                        $('#success_message')
                            .removeClass()
                            .addClass(
                                'alert alert-danger'
                            )
                            .text(
                                response.message
                            );

                        $('#DeleteRegionModal')
                            .modal('hide');

                    }

                    else {

                        $('#success_message')
                            .removeClass()
                            .addClass(
                                'alert alert-success'
                            )
                            .text(
                                response.message
                            );

                        $('#DeleteRegionModal')
                            .modal('hide');

                        fetchregion();

                    }

                    $('.delete_region')
                        .text('Oui, supprimer');

                },

                error: function (xhr) {

                    $('.delete_region')
                        .text('Oui, supprimer');

                    if (xhr.responseJSON) {

                        $('#success_message')
                            .removeClass()
                            .addClass(
                                'alert alert-danger'
                            )
                            .text(
                                xhr.responseJSON.message
                            );

                    }

                }

            });

        }
    );

});

</script>

@endsection