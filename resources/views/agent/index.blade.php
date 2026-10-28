@extends('layouts.ap')

@section('content')

<div class="container-fluid">

    {{-- =====================================================
         EN-TÊTE
    ====================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-users me-2"></i>
                Gestion des agents
            </h4>

            <p class="text-muted mb-0">
                Gestion des agents, fonctions, postes, catégories et services
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#agentModal"
            onclick="openCreateModal()"
        >
            <i class="fas fa-plus me-1"></i>
            Nouvel agent
        </button>

    </div>


    {{-- =====================================================
         MESSAGE
    ====================================================== --}}
    <div id="alertMessage"></div>


    {{-- =====================================================
         TABLEAU DES AGENTS
    ====================================================== --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h5 class="mb-0">
                        Liste des agents
                    </h5>

                </div>

                <div class="col-md-6">

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>

                        <input
                            type="text"
                            id="searchAgent"
                            class="form-control"
                            placeholder="Rechercher un agent..."
                        >

                    </div>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-hover table-bordered align-middle mb-0"
                    id="agentsTable"
                >

                    <thead class="table-light">

                        <tr>

                            <th width="50">#</th>

                            <th>Matricule</th>

                            <th>Prénom</th>

                            <th>Nom</th>

                            <th>Fonction</th>

                            <th>Poste</th>

                            <th>Catégorie</th>

                            <th>Service</th>

                            <th>Direction</th>

                            <th width="150">Actions</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($agents as $agent)

                            <tr>

                                {{-- NUMÉRO --}}
                                <td>
                                    {{
                                        $loop->iteration +
                                        (($agents->currentPage() - 1) * $agents->perPage())
                                    }}
                                </td>


                                {{-- MATRICULE --}}
                                <td>

                                    <span class="badge bg-secondary">
                                        {{ $agent->matricule ?? '-' }}
                                    </span>

                                </td>


                                {{-- PRÉNOM --}}
                                <td>
                                    {{ $agent->prenom ?? '-' }}
                                </td>


                                {{-- NOM --}}
                                <td class="fw-bold">
                                    {{ $agent->nom ?? '-' }}
                                </td>


                                {{-- =====================================================
                                     FONCTION
                                ====================================================== --}}
                                <td>

                                    @if($agent->fonction)

                                        {{ $agent->fonction }}

                                    @else

                                        <span class="text-muted">
                                            Non définie
                                        </span>

                                    @endif

                                </td>


                                {{-- =====================================================
                                     POSTE
                                ====================================================== --}}
                                <td>

                                    @if($agent->poste)

                                        <span class="badge bg-primary">
                                            {{ $agent->poste }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            Non défini
                                        </span>

                                    @endif

                                </td>


                                {{-- =====================================================
                                     CATÉGORIE
                                ====================================================== --}}
                                <td>

                                    @if($agent->categorie)

                                        <span class="badge bg-info text-dark">
                                            {{ $agent->categorie }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            Non définie
                                        </span>

                                    @endif

                                </td>


                                {{-- =====================================================
                                     SERVICE
                                ====================================================== --}}
                                <td>

                                    @if($agent->serviceRelation)

                                        {{ $agent->serviceRelation->nom_service }}

                                    @elseif($agent->service)

                                        {{ $agent->service }}

                                    @else

                                        <span class="text-muted">
                                            Non défini
                                        </span>

                                    @endif

                                </td>


                                {{-- =====================================================
                                     DIRECTION
                                ====================================================== --}}
                                <td>

                                    @if(
                                        $agent->serviceRelation &&
                                        $agent->serviceRelation->directionRelation
                                    )

                                        {{
                                            $agent->serviceRelation
                                                ->directionRelation
                                                ->nom_direction
                                        }}

                                    @else

                                        <span class="text-muted">
                                            Non définie
                                        </span>

                                    @endif

                                </td>


                                {{-- =====================================================
                                     ACTIONS
                                ====================================================== --}}
                                <td>

                                    <div class="btn-group btn-group-sm">

                                        <button
                                            type="button"
                                            class="btn btn-warning"
                                            title="Modifier"
                                            onclick="editAgent({{ $agent->id }})"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>


                                        <button
                                            type="button"
                                            class="btn btn-danger"
                                            title="Supprimer"
                                            onclick="deleteAgent({{ $agent->id }})"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center py-5 text-muted"
                                >

                                    <i class="fas fa-users fa-2x mb-3"></i>

                                    <br>

                                    Aucun agent enregistré.

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
        @if($agents->hasPages())

            <div class="card-footer bg-white">

                {{ $agents->links() }}

            </div>

        @endif

    </div>

</div>



{{-- =========================================================
     MODAL AJOUT / MODIFICATION
========================================================= --}}

<div
    class="modal fade"
    id="agentModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">


            {{-- =================================================
                 HEADER MODAL
            ================================================== --}}
            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="agentModalTitle"
                >
                    Nouvel agent
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            {{-- =================================================
                 FORMULAIRE
            ================================================== --}}
            <form id="agentForm">

                @csrf

                <input
                    type="hidden"
                    id="agent_id"
                    name="agent_id"
                >


                <div class="modal-body">

                    <div class="row g-3">


                        {{-- =================================================
                             MATRICULE
                        ================================================== --}}
                        <div class="col-md-6">

                            <label
                                class="form-label"
                                for="matricule"
                            >
                                Matricule
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="matricule"
                                name="matricule"
                                required
                            >

                            <div
                                class="invalid-feedback"
                                id="error-matricule"
                            ></div>

                        </div>


                        {{-- =================================================
                             PRÉNOM
                        ================================================== --}}
                        <div class="col-md-6">

                            <label
                                class="form-label"
                                for="prenom"
                            >
                                Prénom
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="prenom"
                                name="prenom"
                                required
                            >

                            <div
                                class="invalid-feedback"
                                id="error-prenom"
                            ></div>

                        </div>


                        {{-- =================================================
                             NOM
                        ================================================== --}}
                        <div class="col-md-6">

                            <label
                                class="form-label"
                                for="nom"
                            >
                                Nom
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nom"
                                name="nom"
                                required
                            >

                            <div
                                class="invalid-feedback"
                                id="error-nom"
                            ></div>

                        </div>


                        {{-- =================================================
                             SERVICE
                        ================================================== --}}
                        <div class="col-md-6">

                            <label
                                class="form-label"
                                for="service"
                            >
                                Service
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                class="form-select"
                                id="service"
                                name="service"
                                required
                            >

                                <option value="">
                                    -- Sélectionner un service --
                                </option>

                                @foreach($services as $service)

                                    <option
                                        value="{{ $service->nom_service }}"
                                    >

                                        {{ $service->nom_service }}

                                        @if($service->directionRelation)

                                            -
                                            {{ $service->directionRelation->nom_direction }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            <div
                                class="invalid-feedback"
                                id="error-service"
                            ></div>

                        </div>


                        {{-- =================================================
                             FONCTION
                        ================================================== --}}
                        <div class="col-md-6">

                            <label
                                class="form-label"
                                for="fonction"
                            >
                                Fonction
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                class="form-select"
                                id="fonction"
                                name="fonction"
                                required
                            >

                                <option value="">
                                    -- Sélectionner une fonction --
                                </option>

                                @foreach($fonctions as $fonction)

                                    <option
                                        value="{{ $fonction->nom_fonction }}"
                                        data-categorie-id="{{ $fonction->categorie_id ?? '' }}"
                                        data-categorie-nom="{{ $fonction->categorie->nom_categorie ?? '' }}"
                                    >

                                        {{ $fonction->nom_fonction }}

                                    </option>

                                @endforeach

                            </select>

                            <div
                                class="invalid-feedback"
                                id="error-fonction"
                            ></div>

                        </div>


                        {{-- =================================================
                             POSTE
                        ================================================== --}}
                        <div class="col-md-6">

                            <label
                                class="form-label"
                                for="poste"
                            >
                                Poste
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                class="form-select"
                                id="poste"
                                name="poste"
                                required
                            >

                                <option value="">
                                    -- Sélectionner un poste --
                                </option>

                                @foreach($postes as $poste)

                                    <option
                                        value="{{ $poste->nom_poste }}"
                                    >
                                        {{ $poste->nom_poste }}
                                    </option>

                                @endforeach

                            </select>

                            <div
                                class="invalid-feedback"
                                id="error-poste"
                            ></div>

                        </div>


                        {{-- =================================================
                             CATÉGORIE AUTOMATIQUE
                        ================================================== --}}
                        <div class="col-md-6">

                            <label
                                class="form-label"
                                for="categorie_affichage"
                            >
                                Catégorie
                            </label>

                            <input
                                type="text"
                                class="form-control bg-light"
                                id="categorie_affichage"
                                readonly
                                placeholder="La catégorie sera déterminée automatiquement"
                            >

                            <div class="form-text">
                                La catégorie est automatiquement déterminée
                                selon la fonction sélectionnée.
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     FOOTER MODAL
                ================================================== --}}
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        <i class="fas fa-times me-1"></i>
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="saveAgentBtn"
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
     MODAL CONFIRMATION SUPPRESSION
========================================================= --}}

<div
    class="modal fade"
    id="deleteAgentModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

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


            <div class="modal-body text-center py-4">

                <div class="mb-3">

                    <i
                        class="fas fa-user-times text-danger"
                        style="font-size: 50px;"
                    ></i>

                </div>

                <h5 class="mb-3">
                    Voulez-vous vraiment supprimer cet agent ?
                </h5>

                <p class="text-muted mb-0">

                    Cette opération est définitive.

                    <br>

                    L'agent ne pourra plus être utilisé
                    pour de nouvelles missions.

                </p>

            </div>


            <div class="modal-footer justify-content-center">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    <i class="fas fa-times me-1"></i>
                    Annuler
                </button>

                <button
                    type="button"
                    class="btn btn-danger"
                    id="confirmDeleteBtn"
                    onclick="confirmerSuppressionAgent()"
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
    // RECHERCHE
    // =====================================================

    $('#searchAgent').on('keyup', function () {

        let value = $(this).val().toLowerCase();

        $('#agentsTable tbody tr').filter(function () {

            $(this).toggle(
                $(this).text().toLowerCase().indexOf(value) > -1
            );

        });

    });


    // =====================================================
    // FONCTION → CATÉGORIE
    // =====================================================

    $('#fonction').on('change', function () {

        let option = $(this).find('option:selected');

        let categorieNom =
            option.attr('data-categorie-nom') || '';


        if (categorieNom) {

            $('#categorie_affichage')
                .val(categorieNom);

        } else {

            $('#categorie_affichage')
                .val('');

        }

    });


    // =====================================================
    // SUBMIT FORMULAIRE
    // =====================================================

    $('#agentForm').submit(function (e) {

        e.preventDefault();


        let id = $('#agent_id').val();

        let url = '';

        let method = '';


        if (id) {

            url = "{{ url('/update-agent') }}/" + id;

            method = 'PUT';

        } else {

            url = "{{ route('agents.store') }}";

            method = 'POST';

        }


        // =================================================
        // DONNÉES ENVOYÉES À LARAVEL
        // =================================================

        let formData = {

            _token: "{{ csrf_token() }}",

            _method: method,

            matricule: $('#matricule').val(),

            prenom: $('#prenom').val(),

            nom: $('#nom').val(),

            fonction: $('#fonction').val(),

            poste: $('#poste').val(),

            service: $('#service').val()

        };


        clearErrors();


        $('#saveAgentBtn')
            .prop('disabled', true)
            .html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Enregistrement...'
            );


        $.ajax({

            url: url,

            type: 'POST',

            data: formData,


            success: function (response) {

                $('#saveAgentBtn')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-save me-1"></i> Enregistrer'
                    );


                if (
                    response.status === 200 ||
                    response.status === 201
                ) {

                    const modalElement =
                        document.getElementById('agentModal');

                    const modal =
                        bootstrap.Modal.getInstance(modalElement);

                    if (modal) {

                        modal.hide();

                    }


                    showAlert(
                        'success',
                        response.message ||
                        'Agent enregistré avec succès.'
                    );


                    setTimeout(function () {

                        location.reload();

                    }, 800);

                }

            },


            error: function (xhr) {

                $('#saveAgentBtn')
                    .prop('disabled', false)
                    .html(
                        '<i class="fas fa-save me-1"></i> Enregistrer'
                    );


                if (xhr.status === 422) {

                    let errors =
                        xhr.responseJSON.errors || {};

                    displayErrors(errors);

                } else {

                    showAlert(
                        'danger',
                        xhr.responseJSON?.message ||
                        'Une erreur est survenue lors de l’opération.'
                    );

                }

            }

        });

    });

});


// =====================================================
// OUVRIR MODAL CRÉATION
// =====================================================

function openCreateModal()
{
    $('#agentForm')[0].reset();

    $('#agent_id').val('');

    $('#categorie_affichage').val('');

    $('#poste').val('');

    $('#agentModalTitle').text(
        'Nouvel agent'
    );


    $('#saveAgentBtn')
        .prop('disabled', false)
        .html(
            '<i class="fas fa-save me-1"></i> Enregistrer'
        );


    clearErrors();
}


// =====================================================
// MODIFIER AGENT
// =====================================================

function editAgent(id)
{
    clearErrors();


    $.ajax({

        url: "{{ url('/edit-agent') }}/" + id,

        type: 'GET',


        success: function (response) {

            if (response.status === 200) {

                let agent = response.agent;


                // ==========================================
                // IDENTIFIANT
                // ==========================================

                $('#agent_id')
                    .val(agent.id);


                // ==========================================
                // INFORMATIONS
                // ==========================================

                $('#matricule')
                    .val(agent.matricule);

                $('#prenom')
                    .val(agent.prenom);

                $('#nom')
                    .val(agent.nom);


                // ==========================================
                // FONCTION
                // ==========================================

                $('#fonction')
                    .val(agent.fonction)
                    .trigger('change');


                // ==========================================
                // POSTE
                // ==========================================

                $('#poste')
                    .val(agent.poste);


                // ==========================================
                // SERVICE
                // ==========================================

                $('#service')
                    .val(agent.service);


                // ==========================================
                // TITRE
                // ==========================================

                $('#agentModalTitle')
                    .text('Modifier l’agent');


                // ==========================================
                // AFFICHER LE MODAL
                // ==========================================

                const modalElement =
                    document.getElementById('agentModal');

                const modal =
                    bootstrap.Modal.getOrCreateInstance(
                        modalElement
                    );

                modal.show();

            }

        },


        error: function () {

            showAlert(
                'danger',
                'Impossible de récupérer les informations de l’agent.'
            );

        }

    });

}


// =====================================================
// SUPPRIMER AGENT
// =====================================================

let agentIdASupprimer = null;


function deleteAgent(id)
{
    agentIdASupprimer = id;


    const modalElement =
        document.getElementById('deleteAgentModal');


    const modal =
        bootstrap.Modal.getOrCreateInstance(
            modalElement
        );


    modal.show();
}


// =====================================================
// CONFIRMER SUPPRESSION
// =====================================================

function confirmerSuppressionAgent()
{
    if (!agentIdASupprimer) {

        return;

    }


    const id =
        agentIdASupprimer;


    $('#confirmDeleteBtn')
        .prop('disabled', true)
        .html(
            '<span class="spinner-border spinner-border-sm me-1"></span> Suppression...'
        );


    $.ajax({

        url: "{{ url('/delete-agent') }}/" + id,

        type: 'POST',

        data: {

            _token: "{{ csrf_token() }}",

            _method: 'DELETE'

        },


        success: function (response) {

            $('#confirmDeleteBtn')
                .prop('disabled', false)
                .html(
                    '<i class="fas fa-trash me-1"></i> Supprimer'
                );


            if (response.status === 200) {

                const modalElement =
                    document.getElementById(
                        'deleteAgentModal'
                    );


                const modal =
                    bootstrap.Modal.getInstance(
                        modalElement
                    );


                if (modal) {

                    modal.hide();

                }


                showAlert(
                    'success',
                    response.message ||
                    'Agent supprimé avec succès.'
                );


                setTimeout(function () {

                    location.reload();

                }, 800);

            } else {

                showAlert(
                    'danger',
                    response.message ||
                    'Impossible de supprimer l’agent.'
                );

            }

        },


        error: function (xhr) {

            $('#confirmDeleteBtn')
                .prop('disabled', false)
                .html(
                    '<i class="fas fa-trash me-1"></i> Supprimer'
                );


            let message =
                'Une erreur est survenue lors de la suppression.';


            if (
                xhr.responseJSON &&
                xhr.responseJSON.message
            ) {

                message =
                    xhr.responseJSON.message;

            }


            showAlert(
                'danger',
                message
            );

        }

    });

}


// =====================================================
// AFFICHER LES ERREURS
// =====================================================

function displayErrors(errors)
{
    $.each(errors, function (field, messages) {

        let input =
            $('#' + field);


        if (input.length) {

            input.addClass('is-invalid');

        }


        let errorElement =
            $('#error-' + field);


        if (errorElement.length) {

            errorElement.text(
                messages[0]
            );

        }

    });
}


// =====================================================
// NETTOYER LES ERREURS
// =====================================================

function clearErrors()
{
    $('.is-invalid')
        .removeClass('is-invalid');

    $('.invalid-feedback')
        .text('');
}


// =====================================================
// ALERT
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


    $('#alertMessage').html(html);
}

</script>

@endsection