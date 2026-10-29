@extends('layouts.ap')

@section('content')

<div class="container-fluid">

    {{-- =====================================================
         EN-TÊTE
    ====================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="fas fa-building me-2"></i>
                Gestion des directions
            </h4>

            <p class="text-muted mb-0">
                Gestion des directions de la CANAM
            </p>

        </div>

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#directionModal"
            onclick="openCreateModal()"
        >
            <i class="fas fa-plus me-1"></i>
            Nouvelle direction
        </button>

    </div>


    {{-- =====================================================
         ALERTES
    ====================================================== --}}

    <div id="alertMessage">

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =====================================================
         LISTE DES DIRECTIONS
    ====================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h5 class="mb-0">

                        <i class="fas fa-list me-2"></i>

                        Liste des directions

                    </h5>

                </div>


                <div class="col-md-6">

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="fas fa-search"></i>

                        </span>

                        <input
                            type="text"
                            id="searchDirection"
                            class="form-control"
                            placeholder="Rechercher une direction..."
                        >

                    </div>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-hover table-bordered align-middle mb-0"
                    id="directionsTable"
                >

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Direction
                            </th>

                            <th>
                                Description
                            </th>

                            <th width="130" class="text-center">
                                Services
                            </th>

                            <th width="120" class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($directions as $direction)

                            <tr>

                                <td>

                                    {{ $loop->iteration +
                                        (($directions->currentPage() - 1)
                                        * $directions->perPage()) }}

                                </td>


                                <td>

                                    <strong>

                                        {{ $direction->nom_direction }}

                                    </strong>

                                </td>


                                <td>

                                    {{ $direction->description ?? '-' }}

                                </td>


                                <td class="text-center">

                                    <span class="badge bg-info text-dark">

                                        {{ $direction->services_count }}

                                    </span>

                                </td>


                                <td class="text-center">

                                    <div class="btn-group btn-group-sm">

                                        <button
                                            type="button"
                                            class="btn btn-warning"
                                            title="Modifier"
                                            onclick="editDirection({{ $direction->id }})"
                                        >

                                            <i class="fas fa-edit"></i>

                                        </button>


                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                            title="Supprimer"
                                            onclick="deleteDirection(
                                                {{ $direction->id }},
                                                @js($direction->nom_direction)
                                            )"
                                        >

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-5 text-muted"
                                >

                                    <i class="fas fa-building fa-2x mb-3"></i>

                                    <br>

                                    Aucune direction enregistrée.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($directions->hasPages())

            <div class="card-footer bg-white">

                {{ $directions->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =====================================================
     MODALE AJOUT / MODIFICATION
====================================================== --}}

<div
    class="modal fade"
    id="directionModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">


            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="directionModalTitle"
                >

                    <i class="fas fa-plus-circle me-2"></i>

                    Nouvelle direction

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form id="directionForm">

                @csrf


                <input
                    type="hidden"
                    id="direction_id"
                    name="direction_id"
                >


                <div class="modal-body">


                    <div
                        id="directionError"
                        class="alert alert-danger d-none"
                    ></div>


                    <div class="row g-3">


                        <div class="col-md-12">

                            <label
                                class="form-label"
                                for="nom_direction"
                            >

                                Nom de la direction

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                id="nom_direction"
                                name="nom_direction"
                                placeholder="Ex : Direction des Systèmes d'Information"
                                required
                            >

                        </div>


                        <div class="col-md-12">

                            <label
                                class="form-label"
                                for="description"
                            >

                                Description

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                rows="4"
                                placeholder="Description de la direction..."
                                required
                            ></textarea>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">


                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        Annuler

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="saveDirectionBtn"
                    >

                        <i class="fas fa-save me-1"></i>

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =====================================================
     MODALE SUPPRESSION
====================================================== --}}

<div
    class="modal fade"
    id="deleteDirectionModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">


            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title">

                    <i class="fas fa-exclamation-triangle me-2"></i>

                    Confirmation de suppression

                </h5>


                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <p class="mb-2">

                    Voulez-vous vraiment supprimer la direction :

                </p>


                <h5
                    id="deleteDirectionName"
                    class="text-danger"
                ></h5>


                <div
                    id="deleteDirectionError"
                    class="alert alert-danger d-none mt-3"
                ></div>

            </div>


            <div class="modal-footer">


                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >

                    Annuler

                </button>


                <button
                    type="button"
                    class="btn btn-danger"
                    id="confirmDeleteDirection"
                >

                    <i class="fas fa-trash me-1"></i>

                    Supprimer

                </button>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>

$(document).ready(function () {


    // =====================================================
    // CSRF
    // =====================================================

    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]').attr('content')

        }

    });


    // =====================================================
    // ENREGISTRER / MODIFIER
    // =====================================================

    $('#directionForm').submit(function (e) {

        e.preventDefault();


        let id =
            $('#direction_id').val();


        let url;

        let method;


        if (id) {

            url =
                "{{ url('/update-direction') }}/" + id;

            method = 'PUT';

        } else {

            url =
                "{{ route('storeDirection') }}";

            method = 'POST';

        }


        let formData = {

            _token:
                "{{ csrf_token() }}",

            _method:
                method,

            nom_direction:
                $('#nom_direction').val(),

            description:
                $('#description').val()

        };


        clearErrors();


        $('#saveDirectionBtn')
            .prop('disabled', true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...'
            );


        $.ajax({

            url: url,

            type: 'POST',

            data: formData,


            success: function (response) {


                $('#saveDirectionBtn')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-save me-1"></i> Enregistrer'
                    );


                if (
                    response.status === 200 ||
                    response.status === 201
                ) {


                    $('#directionModal')
                        .modal('hide');


                    showAlert(
                        'success',
                        response.message ||
                        'Direction enregistrée avec succès.'
                    );


                    setTimeout(function () {

                        location.reload();

                    }, 800);


                } else {


                    showAlert(
                        'danger',
                        response.message ||
                        'Une erreur est survenue.'
                    );

                }

            },


            error: function (xhr) {


                $('#saveDirectionBtn')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-save me-1"></i> Enregistrer'
                    );


                if (xhr.status === 422) {


                    let errors =
                        xhr.responseJSON.errors;


                    displayErrors(errors);


                } else {


                    let message =
                        xhr.responseJSON?.message ||
                        'Une erreur est survenue lors de l’enregistrement.';


                    showAlert(
                        'danger',
                        message
                    );

                }

            }

        });

    });


    // =====================================================
    // RECHERCHE
    // =====================================================

    $('#searchDirection').on('keyup', function () {


        let value =
            $(this).val().toLowerCase();


        $('#directionsTable tbody tr').filter(function () {


            $(this).toggle(

                $(this)
                    .text()
                    .toLowerCase()
                    .indexOf(value) > -1

            );

        });

    });


    // =====================================================
    // SUPPRESSION
    // =====================================================

    $('#confirmDeleteDirection').click(function () {


        let id =
            $(this).data('id');


        if (!id) {

            return;

        }


        $('#confirmDeleteDirection')
            .prop('disabled', true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Suppression...'
            );


        $.ajax({

            url:
                "{{ url('/delete-direction') }}/" + id,

            type: 'POST',

            data: {

                _token:
                    "{{ csrf_token() }}",

                _method:
                    'DELETE'

            },


            success: function (response) {


                if (response.status === 200) {


                    $('#deleteDirectionModal')
                        .modal('hide');


                    showAlert(
                        'success',
                        response.message ||
                        'Direction supprimée avec succès.'
                    );


                    setTimeout(function () {

                        location.reload();

                    }, 800);


                } else {


                    $('#confirmDeleteDirection')
                        .prop('disabled', false)
                        .html(
                            '<i class="fas fa-trash me-1"></i> Supprimer'
                        );


                    $('#deleteDirectionError')
                        .removeClass('d-none')
                        .html(
                            response.message ||
                            'Impossible de supprimer la direction.'
                        );

                }

            },


            error: function (xhr) {


                $('#confirmDeleteDirection')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-trash me-1"></i> Supprimer'
                    );


                let message =
                    xhr.responseJSON?.message ||
                    'Impossible de supprimer la direction.';


                $('#deleteDirectionError')
                    .removeClass('d-none')
                    .html(message);

            }

        });

    });

});


// =====================================================
// NOUVELLE DIRECTION
// =====================================================

function openCreateModal()
{

    $('#directionForm')[0].reset();


    $('#direction_id').val('');


    $('#directionModalTitle').html(

        '<i class="fas fa-plus-circle me-2"></i> Nouvelle direction'

    );


    clearErrors();

}


// =====================================================
// MODIFIER DIRECTION
// =====================================================

function editDirection(id)
{

    clearErrors();


    $.ajax({

        url:
            "{{ url('/edit-direction') }}/" + id,

        type: 'GET',


        success: function (response) {


            if (response.status === 200) {


                let direction =
                    response.direction;


                $('#direction_id')
                    .val(direction.id);


                $('#nom_direction')
                    .val(direction.nom_direction);


                $('#description')
                    .val(direction.description);


                $('#directionModalTitle').html(

                    '<i class="fas fa-edit me-2"></i> Modifier la direction'

                );


                $('#directionModal')
                    .modal('show');

            }

        },


        error: function () {


            showAlert(
                'danger',
                'Impossible de récupérer les informations de la direction.'
            );

        }

    });

}


// =====================================================
// SUPPRIMER DIRECTION
// =====================================================

function deleteDirection(id, name)
{

    $('#deleteDirectionName')
        .text(name);


    $('#deleteDirectionError')
        .addClass('d-none')
        .html('');


    $('#confirmDeleteDirection')
        .data('id', id)
        .prop('disabled', false)
        .html(
            '<i class="fas fa-trash me-1"></i> Supprimer'
        );


    $('#deleteDirectionModal')
        .modal('show');

}


// =====================================================
// AFFICHER LES ERREURS
// =====================================================

function displayErrors(errors)
{

    let message = '';


    $.each(errors, function (field, messages) {


        $.each(messages, function (index, msg) {


            message +=
                '<div>' +
                msg +
                '</div>';

        });

    });


    $('#directionError')
        .removeClass('d-none')
        .html(message);

}


// =====================================================
// NETTOYER LES ERREURS
// =====================================================

function clearErrors()
{

    $('#directionError')
        .addClass('d-none')
        .html('');

}


// =====================================================
// MESSAGE
// =====================================================

function showAlert(type, message)
{

    let html = `

        <div
            class="alert alert-${type} alert-dismissible fade show"
            role="alert"
        >

            ${message}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    `;


    $('#alertMessage')
        .html(html);

}

</script>

@endsection