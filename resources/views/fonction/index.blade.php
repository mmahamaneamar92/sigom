@extends('layouts.ap')

@section('content')

<div class="container-fluid">

    {{-- =====================================================
         EN-TÊTE
    ====================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="fas fa-user-tie me-2"></i>
                Gestion des fonctions
            </h4>

            <p class="text-muted mb-0">
                Gestion des fonctions des agents de la CANAM
            </p>

        </div>

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#fonctionModal"
            onclick="openCreateModal()"
        >
            <i class="fas fa-plus me-1"></i>
            Nouvelle fonction
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
         LISTE
    ====================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h5 class="mb-0">

                        <i class="fas fa-list me-2"></i>

                        Liste des fonctions

                    </h5>

                </div>


                <div class="col-md-6">

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="fas fa-search"></i>

                        </span>

                        <input
                            type="text"
                            id="searchFonction"
                            class="form-control"
                            placeholder="Rechercher une fonction..."
                        >

                    </div>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-hover table-bordered align-middle mb-0"
                    id="fonctionsTable"
                >

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Fonction
                            </th>

                            <th>
                                Description
                            </th>

                            <th width="120" class="text-center">
                                Agents
                            </th>

                            <th width="120" class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($fonctions as $fonction)

                            <tr>

                                <td>

                                    {{ $loop->iteration +
                                        (($fonctions->currentPage() - 1)
                                        * $fonctions->perPage()) }}

                                </td>


                                <td>

                                    <strong>

                                        {{ $fonction->nom_fonction }}

                                    </strong>

                                </td>


                                <td>

                                    {{ $fonction->description ?? '-' }}

                                </td>


                                <td class="text-center">

                                    <span class="badge bg-info text-dark">

                                        {{ $fonction->agents_count }}

                                    </span>

                                </td>


                                <td class="text-center">

                                    <div class="btn-group btn-group-sm">

                                        <button
                                            type="button"
                                            class="btn btn-warning"
                                            title="Modifier"
                                            onclick="editFonction({{ $fonction->id }})"
                                        >

                                            <i class="fas fa-edit"></i>

                                        </button>


                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                            title="Supprimer"
                                            onclick="deleteFonction(
                                                {{ $fonction->id }},
                                                @js($fonction->nom_fonction)
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

                                    <i class="fas fa-user-tie fa-2x mb-3"></i>

                                    <br>

                                    Aucune fonction enregistrée.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($fonctions->hasPages())

            <div class="card-footer bg-white">

                {{ $fonctions->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =====================================================
     MODALE AJOUT / MODIFICATION
====================================================== --}}

<div
    class="modal fade"
    id="fonctionModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">


            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="fonctionModalTitle"
                >

                    <i class="fas fa-plus-circle me-2"></i>

                    Nouvelle fonction

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form id="fonctionForm">

                @csrf

                <input
                    type="hidden"
                    id="fonction_id"
                    name="fonction_id"
                >


                <div class="modal-body">

                    <div
                        id="fonctionError"
                        class="alert alert-danger d-none"
                    ></div>


                    <div class="row g-3">


                        <div class="col-md-12">

                            <label
                                class="form-label"
                                for="nom_fonction"
                            >

                                Nom de la fonction

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                id="nom_fonction"
                                name="nom_fonction"
                                placeholder="Ex : Directeur"
                                required
                            >

                        </div>


                        <div class="col-md-12">

                            <label
                                class="form-label"
                                for="description"
                            >

                                Description

                            </label>


                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                rows="4"
                                placeholder="Description de la fonction..."
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
                        id="saveFonctionBtn"
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
    id="deleteFonctionModal"
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

                    Voulez-vous vraiment supprimer la fonction :

                </p>


                <h5
                    id="deleteFonctionName"
                    class="text-danger"
                ></h5>


                <div
                    id="deleteFonctionError"
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
                    id="confirmDeleteFonction"
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
    // AJOUT / MODIFICATION
    // =====================================================

    $('#fonctionForm').submit(function (e) {

        e.preventDefault();


        let id =
            $('#fonction_id').val();


        let url;

        let method;


        if (id) {

            url =
                "{{ url('/update-fonction') }}/" + id;

            method = 'PUT';

        } else {

            url =
                "{{ route('fonctions.store') }}";

            method = 'POST';

        }


        let formData = {

            _token:
                "{{ csrf_token() }}",

            _method:
                method,

            nom_fonction:
                $('#nom_fonction').val(),

            description:
                $('#description').val()

        };


        clearErrors();


        $('#saveFonctionBtn')
            .prop('disabled', true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...'
            );


        $.ajax({

            url: url,

            type: 'POST',

            data: formData,


            success: function (response) {


                $('#saveFonctionBtn')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-save me-1"></i> Enregistrer'
                    );


                if (
                    response.status === 200 ||
                    response.status === 201
                ) {


                    $('#fonctionModal')
                        .modal('hide');


                    showAlert(
                        'success',
                        response.message ||
                        'Fonction enregistrée avec succès.'
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


                $('#saveFonctionBtn')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-save me-1"></i> Enregistrer'
                    );


                if (xhr.status === 422) {

                    displayErrors(
                        xhr.responseJSON.errors
                    );

                } else {

                    showAlert(
                        'danger',
                        xhr.responseJSON?.message ||
                        'Une erreur est survenue.'
                    );

                }

            }

        });

    });


    // =====================================================
    // RECHERCHE
    // =====================================================

    $('#searchFonction').on('keyup', function () {


        let value =
            $(this).val().toLowerCase();


        $('#fonctionsTable tbody tr').filter(function () {


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

    $('#confirmDeleteFonction').click(function () {


        let id =
            $(this).data('id');


        if (!id) {

            return;

        }


        $('#confirmDeleteFonction')
            .prop('disabled', true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Suppression...'
            );


        $.ajax({

            url:
                "{{ url('/delete-fonction') }}/" + id,

            type: 'POST',

            data: {

                _token:
                    "{{ csrf_token() }}",

                _method:
                    'DELETE'

            },


            success: function (response) {


                if (response.status === 200) {


                    $('#deleteFonctionModal')
                        .modal('hide');


                    showAlert(
                        'success',
                        response.message ||
                        'Fonction supprimée avec succès.'
                    );


                    setTimeout(function () {

                        location.reload();

                    }, 800);


                } else {


                    $('#confirmDeleteFonction')
                        .prop('disabled', false)
                        .html(
                            '<i class="fas fa-trash me-1"></i> Supprimer'
                        );


                    $('#deleteFonctionError')
                        .removeClass('d-none')
                        .html(
                            response.message ||
                            'Impossible de supprimer la fonction.'
                        );

                }

            },


            error: function (xhr) {


                $('#confirmDeleteFonction')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-trash me-1"></i> Supprimer'
                    );


                $('#deleteFonctionError')
                    .removeClass('d-none')
                    .html(
                        xhr.responseJSON?.message ||
                        'Impossible de supprimer la fonction.'
                    );

            }

        });

    });

});


// =====================================================
// NOUVELLE FONCTION
// =====================================================

function openCreateModal()
{

    $('#fonctionForm')[0].reset();

    $('#fonction_id').val('');

    $('#fonctionModalTitle').html(

        '<i class="fas fa-plus-circle me-2"></i> Nouvelle fonction'

    );

    clearErrors();

}


// =====================================================
// MODIFIER
// =====================================================

function editFonction(id)
{

    clearErrors();


    $.ajax({

        url:
            "{{ url('/edit-fonction') }}/" + id,

        type: 'GET',


        success: function (response) {


            if (response.status === 200) {


                let fonction =
                    response.fonction;


                $('#fonction_id')
                    .val(fonction.id);


                $('#nom_fonction')
                    .val(fonction.nom_fonction);


                $('#description')
                    .val(fonction.description);


                $('#fonctionModalTitle').html(

                    '<i class="fas fa-edit me-2"></i> Modifier la fonction'

                );


                $('#fonctionModal')
                    .modal('show');

            }

        },


        error: function () {


            showAlert(
                'danger',
                'Impossible de récupérer les informations de la fonction.'
            );

        }

    });

}


// =====================================================
// SUPPRIMER
// =====================================================

function deleteFonction(id, name)
{

    $('#deleteFonctionName')
        .text(name);


    $('#deleteFonctionError')
        .addClass('d-none')
        .html('');


    $('#confirmDeleteFonction')
        .data('id', id)
        .prop('disabled', false)
        .html(
            '<i class="fas fa-trash me-1"></i> Supprimer'
        );


    $('#deleteFonctionModal')
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


    $('#fonctionError')
        .removeClass('d-none')
        .html(message);

}


// =====================================================
// NETTOYER ERREURS
// =====================================================

function clearErrors()
{

    $('#fonctionError')
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
