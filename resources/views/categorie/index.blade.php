@extends('layouts.ap')

@section('content')

<div class="container-fluid">

    {{-- =====================================================
         EN-TÊTE
    ====================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="fas fa-layer-group me-2"></i>
                Gestion des catégories
            </h4>

            <p class="text-muted mb-0">
                Gestion des catégories des agents de la CANAM
            </p>

        </div>


        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#categorieModal"
            onclick="openCreateModal()"
        >

            <i class="fas fa-plus me-1"></i>

            Nouvelle catégorie

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


    {{-- =====================================================
         LISTE
    ====================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h5 class="mb-0">

                        <i class="fas fa-list me-2"></i>

                        Liste des catégories

                    </h5>

                </div>


                <div class="col-md-6">

                    <div class="input-group">

                        <span class="input-group-text">

                            <i class="fas fa-search"></i>

                        </span>


                        <input
                            type="text"
                            id="searchCategorie"
                            class="form-control"
                            placeholder="Rechercher une catégorie..."
                        >

                    </div>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-hover table-bordered align-middle mb-0"
                    id="categoriesTable"
                >

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th width="120">
                                Code
                            </th>

                            <th>
                                Catégorie
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

                        @forelse($categories as $categorie)

                            <tr>

                                <td>

                                    {{ $loop->iteration +
                                        (($categories->currentPage() - 1)
                                        * $categories->perPage()) }}

                                </td>


                                <td>

                                    <span class="badge bg-primary">

                                        {{ $categorie->code }}

                                    </span>

                                </td>


                                <td>

                                    <strong>

                                        {{ $categorie->nom_categorie }}

                                    </strong>

                                </td>


                                <td>

                                    {{ $categorie->description ?? '-' }}

                                </td>


                                <td class="text-center">

                                    <span class="badge bg-info text-dark">

                                        {{ $categorie->agents_count }}

                                    </span>

                                </td>


                                <td class="text-center">

                                    <div class="btn-group btn-group-sm">


                                        <button
                                            type="button"
                                            class="btn btn-warning"
                                            title="Modifier"
                                            onclick="editCategorie({{ $categorie->id }})"
                                        >

                                            <i class="fas fa-edit"></i>

                                        </button>


                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                            title="Supprimer"
                                            onclick="deleteCategorie(
                                                {{ $categorie->id }},
                                                @js($categorie->nom_categorie)
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
                                    colspan="6"
                                    class="text-center py-5 text-muted"
                                >

                                    <i class="fas fa-layer-group fa-2x mb-3"></i>

                                    <br>

                                    Aucune catégorie enregistrée.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($categories->hasPages())

            <div class="card-footer bg-white">

                {{ $categories->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =====================================================
     MODALE AJOUT / MODIFICATION
====================================================== --}}

<div
    class="modal fade"
    id="categorieModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">


            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="categorieModalTitle"
                >

                    <i class="fas fa-plus-circle me-2"></i>

                    Nouvelle catégorie

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form id="categorieForm">

                @csrf


                <input
                    type="hidden"
                    id="categorie_id"
                    name="categorie_id"
                >


                <div class="modal-body">


                    <div
                        id="categorieError"
                        class="alert alert-danger d-none"
                    ></div>


                    <div class="row g-3">


                        <div class="col-md-4">

                            <label
                                class="form-label"
                                for="code"
                            >

                                Code

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                id="code"
                                name="code"
                                maxlength="10"
                                placeholder="Ex : I"
                                required
                            >

                        </div>


                        <div class="col-md-8">

                            <label
                                class="form-label"
                                for="nom_categorie"
                            >

                                Nom de la catégorie

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                id="nom_categorie"
                                name="nom_categorie"
                                placeholder="Ex : Catégorie I"
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
                                placeholder="Description de la catégorie..."
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
                        id="saveCategorieBtn"
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
    id="deleteCategorieModal"
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

                    Voulez-vous vraiment supprimer la catégorie :

                </p>


                <h5
                    id="deleteCategorieName"
                    class="text-danger"
                ></h5>


                <div
                    id="deleteCategorieError"
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
                    id="confirmDeleteCategorie"
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

    $('#categorieForm').submit(function (e) {

        e.preventDefault();


        let id =
            $('#categorie_id').val();


        let url;

        let method;


        if (id) {

            url =
                "{{ url('/update-categorie') }}/" + id;

            method = 'PUT';

        } else {

            url =
                "{{ route('categories.store') }}";

            method = 'POST';

        }


        let formData = {

            _token:
                "{{ csrf_token() }}",

            _method:
                method,

            code:
                $('#code').val(),

            nom_categorie:
                $('#nom_categorie').val(),

            description:
                $('#description').val()

        };


        clearErrors();


        $('#saveCategorieBtn')
            .prop('disabled', true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...'
            );


        $.ajax({

            url: url,

            type: 'POST',

            data: formData,


            success: function (response) {


                $('#saveCategorieBtn')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-save me-1"></i> Enregistrer'
                    );


                if (
                    response.status === 200 ||
                    response.status === 201
                ) {


                    $('#categorieModal')
                        .modal('hide');


                    showAlert(
                        'success',
                        response.message ||
                        'Catégorie enregistrée avec succès.'
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


                $('#saveCategorieBtn')
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

    $('#searchCategorie').on('keyup', function () {


        let value =
            $(this).val().toLowerCase();


        $('#categoriesTable tbody tr').filter(function () {


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

    $('#confirmDeleteCategorie').click(function () {


        let id =
            $(this).data('id');


        if (!id) {

            return;

        }


        $('#confirmDeleteCategorie')
            .prop('disabled', true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Suppression...'
            );


        $.ajax({

            url:
                "{{ url('/delete-categorie') }}/" + id,

            type: 'POST',

            data: {

                _token:
                    "{{ csrf_token() }}",

                _method:
                    'DELETE'

            },


            success: function (response) {


                if (response.status === 200) {


                    $('#deleteCategorieModal')
                        .modal('hide');


                    showAlert(
                        'success',
                        response.message ||
                        'Catégorie supprimée avec succès.'
                    );


                    setTimeout(function () {

                        location.reload();

                    }, 800);


                } else {


                    $('#confirmDeleteCategorie')
                        .prop('disabled', false)
                        .html(
                            '<i class="fas fa-trash me-1"></i> Supprimer'
                        );


                    $('#deleteCategorieError')
                        .removeClass('d-none')
                        .html(
                            response.message ||
                            'Impossible de supprimer la catégorie.'
                        );

                }

            },


            error: function (xhr) {


                $('#confirmDeleteCategorie')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-trash me-1"></i> Supprimer'
                    );


                $('#deleteCategorieError')
                    .removeClass('d-none')
                    .html(
                        xhr.responseJSON?.message ||
                        'Impossible de supprimer la catégorie.'
                    );

            }

        });

    });

});


// =====================================================
// NOUVELLE CATÉGORIE
// =====================================================

function openCreateModal()
{

    $('#categorieForm')[0].reset();

    $('#categorie_id').val('');

    $('#categorieModalTitle').html(

        '<i class="fas fa-plus-circle me-2"></i> Nouvelle catégorie'

    );

    clearErrors();

}


// =====================================================
// MODIFIER
// =====================================================

function editCategorie(id)
{

    clearErrors();


    $.ajax({

        url:
            "{{ url('/edit-categorie') }}/" + id,

        type: 'GET',


        success: function (response) {


            if (response.status === 200) {


                let categorie =
                    response.categorie;


                $('#categorie_id')
                    .val(categorie.id);


                $('#code')
                    .val(categorie.code);


                $('#nom_categorie')
                    .val(categorie.nom_categorie);


                $('#description')
                    .val(categorie.description);


                $('#categorieModalTitle').html(

                    '<i class="fas fa-edit me-2"></i> Modifier la catégorie'

                );


                $('#categorieModal')
                    .modal('show');

            }

        },


        error: function () {


            showAlert(
                'danger',
                'Impossible de récupérer les informations de la catégorie.'
            );

        }

    });

}


// =====================================================
// SUPPRIMER
// =====================================================

function deleteCategorie(id, name)
{

    $('#deleteCategorieName')
        .text(name);


    $('#deleteCategorieError')
        .addClass('d-none')
        .html('');


    $('#confirmDeleteCategorie')
        .data('id', id)
        .prop('disabled', false)
        .html(
            '<i class="fas fa-trash me-1"></i> Supprimer'
        );


    $('#deleteCategorieModal')
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


    $('#categorieError')
        .removeClass('d-none')
        .html(message);

}


// =====================================================
// NETTOYER ERREURS
// =====================================================

function clearErrors()
{

    $('#categorieError')
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
