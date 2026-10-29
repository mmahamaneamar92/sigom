@extends('layouts.ap')

@section('content')

<div class="container-fluid">

    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-car me-2"></i>
                Gestion des voitures
            </h4>

            <p class="text-muted mb-0">
                Gestion du parc automobile de la CANAM
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#voitureModal"
            onclick="resetForm()"
        >
            <i class="fas fa-plus me-1"></i>
            Ajouter une voiture
        </button>

    </div>


    <!-- Alert -->
    <div id="alertMessage"></div>


    <!-- Tableau -->
    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="row align-items-center">

                <div class="col-md-6">
                    <h5 class="mb-0">
                        Liste des voitures
                    </h5>
                </div>

                <div class="col-md-6">

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>

                        <input
                            type="text"
                            id="searchVoiture"
                            class="form-control"
                            placeholder="Rechercher une voiture..."
                        >

                    </div>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>#</th>
                            <th>Immatriculation</th>
                            <th>Marque</th>
                            <th>Modèle</th>
                            <th>Année</th>
                            <th>Carburant</th>
                            <th>Kilométrage</th>
                            <th>État</th>
                            <th>Disponibilité</th>
                            <th width="130">Actions</th>
                        </tr>

                    </thead>

                    <tbody id="voitureTable">

                        @forelse($voitures as $voiture)

                            <tr>

                                <td>
                                    {{ $voitures->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $voiture->immatriculation }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $voiture->marque }}
                                </td>

                                <td>
                                    {{ $voiture->modele }}
                                </td>

                                <td>
                                    {{ $voiture->annee ?? '-' }}
                                </td>

                                <td>

                                    @if($voiture->type_carburant === 'diesel')
                                        <span class="badge bg-dark">
                                            Diesel
                                        </span>

                                    @elseif($voiture->type_carburant === 'essence')
                                        <span class="badge bg-warning text-dark">
                                            Essence
                                        </span>

                                    @elseif($voiture->type_carburant === 'hybride')
                                        <span class="badge bg-success">
                                            Hybride
                                        </span>

                                    @else
                                        <span class="badge bg-info">
                                            Électrique
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    {{ number_format($voiture->kilometrage, 0, ',', ' ') }}
                                    km
                                </td>

                                <td>

                                    @if($voiture->etat === 'bon')
                                        <span class="badge bg-success">
                                            Bon
                                        </span>

                                    @elseif($voiture->etat === 'moyen')
                                        <span class="badge bg-warning text-dark">
                                            Moyen
                                        </span>

                                    @elseif($voiture->etat === 'mauvais')
                                        <span class="badge bg-danger">
                                            Mauvais
                                        </span>

                                    @else
                                        <span class="badge bg-secondary">
                                            Hors service
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    @if($voiture->disponible)
                                        <span class="badge bg-success">
                                            Disponible
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Indisponible
                                        </span>
                                    @endif

                                </td>

                                <td>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-warning"
                                        onclick="editVoiture({{ $voiture->id }})"
                                        title="Modifier"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger"
                                        onclick="deleteVoiture({{ $voiture->id }})"
                                        title="Supprimer"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10" class="text-center py-4">

                                    <i class="fas fa-car fa-2x text-muted mb-2"></i>

                                    <p class="text-muted mb-0">
                                        Aucune voiture enregistrée.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <!-- Pagination -->
        @if($voitures->hasPages())

            <div class="card-footer bg-white">

                {{ $voitures->links() }}

            </div>

        @endif

    </div>

</div>


<!-- ===================================================== -->
<!-- MODAL AJOUT / MODIFICATION -->
<!-- ===================================================== -->

<div
    class="modal fade"
    id="voitureModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="voitureModalTitle">
                    Ajouter une voiture
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form id="voitureForm">

                @csrf

                <input
                    type="hidden"
                    id="voiture_id"
                >

                <div class="modal-body">

                    <div class="row">

                        <!-- Immatriculation -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Immatriculation
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="immatriculation"
                                name="immatriculation"
                                class="form-control"
                                placeholder="Ex : AB-1234-MD"
                                required
                            >

                            <div
                                class="invalid-feedback"
                                id="error_immatriculation"
                            ></div>

                        </div>


                        <!-- Marque -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Marque
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="marque"
                                name="marque"
                                class="form-control"
                                placeholder="Ex : Toyota"
                                required
                            >

                            <div
                                class="invalid-feedback"
                                id="error_marque"
                            ></div>

                        </div>


                        <!-- Modèle -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Modèle
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="modele"
                                name="modele"
                                class="form-control"
                                placeholder="Ex : Hilux"
                                required
                            >

                            <div
                                class="invalid-feedback"
                                id="error_modele"
                            ></div>

                        </div>


                        <!-- Année -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Année
                            </label>

                            <input
                                type="number"
                                id="annee"
                                name="annee"
                                class="form-control"
                                min="1900"
                                max="{{ date('Y') }}"
                                placeholder="{{ date('Y') }}"
                            >

                            <div
                                class="invalid-feedback"
                                id="error_annee"
                            ></div>

                        </div>


                        <!-- Couleur -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Couleur
                            </label>

                            <input
                                type="text"
                                id="couleur"
                                name="couleur"
                                class="form-control"
                                placeholder="Ex : Blanc"
                            >

                        </div>


                        <!-- Carburant -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Type de carburant
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="type_carburant"
                                name="type_carburant"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- Sélectionner --
                                </option>

                                <option value="diesel">
                                    Diesel
                                </option>

                                <option value="essence">
                                    Essence
                                </option>

                                <option value="hybride">
                                    Hybride
                                </option>

                                <option value="electrique">
                                    Électrique
                                </option>

                            </select>

                            <div
                                class="invalid-feedback"
                                id="error_type_carburant"
                            ></div>

                        </div>


                        <!-- Kilométrage -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Kilométrage
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    id="kilometrage"
                                    name="kilometrage"
                                    class="form-control"
                                    min="0"
                                    value="0"
                                    required
                                >

                                <span class="input-group-text">
                                    km
                                </span>

                            </div>

                            <div
                                class="invalid-feedback"
                                id="error_kilometrage"
                            ></div>

                        </div>


                        <!-- État -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                État
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="etat"
                                name="etat"
                                class="form-select"
                                required
                            >

                                <option value="bon">
                                    Bon
                                </option>

                                <option value="moyen">
                                    Moyen
                                </option>

                                <option value="mauvais">
                                    Mauvais
                                </option>

                                <option value="hors_service">
                                    Hors service
                                </option>

                            </select>

                        </div>


                        <!-- Date mise en service -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Date de mise en service
                            </label>

                            <input
                                type="date"
                                id="date_mise_service"
                                name="date_mise_service"
                                class="form-control"
                            >

                        </div>


                        <!-- Disponibilité -->
                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Disponibilité
                            </label>

                            <select
                                id="disponible"
                                name="disponible"
                                class="form-select"
                            >

                                <option value="1">
                                    Disponible
                                </option>

                                <option value="0">
                                    Indisponible
                                </option>

                            </select>

                        </div>


                        <!-- Observation -->
                        <div class="col-md-12 mb-3">

                            <label class="form-label">
                                Observation
                            </label>

                            <textarea
                                id="observation"
                                name="observation"
                                class="form-control"
                                rows="3"
                                placeholder="Observation éventuelle..."
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
                        id="btnSaveVoiture"
                    >
                        <i class="fas fa-save me-1"></i>
                        Enregistrer
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- ===================================================== -->
<!-- MODAL SUPPRESSION -->
<!-- ===================================================== -->

<div
    class="modal fade"
    id="deleteVoitureModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title">
                    Confirmation
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body text-center">

                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>

                <p class="mb-0">
                    Voulez-vous vraiment supprimer cette voiture ?
                </p>

                <small class="text-muted">
                    Cette opération est irréversible.
                </small>

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
                    id="confirmDeleteVoiture"
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

let voitureIdToDelete = null;


/*
|--------------------------------------------------------------------------
| Réinitialiser le formulaire
|--------------------------------------------------------------------------
*/

function resetForm()
{
    $('#voitureForm')[0].reset();

    $('#voiture_id').val('');

    $('#voitureModalTitle').text('Ajouter une voiture');

    $('#kilometrage').val(0);

    $('#disponible').val('1');

    $('#etat').val('bon');

    $('.is-invalid').removeClass('is-invalid');

    $('.invalid-feedback').text('');
}


/*
|--------------------------------------------------------------------------
| Afficher une alerte
|--------------------------------------------------------------------------
*/

function showAlert(type, message)
{
    $('#alertMessage').html(`
        <div class="alert alert-${type} alert-dismissible fade show">
            ${message}
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    `);

    setTimeout(function() {
        $('.alert').alert('close');
    }, 4000);
}


/*
|--------------------------------------------------------------------------
| Enregistrer / Modifier
|--------------------------------------------------------------------------
*/

$('#voitureForm').submit(function(e)
{
    e.preventDefault();

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    let id = $('#voiture_id').val();

    let url = id
        ? `/update-voiture/${id}`
        : `{{ route('voitures.store') }}`;

    let method = id ? 'PUT' : 'POST';

    let formData = {
        _token: '{{ csrf_token() }}',

        immatriculation: $('#immatriculation').val(),
        marque: $('#marque').val(),
        modele: $('#modele').val(),
        annee: $('#annee').val(),
        couleur: $('#couleur').val(),
        type_carburant: $('#type_carburant').val(),
        kilometrage: $('#kilometrage').val(),
        etat: $('#etat').val(),
        disponible: $('#disponible').val(),
        date_mise_service: $('#date_mise_service').val(),
        observation: $('#observation').val()
    };

    if (method === 'PUT') {
        formData._method = 'PUT';
    }

    $('#btnSaveVoiture')
        .prop('disabled', true)
        .html('<i class="fas fa-spinner fa-spin me-1"></i> Enregistrement...');


    $.ajax({

        url: url,

        type: 'POST',

        data: formData,

        success: function(response)
        {
            if (response.status === 200) {

                $('#voitureModal').modal('hide');

                showAlert(
                    'success',
                    response.message
                );

                setTimeout(function() {
                    location.reload();
                }, 800);
            }
        },

        error: function(xhr)
        {
            if (xhr.status === 422) {

                let errors = xhr.responseJSON.errors;

                $.each(errors, function(field, messages)
                {
                    $('#' + field).addClass('is-invalid');

                    $('#error_' + field)
                        .text(messages[0]);
                });

            } else {

                showAlert(
                    'danger',
                    'Une erreur est survenue.'
                );
            }
        },

        complete: function()
        {
            $('#btnSaveVoiture')
                .prop('disabled', false)
                .html('<i class="fas fa-save me-1"></i> Enregistrer');
        }

    });

});


/*
|--------------------------------------------------------------------------
| Modifier une voiture
|--------------------------------------------------------------------------
*/

function editVoiture(id)
{
    $.ajax({

        url: `/edit-voiture/${id}`,

        type: 'GET',

        success: function(response)
        {
            if (response.status === 200) {

                let v = response.voiture;

                $('#voiture_id').val(v.id);

                $('#immatriculation').val(v.immatriculation);
                $('#marque').val(v.marque);
                $('#modele').val(v.modele);
                $('#annee').val(v.annee);
                $('#couleur').val(v.couleur);
                $('#type_carburant').val(v.type_carburant);
                $('#kilometrage').val(v.kilometrage);
                $('#etat').val(v.etat);
                $('#disponible').val(v.disponible ? '1' : '0');

                if (v.date_mise_service) {

                    $('#date_mise_service').val(
                        v.date_mise_service.substring(0, 10)
                    );

                } else {

                    $('#date_mise_service').val('');

                }

                $('#observation').val(v.observation);

                $('#voitureModalTitle')
                    .text('Modifier la voiture');

                $('#voitureModal').modal('show');
            }
        },

        error: function()
        {
            showAlert(
                'danger',
                'Impossible de récupérer les informations de la voiture.'
            );
        }

    });
}


/*
|--------------------------------------------------------------------------
| Suppression
|--------------------------------------------------------------------------
*/

function deleteVoiture(id)
{
    voitureIdToDelete = id;

    $('#deleteVoitureModal').modal('show');
}


/*
|--------------------------------------------------------------------------
| Confirmation suppression
|--------------------------------------------------------------------------
*/

$('#confirmDeleteVoiture').click(function()
{
    if (!voitureIdToDelete) {
        return;
    }

    $.ajax({

        url: `/delete-voiture/${voitureIdToDelete}`,

        type: 'DELETE',

        data: {
            _token: '{{ csrf_token() }}'
        },

        success: function(response)
        {
            $('#deleteVoitureModal').modal('hide');

            if (response.status === 200) {

                showAlert(
                    'success',
                    response.message
                );

                setTimeout(function() {
                    location.reload();
                }, 800);

            } else {

                showAlert(
                    'danger',
                    response.message
                );
            }
        },

        error: function(xhr)
        {
            $('#deleteVoitureModal').modal('hide');

            let message =
                'Impossible de supprimer cette voiture.';

            if (xhr.responseJSON &&
                xhr.responseJSON.message) {

                message = xhr.responseJSON.message;
            }

            showAlert(
                'danger',
                message
            );
        }

    });
});


/*
|--------------------------------------------------------------------------
| Recherche dans le tableau
|--------------------------------------------------------------------------
*/

$('#searchVoiture').on('keyup', function()
{
    let value = $(this).val().toLowerCase();

    $('#voitureTable tr').filter(function()
    {
        $(this).toggle(
            $(this).text().toLowerCase().indexOf(value) > -1
        );
    });
});

</script>

@endsection