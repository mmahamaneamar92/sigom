@extends('layouts.ap')

@section('content')

<div class="container-fluid">

    {{-- =====================================================
         EN-TÊTE
    ====================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-cogs me-2"></i>
                Gestion des services
            </h4>

            <p class="text-muted mb-0">
                Gestion des services et de leurs directions
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#serviceModal"
            onclick="openCreateModal()"
        >
            <i class="fas fa-plus me-1"></i>
            Nouveau service
        </button>

    </div>


    {{-- =====================================================
         MESSAGE
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


    {{-- =====================================================
         ERREURS
    ====================================================== --}}
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
         TABLEAU DES SERVICES
    ====================================================== --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Liste des services
                    </h5>

                </div>

                <div class="col-md-6">

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>

                        <input
                            type="text"
                            id="searchService"
                            class="form-control"
                            placeholder="Rechercher un service..."
                        >

                    </div>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-hover table-bordered align-middle mb-0"
                    id="servicesTable"
                >

                    <thead class="table-light">

                        <tr>

                            <th width="60">#</th>

                            <th>Service</th>

                            <th>Description</th>

                            <th>Direction</th>

                            <th width="120" class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($services as $service)

                            <tr>

                                <td>
                                    {{ $loop->iteration + (($services->currentPage() - 1) * $services->perPage()) }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $service->nom_service }}
                                    </strong>

                                </td>


                                <td>

                                    {{ $service->description ?? '-' }}

                                </td>


                                <td>

                                    <span class="badge bg-info text-dark">

                                        {{ $service->directionRelation->nom_direction
                                            ?? $service->direction
                                            ?? '-' }}

                                    </span>

                                </td>


                                <td class="text-center">

                                    <div class="btn-group btn-group-sm">

                                        {{-- MODIFIER --}}
                                        <button
                                            type="button"
                                            class="btn btn-warning"
                                            title="Modifier"
                                            onclick="editService({{ $service->id }})"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>


                                        {{-- SUPPRIMER --}}
                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                            title="Supprimer"
                                            onclick="deleteService(
                                                {{ $service->id }},
                                                @js($service->nom_service)
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

                                    <i class="fas fa-cogs fa-2x mb-3"></i>

                                    <br>

                                    Aucun service enregistré.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}
        @if($services->hasPages())

            <div class="card-footer bg-white">

                {{ $services->links() }}

            </div>

        @endif

    </div>

</div>



{{-- =========================================================
     MODALE AJOUT / MODIFICATION
========================================================= --}}

<div
    class="modal fade"
    id="serviceModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">


            {{-- HEADER --}}
            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="serviceModalTitle"
                >
                    <i class="fas fa-plus-circle me-2"></i>
                    Nouveau service
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            {{-- FORMULAIRE --}}
            <form id="serviceForm">

                @csrf

                <input
                    type="hidden"
                    id="service_id"
                    name="service_id"
                >


                <div class="modal-body">

                    <div
                        id="serviceError"
                        class="alert alert-danger d-none"
                    ></div>


                    <div class="row g-3">


                        {{-- NOM SERVICE --}}
                        <div class="col-md-6">

                            <label
                                class="form-label"
                                for="nom_service"
                            >

                                Nom du service

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nom_service"
                                name="nom_service"
                                placeholder="Ex : Service Informatique"
                                required
                            >

                        </div>


                        {{-- DIRECTION --}}
                        <div class="col-md-6">

                            <label
                                class="form-label"
                                for="direction"
                            >

                                Direction

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <select
                                class="form-select"
                                id="direction"
                                name="direction"
                                required
                            >

                                <option value="">
                                    -- Sélectionner une direction --
                                </option>

                                @foreach($directions as $direction)

                                    <option
                                        value="{{ $direction->nom_direction }}"
                                    >

                                        {{ $direction->nom_direction }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- DESCRIPTION --}}
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
                                placeholder="Description du service..."
                                required
                            ></textarea>

                        </div>

                    </div>

                </div>


                {{-- FOOTER --}}
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
                        id="saveServiceBtn"
                    >

                        <i class="fas fa-save me-1"></i>

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- =========================================================
     MODALE SUPPRESSION
========================================================= --}}

<div
    class="modal fade"
    id="deleteServiceModal"
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
                    Voulez-vous vraiment supprimer le service :
                </p>

                <h5
                    id="deleteServiceName"
                    class="text-danger"
                ></h5>


                <div
                    id="deleteServiceError"
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
                    id="confirmDeleteService"
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
    // ENREGISTRER / MODIFIER SERVICE
    // =====================================================

    $('#serviceForm').submit(function (e) {

        e.preventDefault();


        let id = $('#service_id').val();

        let url;

        let method;


        if (id) {

            url =
                "{{ url('/update-service') }}/" + id;

            method = 'PUT';

        } else {

            url =
                "{{ route('storeService') }}";

            method = 'POST';

        }


        let formData = {

            _token:
                "{{ csrf_token() }}",

            _method:
                method,

            nom_service:
                $('#nom_service').val(),

            description:
                $('#description').val(),

            direction:
                $('#direction').val()

        };


        clearErrors();


        $('#saveServiceBtn')
            .prop('disabled', true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...'
            );


        $.ajax({

            url: url,

            type: 'POST',

            data: formData,


            success: function (response) {

                $('#saveServiceBtn')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-save me-1"></i> Enregistrer'
                    );


                if (
                    response.status === 200 ||
                    response.status === 201
                ) {

                    $('#serviceModal').modal('hide');


                    showAlert(
                        'success',
                        response.message ||
                        'Service enregistré avec succès.'
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

                $('#saveServiceBtn')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-save me-1"></i> Enregistrer'
                    );


                if (xhr.status === 422) {

                    let errors =
                        xhr.responseJSON.errors;

                    displayErrors(errors);

                }

                else {

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

    $('#searchService').on('keyup', function () {

        let value =
            $(this).val().toLowerCase();


        $('#servicesTable tbody tr').filter(function () {

            $(this).toggle(

                $(this)
                    .text()
                    .toLowerCase()
                    .indexOf(value) > -1

            );

        });

    });


    // =====================================================
    // CONFIRMATION SUPPRESSION
    // =====================================================

    $('#confirmDeleteService').click(function () {

        let id =
            $(this).data('id');


        if (!id) {

            return;

        }


        $('#confirmDeleteService')
            .prop('disabled', true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Suppression...'
            );


        $.ajax({

            url:
                "{{ url('/delete-service') }}/" + id,

            type: 'POST',

            data: {

                _token:
                    "{{ csrf_token() }}",

                _method:
                    'DELETE'

            },


            success: function (response) {

                if (response.status === 200) {

                    $('#deleteServiceModal')
                        .modal('hide');


                    showAlert(
                        'success',
                        response.message ||
                        'Service supprimé avec succès.'
                    );


                    setTimeout(function () {

                        location.reload();

                    }, 800);

                }

                else {

                    $('#confirmDeleteService')
                        .prop('disabled', false)
                        .html(
                            '<i class="fas fa-trash me-1"></i> Supprimer'
                        );


                    $('#deleteServiceError')
                        .removeClass('d-none')
                        .html(
                            response.message ||
                            'Impossible de supprimer le service.'
                        );

                }

            },


            error: function (xhr) {

                $('#confirmDeleteService')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-trash me-1"></i> Supprimer'
                    );


                let message =
                    xhr.responseJSON?.message ||
                    'Impossible de supprimer le service.';


                $('#deleteServiceError')
                    .removeClass('d-none')
                    .html(message);

            }

        });

    });

});



// =====================================================
// NOUVEAU SERVICE
// =====================================================

function openCreateModal()
{

    $('#serviceForm')[0].reset();

    $('#service_id').val('');

    $('#serviceModalTitle').html(
        '<i class="fas fa-plus-circle me-2"></i> Nouveau service'
    );

    clearErrors();

}



// =====================================================
// MODIFIER SERVICE
// =====================================================

function editService(id)
{

    clearErrors();


    $.ajax({

        url:
            "{{ url('/edit-service') }}/" + id,

        type: 'GET',


        success: function (response) {

            if (response.status === 200) {

                let service =
                    response.service;


                $('#service_id')
                    .val(service.id);


                $('#nom_service')
                    .val(service.nom_service);


                $('#description')
                    .val(service.description);


                /*
                 * La colonne services.direction
                 * contient directement le nom
                 * de la direction.
                 */

                $('#direction')
                    .val(service.direction);


                $('#serviceModalTitle').html(
                    '<i class="fas fa-edit me-2"></i> Modifier le service'
                );


                $('#serviceModal')
                    .modal('show');

            }

        },


        error: function () {

            showAlert(
                'danger',
                'Impossible de récupérer les informations du service.'
            );

        }

    });

}



// =====================================================
// SUPPRIMER SERVICE
// =====================================================

function deleteService(id, name)
{

    $('#deleteServiceName')
        .text(name);


    $('#deleteServiceError')
        .addClass('d-none')
        .html('');


    $('#confirmDeleteService')
        .data('id', id)
        .prop('disabled', false)
        .html(
            '<i class="fas fa-trash me-1"></i> Supprimer'
        );


    $('#deleteServiceModal')
        .modal('show');

}



// =====================================================
// ERREURS
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


    $('#serviceError')
        .removeClass('d-none')
        .html(message);

}



// =====================================================
// NETTOYER ERREURS
// =====================================================

function clearErrors()
{

    $('#serviceError')
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