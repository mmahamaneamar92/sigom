@extends('layouts.ap')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-file-earmark-plus me-2"></i>
                Créer une mission
            </h4>

            <p class="text-muted mb-0">
                Création d'un nouvel ordre de mission
            </p>
        </div>

        <a href="{{ route('missions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Retour
        </a>

    </div>


    {{-- =========================================================
         ALERTES
    ========================================================== --}}

    <div id="alertContainer"></div>


    {{-- =========================================================
         FORMULAIRE
    ========================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0">
                <i class="bi bi-info-circle me-2"></i>
                Informations de la mission
            </h5>
        </div>


        <div class="card-body">

            <form id="missionForm" action="{{ route('missions.store') }}" method="POST">
                @csrf


                {{-- =================================================
                     INFORMATIONS GÉNÉRALES
                ================================================== --}}

                <div class="row g-3">

                    {{-- Objet --}}
                    <div class="col-md-8">

                        <label for="objet" class="form-label fw-semibold">
                            Objet de la mission
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text" id="objet" name="objet" class="form-control" value="{{ old('objet', $initiation->objet ?? '') }}" required>

                    </div>

                    @if ($initiation)
                    <input type="hidden" name="mission_initiation_id" value="{{ $initiation->id }}">

                    <div class="alert alert-info">
                        <strong>Initiation INIT-{{ $initiation->id }}</strong>
                        <p class="mb-2">
                            L’objet de la mission a été prérempli à partir de la demande.
                        </p>

                        @if ($initiation->tdr_path)
                        <a href="{{ route('mission-initiations.tdr', $initiation->id) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="fas fa-file-alt"></i>
                            Consulter le TDR
                        </a>
                        @endif
                    </div>
                    @endif



                    {{-- Direction --}}
                    <div class="col-md-4">
                        <label for="direction" class="form-label fw-semibold">
                            Direction <span class="text-danger">*</span>
                        </label>

                        @php
                        $directionSelectionnee = old('direction');

                        if (
                        !$directionSelectionnee &&
                        isset($initiation) &&
                        $initiation
                        ) {
                        $directionSelectionnee =
                        $initiation->demandeur?->direction?->nom_direction;
                        }

                        if (!$directionSelectionnee && auth()->check()) {
                        $directionSelectionnee =
                        auth()->user()->direction?->nom_direction;
                        }
                        @endphp

                        <select class="form-select" id="direction" name="direction" required>

                            <option value="">-- Sélectionner une direction --</option>

                            @foreach($directions as $direction)
                            <option value="{{ $direction->nom_direction }}" @selected( $directionSelectionnee===$direction->nom_direction
                                )>
                                {{ $direction->nom_direction }}
                            </option>
                            @endforeach

                        </select>
                    </div>



                    {{-- Type --}}
                    <div class="col-md-4">
                        <label for="type" class="form-label fw-semibold">
                            Type de mission
                            <span class="text-danger">*</span>
                        </label>

                        <select class="form-select" id="type" name="type" required>
                            <option value="">-- Sélectionner --</option>

                            <option value="nationale" {{ old('type', $initiation->type ?? '') === 'nationale' ? 'selected' : '' }}>
                                Nationale
                            </option>

                            <option value="internationale" {{ old('type', $initiation->type ?? '') === 'internationale' ? 'selected' : '' }}>
                                Internationale
                            </option>
                        </select>
                    </div>




                    {{-- Pays --}}
                    <div class="col-md-4">
                        <label for="pays_id" class="form-label fw-semibold">
                            Pays
                            <span class="text-danger">*</span>
                        </label>

                        @php
                        $paysSelectionne = old(
                        'pays_id',
                        $initiation->pays_id
                        ?? $pays->firstWhere('code_iso', 'ML')?->id
                        ?? ''
                        );
                        @endphp

                        <select class="form-select" id="pays_id" name="pays_id" required disabled>
                            <option value="">
                                -- Sélectionner le type de mission --
                            </option>

                            @foreach($pays as $pay)
                            <option value="{{ $pay->id }}" data-code="{{ $pay->code_iso ?? '' }}" data-nom="{{ $pay->nom }}" {{ (string) $paysSelectionne === (string) $pay->id ? 'selected' : '' }}>
                                {{ $pay->nom }}
                            </option>
                            @endforeach
                        </select>

                        <div class="form-text" id="paysHelp">
                            Sélectionnez d'abord le type de mission.
                        </div>
                    </div>




                    {{-- Région --}}
                    <div class="col-md-4">
                        <label for="region_id" class="form-label fw-semibold">
                            Région
                            <span class="text-danger">*</span>
                        </label>

                        <select class="form-select" id="region_id" name="region_id" required disabled>
                            <option value="">-- Sélectionner d'abord le pays --</option>
                        </select>

                        <div class="form-text">
                            Les régions disponibles dépendent du pays sélectionné.
                        </div>
                    </div>


                    {{-- Destination : cercle --}}
                    <div class="col-md-8">
                        <label for="destination" class="form-label fw-semibold">
                            Cercle de destination
                            <span class="text-danger">*</span>
                        </label>

                        <select class="form-select" id="destination" name="destination" required disabled>
                            <option value="">-- Sélectionner d'abord la région --</option>
                        </select>

                        <div class="form-text" id="cercle-message">
                            Sélectionne une région pour afficher ses cercles.
                        </div>
                    </div>



                    {{-- Date départ --}}
                    <div class="col-md-4">

                        <label for="date_depart" class="form-label fw-semibold">
                            Date de départ
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date" class="form-control" id="date_depart" name="date_depart" required>

                    </div>


                    {{-- Date retour --}}
                    <div class="col-md-4">

                        <label for="date_retour" class="form-label fw-semibold">
                            Date de retour
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date" class="form-control" id="date_retour" name="date_retour" required>

                    </div>


                    {{-- Durée --}}
                    <div class="col-md-4">

                        <label for="duree" class="form-label fw-semibold">
                            Durée
                        </label>

                        <div class="input-group">

                            <input type="text" class="form-control bg-light" id="duree" name="duree" readonly placeholder="Calcul automatique">

                            <span class="input-group-text">
                                jour(s)
                            </span>

                        </div>

                        <div class="form-text">
                            Calculée automatiquement, dates incluses.
                        </div>

                    </div>

                </div>


                <hr class="my-4">


                {{-- =================================================
                     PARTICIPANTS
                ================================================== --}}

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div>
                        <h5 class="mb-1">
                            <i class="bi bi-people me-2"></i>
                            Participants à la mission
                        </h5>

                        <small class="text-muted">
                            Les frais et indemnités seront calculés automatiquement
                            selon la catégorie de chaque agent.
                        </small>
                    </div>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agentModal">

                        <i class="bi bi-person-plus me-1"></i>
                        Ajouter un agent

                    </button>

                </div>


                {{-- Tableau participants --}}
                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th style="width: 50px;">#</th>

                                <th>Matricule</th>

                                <th>Prénom</th>

                                <th>Nom</th>

                                <th>Fonction</th>

                                <th>Poste</th>

                                <th>Catégorie</th>

                                <th>Service</th>

                                <th style="width: 80px;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody id="participantsTableBody">

                            <tr id="emptyParticipantsRow">

                                <td colspan="9" class="text-center text-muted py-4">

                                    <i class="bi bi-people fs-3 d-block mb-2"></i>

                                    Aucun participant sélectionné.

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                {{-- =================================================
                     BOUTONS
                ================================================== --}}

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a href="{{ route('missions.index') }}" class="btn btn-outline-secondary">

                        <i class="bi bi-x-circle me-1"></i>
                        Annuler

                    </a>


                    <button type="submit" class="btn btn-primary" id="submitBtn">

                        <span id="submitText">
                            <i class="bi bi-check-circle me-1"></i>
                            Créer la mission
                        </span>

                        <span id="submitLoader" class="d-none">

                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>

                            Création en cours...

                        </span>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- =============================================================
     MODAL SÉLECTION DES AGENTS
============================================================== --}}

<div class="modal fade" id="agentModal" tabindex="-1" aria-labelledby="agentModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="agentModalLabel">

                    <i class="bi bi-person-plus me-2"></i>
                    Sélectionner les participants

                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer">
                </button>

            </div>


            <div class="modal-body">

                {{-- Recherche --}}
                <div class="mb-3">

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" class="form-control" id="agentSearch" placeholder="Rechercher par matricule, prénom, nom, fonction ou service...">

                    </div>

                </div>


                {{-- Liste agents --}}
                <div class="table-responsive">

                    <table class="table table-hover table-bordered align-middle">

                        <thead class="table-light">

                            <tr>

                                <th style="width: 50px;"></th>

                                <th>Matricule</th>

                                <th>Prénom</th>

                                <th>Nom</th>

                                <th>Fonction</th>

                                <th>Poste</th>

                                <th>Catégorie</th>

                                <th>Service</th>

                            </tr>

                        </thead>


                        <tbody id="agentsModalBody">

                            @forelse($agents as $agent)

                            <tr class="agent-row" data-agent-id="{{ $agent->id }}" data-search="{{ strtolower(
                                        ($agent->matricule ?? '') . ' ' .
                                        ($agent->prenom ?? '') . ' ' .
                                        ($agent->nom ?? '') . ' ' .
                                        ($agent->fonction ?? '') . ' ' .
                                        ($agent->poste ?? '') . ' ' .
                                        ($agent->categorie ?? '') . ' ' .
                                        ($agent->service ?? '')
                                    ) }}">

                                <td class="text-center">

                                    <input type="checkbox" class="form-check-input agent-checkbox" value="{{ $agent->id }}" data-id="{{ $agent->id }}">

                                </td>

                                <td>
                                    {{ $agent->matricule }}
                                </td>

                                <td>
                                    {{ $agent->prenom }}
                                </td>

                                <td>
                                    {{ $agent->nom }}
                                </td>

                                <td>
                                    {{ $agent->fonction }}
                                </td>

                                <td>
                                    {{ $agent->poste ?? '—' }}
                                </td>

                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $agent->categorie }}
                                    </span>
                                </td>

                                <td>
                                    {{ $agent->service }}
                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="8" class="text-center text-muted py-4">

                                    <i class="bi bi-person-x fs-3 d-block mb-2"></i>

                                    Aucun agent disponible.

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            <div class="modal-footer">

                <span class="text-muted me-auto">
                    <strong id="selectedCount">0</strong>
                    agent(s) sélectionné(s)
                </span>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                    Fermer

                </button>

                <button type="button" class="btn btn-primary" id="confirmAgentsBtn">

                    <i class="bi bi-check-lg me-1"></i>
                    Ajouter les participants

                </button>

            </div>

        </div>

    </div>

</div>



{{-- =============================================================
     JAVASCRIPT
============================================================== --}}

@php
$paysRegionsJs = $pays->mapWithKeys(function ($pay) {
return [
(string) $pay->id => $pay->regions->map(function ($region) {
return [
'id' => $region->id,
'nom' => $region->nom,
];
})->values()->all(),
];
})->all();
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function() {

        /*
        |--------------------------------------------------------------------------
        | Données PHP → JavaScript
        |--------------------------------------------------------------------------
        */


        const paysRegions = @json($paysRegionsJs);



        const allAgents = {
            !!$agents - > toJson() !!
        };


        /*
        |--------------------------------------------------------------------------
        | Éléments DOM
        |--------------------------------------------------------------------------
        */

        const form = document.getElementById('missionForm');

        const typeSelect = document.getElementById('type');

        const paysSelect = document.getElementById('pays_id');

        const regionSelect = document.getElementById('region_id');

        const dateDepart = document.getElementById('date_depart');

        const dateRetour = document.getElementById('date_retour');

        const dureeInput = document.getElementById('duree');

        const participantsBody =
            document.getElementById('participantsTableBody');

        const emptyParticipantsRow =
            document.getElementById('emptyParticipantsRow');

        const agentSearch =
            document.getElementById('agentSearch');

        const selectedCount =
            document.getElementById('selectedCount');

        const submitBtn =
            document.getElementById('submitBtn');

        const submitText =
            document.getElementById('submitText');

        const submitLoader =
            document.getElementById('submitLoader');

        const paysHelp =
            document.getElementById('paysHelp');


        /*
        |--------------------------------------------------------------------------
        | Agents sélectionnés
        |--------------------------------------------------------------------------
        */

        let selectedAgents = [];


        /*
        |--------------------------------------------------------------------------
        | Recherche de l'agent
        |--------------------------------------------------------------------------
        */

        function getAgentById(id) {
            return allAgents.find(
                agent => String(agent.id) === String(id)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Affichage des participants
        |--------------------------------------------------------------------------
        */

        function renderSelectedAgents() {
            participantsBody.innerHTML = '';

            if (selectedAgents.length === 0) {

                participantsBody.innerHTML = `
                <tr id="emptyParticipantsRow">
                    <td colspan="9"
                        class="text-center text-muted py-4">

                        <i class="bi bi-people fs-3 d-block mb-2"></i>

                        Aucun participant sélectionné.

                    </td>
                </tr>
            `;

                return;
            }


            selectedAgents.forEach(function(agent, index) {

                const row = document.createElement('tr');

                row.innerHTML = `

                <td class="text-center">
                    ${index + 1}
                </td>

                <td>
                    ${escapeHtml(agent.matricule ?? '')}
                </td>

                <td>
                    ${escapeHtml(agent.prenom ?? '')}
                </td>

                <td>
                    ${escapeHtml(agent.nom ?? '')}
                </td>

                <td>
                    ${escapeHtml(agent.fonction ?? '')}
                </td>

                <td>
                    ${escapeHtml(agent.poste ?? '—')}
                </td>

                <td>
                    <span class="badge bg-info text-dark">
                        ${escapeHtml(agent.categorie ?? '')}
                    </span>
                </td>

                <td>
                    ${escapeHtml(agent.service ?? '')}
                </td>

                <td class="text-center">

                    <button type="button"
                            class="btn btn-sm btn-outline-danger remove-agent"
                            data-id="${agent.id}"
                            title="Retirer">

                        <i class="bi bi-trash"></i>

                    </button>

                </td>
            `;

                participantsBody.appendChild(row);

            });


            document.querySelectorAll('.remove-agent')
                .forEach(function(button) {

                    button.addEventListener('click', function() {

                        const id = String(this.dataset.id);

                        selectedAgents =
                            selectedAgents.filter(
                                agent => String(agent.id) !== id
                            );

                        updateAgentCheckboxes();

                        updateSelectedCount();

                        renderSelectedAgents();

                    });

                });
        }


        /*
        |--------------------------------------------------------------------------
        | Sécurisation affichage HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value) {
            const div = document.createElement('div');

            div.textContent = value ? ? '';

            return div.innerHTML;
        }


        /*
        |--------------------------------------------------------------------------
        | Mise à jour des cases à cocher
        |--------------------------------------------------------------------------
        */

        function updateAgentCheckboxes() {
            document
                .querySelectorAll('.agent-checkbox')
                .forEach(function(checkbox) {

                    const selected =
                        selectedAgents.some(
                            agent =>
                            String(agent.id) ===
                            String(checkbox.value)
                        );

                    checkbox.checked = selected;

                });
        }


        /*
        |--------------------------------------------------------------------------
        | Nombre d'agents sélectionnés
        |--------------------------------------------------------------------------
        */

        function updateSelectedCount() {
            selectedCount.textContent =
                selectedAgents.length;
        }


        /*
        |--------------------------------------------------------------------------
        | Sélection des agents depuis la modal
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.agent-checkbox')
            .forEach(function(checkbox) {

                checkbox.addEventListener('change', function() {

                    const agentId = String(this.value);

                    const agent = getAgentById(agentId);

                    if (!agent) {
                        return;
                    }


                    if (this.checked) {

                        const exists =
                            selectedAgents.some(
                                item =>
                                String(item.id) === agentId
                            );

                        if (!exists) {
                            selectedAgents.push(agent);
                        }

                    } else {

                        selectedAgents =
                            selectedAgents.filter(
                                item =>
                                String(item.id) !== agentId
                            );

                    }


                    updateSelectedCount();

                });

            });


        /*
        |--------------------------------------------------------------------------
        | Bouton confirmer les agents
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('confirmAgentsBtn')
            .addEventListener('click', function() {

                renderSelectedAgents();

                updateAgentCheckboxes();

                updateSelectedCount();

                const modalElement =
                    document.getElementById('agentModal');

                const modal =
                    bootstrap.Modal.getInstance(modalElement);

                if (modal) {
                    modal.hide();
                }

            });


        /*
        |--------------------------------------------------------------------------
        | Recherche dans les agents
        |--------------------------------------------------------------------------
        */

        agentSearch.addEventListener('input', function() {

            const search =
                this.value.toLowerCase().trim();


            document
                .querySelectorAll('.agent-row')
                .forEach(function(row) {

                    const data =
                        row.dataset.search || '';

                    row.style.display =
                        data.includes(search) ?
                        '' :
                        'none';

                });

        });


        /*
        |--------------------------------------------------------------------------
        | Gestion type de mission
        |--------------------------------------------------------------------------
        */


        typeSelect.addEventListener('change', function() {
            const type = this.value;

            regionSelect.innerHTML =
                '<option value="">-- Sélectionner une région --</option>';
            regionSelect.disabled = true;

            if (type === 'nationale') {
                const maliOption = Array.from(paysSelect.options).find(option =>
                    (option.dataset.code || '').toUpperCase() === 'ML' ||
                    (option.dataset.nom || '').toLowerCase() === 'mali'
                );

                paysSelect.disabled = false;

                if (maliOption) {
                    paysSelect.value = maliOption.value;
                    loadRegions(maliOption.value);
                    paysSelect.disabled = true;
                } else {
                    showAlert('Le Mali est introuvable dans la liste des pays.', 'warning');
                }

                paysHelp.textContent =
                    'Le Mali est automatiquement sélectionné pour une mission nationale.';
                return;
            }

            paysSelect.disabled = type !== 'internationale';

            if (type === 'internationale') {
                paysHelp.textContent =
                    'Sélectionnez le pays de destination.';
            } else {
                paysSelect.value = '';
                paysHelp.textContent =
                    'Sélectionnez d’abord le type de mission.';
            }
        });

        paysSelect.addEventListener('change', function() {
            loadRegions(this.value);
        });

        // Initialisation à l'ouverture de la page
        if (typeSelect.value === 'nationale') {
            typeSelect.dispatchEvent(new Event('change'));
        } else if (typeSelect.value === 'internationale') {
            paysSelect.disabled = false;
            paysHelp.textContent = 'Sélectionnez le pays de destination.';
        }



        /*
        |--------------------------------------------------------------------------
        | Chargement des régions
        |--------------------------------------------------------------------------
        */

        function loadRegions(paysId) {
            regionSelect.innerHTML = `
            <option value="">
                -- Sélectionner une région --
            </option>
        `;


            if (!paysId) {

                regionSelect.disabled = true;

                return;
            }


            const regions =
                paysRegions[paysId] || [];


            if (regions.length === 0) {

                regionSelect.disabled = true;

                regionSelect.innerHTML = `
                <option value="">
                    -- Aucune région disponible --
                </option>
            `;

                return;
            }


            regions.forEach(function(region) {

                const option =
                    document.createElement('option');

                option.value =
                    region.id;

                option.textContent =
                    region.nom;

                regionSelect.appendChild(option);

            });


            regionSelect.disabled = false;

        }


        /*
        |--------------------------------------------------------------------------
        | Changement de pays
        |--------------------------------------------------------------------------
        */

        paysSelect.addEventListener('change', function() {

            loadRegions(this.value);

        });


        /*
        |--------------------------------------------------------------------------
        | Calcul automatique de la durée
        |--------------------------------------------------------------------------
        |
        | La durée est inclusive :
        |
        | 01/09 → 01/09 = 1 jour
        | 01/09 → 02/09 = 2 jours
        |
        |--------------------------------------------------------------------------
        */

        function calculateDuration() {
            const departure =
                dateDepart.value;

            const returnDate =
                dateRetour.value;


            if (!departure || !returnDate) {

                dureeInput.value = '';

                return;

            }


            const start =
                new Date(departure + 'T00:00:00');

            const end =
                new Date(returnDate + 'T00:00:00');


            if (isNaN(start.getTime()) ||
                isNaN(end.getTime())) {

                dureeInput.value = '';

                return;
            }


            const difference =
                end.getTime() - start.getTime();


            if (difference < 0) {

                dureeInput.value = '';

                return;
            }


            const days =
                Math.floor(
                    difference /
                    (1000 * 60 * 60 * 24)
                ) + 1;


            dureeInput.value =
                days + ' jour(s)';

        }


        dateDepart.addEventListener(
            'change'
            , calculateDuration
        );

        dateRetour.addEventListener(
            'change'
            , calculateDuration
        );


        /*
        |--------------------------------------------------------------------------
        | Vérification dates
        |--------------------------------------------------------------------------
        */

        function validateDates() {
            if (!dateDepart.value ||
                !dateRetour.value) {

                return false;
            }


            const start =
                new Date(
                    dateDepart.value + 'T00:00:00'
                );

            const end =
                new Date(
                    dateRetour.value + 'T00:00:00'
                );


            return end >= start;
        }


        /*
        |--------------------------------------------------------------------------
        | Alertes
        |--------------------------------------------------------------------------
        */

        function showAlert(message, type = 'danger') {
            const html = `

            <div class="alert alert-${type} alert-dismissible fade show"
                 role="alert">

                <i class="bi bi-exclamation-triangle me-2"></i>

                ${escapeHtml(message)}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        `;

            document
                .getElementById('alertContainer')
                .innerHTML = html;

            window.scrollTo({
                top: 0
                , behavior: 'smooth'
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Affichage erreurs Laravel
        |--------------------------------------------------------------------------
        */

        function displayValidationErrors(errors) {
            const container = document.getElementById('alertContainer');

            container.innerHTML = '';

            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show';
            alert.setAttribute('role', 'alert');

            const title = document.createElement('strong');
            title.textContent = 'Veuillez corriger les erreurs suivantes :';

            const list = document.createElement('ul');
            list.className = 'mb-0 mt-2';

            Object.values(errors || {}).flat().forEach(function(message) {
                const item = document.createElement('li');
                item.textContent = message;
                list.appendChild(item);
            });

            const close = document.createElement('button');
            close.type = 'button';
            close.className = 'btn-close';
            close.setAttribute('data-bs-dismiss', 'alert');
            close.setAttribute('aria-label', 'Fermer');

            alert.appendChild(title);
            alert.appendChild(list);
            alert.appendChild(close);

            container.appendChild(alert);

            window.scrollTo({
                top: 0
                , behavior: 'smooth'
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Soumission du formulaire
        |--------------------------------------------------------------------------
        */

        form.addEventListener('submit', async function(event) {

            event.preventDefault();


            /*
            |--------------------------------------------------------------------------
            | Vérifications navigateur
            |--------------------------------------------------------------------------
            */

            const objet =
                document.getElementById('objet').value.trim();

            const direction =
                document.getElementById('direction').value;

            const type =
                typeSelect.value;

            const paysId =
                paysSelect.value;

            const regionId =
                regionSelect.value;

            const destination =
                document.getElementById('destination')
                .value
                .trim();


            if (!objet) {

                showAlert(
                    'Veuillez renseigner l’objet de la mission.'
                    , 'warning'
                );

                return;
            }


            if (!direction) {

                showAlert(
                    'Veuillez sélectionner une direction.'
                    , 'warning'
                );

                return;
            }


            if (!type) {

                showAlert(
                    'Veuillez sélectionner le type de mission.'
                    , 'warning'
                );

                return;
            }


            if (!paysId) {

                showAlert(
                    'Veuillez sélectionner le pays.'
                    , 'warning'
                );

                return;
            }


            if (!regionId) {

                showAlert(
                    'Veuillez sélectionner la région.'
                    , 'warning'
                );

                return;
            }


            if (!destination) {

                showAlert(
                    'Veuillez renseigner la destination.'
                    , 'warning'
                );

                return;
            }


            if (!validateDates()) {

                showAlert(
                    'La date de retour doit être supérieure ou égale à la date de départ.'
                    , 'warning'
                );

                return;
            }


            if (selectedAgents.length === 0) {

                showAlert(
                    'Veuillez sélectionner au moins un participant à la mission.'
                    , 'warning'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Vérification durée
            |--------------------------------------------------------------------------
            */

            const start =
                new Date(
                    dateDepart.value + 'T00:00:00'
                );

            const end =
                new Date(
                    dateRetour.value + 'T00:00:00'
                );

            const duree =
                Math.floor(
                    (end - start) /
                    (1000 * 60 * 60 * 24)
                ) + 1;


            if (duree < 1) {

                showAlert(
                    'La durée de la mission est invalide.'
                    , 'warning'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Préparation FormData
            |--------------------------------------------------------------------------
            */

            const formData =
                new FormData();


            formData.append(
                '_token'
                , document.querySelector(
                    'meta[name="csrf-token"]'
                ) ? .getAttribute('content') ||
                '{{ csrf_token() }}'
            );


            formData.append(
                'objet'
                , objet
            );


            formData.append(
                'direction'
                , direction
            );


            formData.append(
                'type'
                , type
            );


            formData.append(
                'pays_id'
                , paysId
            );


            formData.append(
                'region_id'
                , regionId
            );


            formData.append(
                'destination'
                , destination
            );


            formData.append(
                'date_depart'
                , dateDepart.value
            );


            formData.append(
                'date_retour'
                , dateRetour.value
            );


            // Identifiant de la demande d'initiation, si le formulaire provient d'une initiation
            const initiationInput = document.querySelector(
                'input[name="mission_initiation_id"]'
            );

            if (initiationInput) {
                formData.append(
                    'mission_initiation_id'
                    , initiationInput.value
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Participants
            |--------------------------------------------------------------------------
            */

            selectedAgents.forEach(function(agent) {

                formData.append(
                    'agent_ids[]'
                    , agent.id
                );

            });


            /*
            |--------------------------------------------------------------------------
            | Bouton chargement
            |--------------------------------------------------------------------------
            */

            submitBtn.disabled = true;

            submitText.classList.add('d-none');

            submitLoader.classList.remove('d-none');


            try {

                const response =
                    await fetch(
                        '{{ route("missions.store") }}', {
                            method: 'POST'
                            , headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                                , 'Accept': 'application/json'
                            }
                            , body: formData
                        }
                    );


                const data =
                    await response.json();


                /*
                |--------------------------------------------------------------------------
                | Succès
                |--------------------------------------------------------------------------
                */

                if (response.ok && data.success) {

                    showAlert(
                        data.message ||
                        'Mission créée avec succès.'
                        , 'success'
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Redirection
                    |--------------------------------------------------------------------------
                    */

                    setTimeout(function() {

                        if (data.redirect) {

                            window.location.href =
                                data.redirect;

                        } else if (data.mission_id) {

                            window.location.href =
                                '/missions/' +
                                data.mission_id;

                        } else {

                            window.location.href =
                                '{{ route("missions.index") }}';

                        }

                    }, 700);


                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Erreurs de validation Laravel
                |--------------------------------------------------------------------------
                */

                if (response.status === 422) {

                    if (data.errors) {

                        displayValidationErrors(
                            data.errors
                        );

                    } else {

                        showAlert(
                            data.message ||
                            'Les données saisies sont invalides.'
                            , 'danger'
                        );

                    }

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Autres erreurs
                |--------------------------------------------------------------------------
                */

                showAlert(
                    data.message ||
                    'Une erreur est survenue lors de la création de la mission.'
                    , 'danger'
                );


            } catch (error) {

                console.error(error);

                showAlert(
                    'Impossible de contacter le serveur. Vérifiez votre connexion puis réessayez.'
                    , 'danger'
                );


            } finally {

                submitBtn.disabled = false;

                submitText.classList.remove('d-none');

                submitLoader.classList.add('d-none');

            }

        });


        /*
        |--------------------------------------------------------------------------
        | Initialisation
        |--------------------------------------------------------------------------
        */

        renderSelectedAgents();

        updateSelectedCount();

    });

</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const regionSelect = document.getElementById('region_id');
        const cercleSelect = document.getElementById('destination');
        const message = document.getElementById('cercle-message');

        if (!regionSelect || !cercleSelect) return;

        async function chargerCercles(regionId) {
            cercleSelect.innerHTML =
                '<option value="">-- Chargement des cercles... --</option>';
            cercleSelect.disabled = true;

            if (!regionId) {
                cercleSelect.innerHTML =
                    '<option value="">-- Sélectionner d\'abord la région --</option>';
                message.textContent =
                    'Sélectionne une région pour afficher ses cercles.';
                return;
            }

            try {
                const url = @json(url('/regions')) + '/' +
                    encodeURIComponent(regionId) + '/cercles';

                const response = await fetch(url, {
                    headers: {
                        Accept: 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Erreur lors du chargement des cercles.');
                }

                const cercles = await response.json();

                cercleSelect.innerHTML =
                    '<option value="">-- Sélectionner un cercle --</option>';

                cercles.forEach(function(cercle) {
                    const option = document.createElement('option');
                    option.value = cercle.nom_cercle;
                    option.textContent = cercle.nom_cercle;
                    cercleSelect.appendChild(option);
                });

                cercleSelect.disabled = false;
                message.textContent = cercles.length ?
                    cercles.length + ' cercle(s) disponible(s).' :
                    'Aucun cercle enregistré pour cette région.';

            } catch (error) {
                cercleSelect.innerHTML =
                    '<option value="">-- Erreur de chargement --</option>';
                message.textContent = error.message;
            }
        }

        regionSelect.addEventListener('change', function() {
            chargerCercles(this.value);
        });
    });

</script>




@endsection
