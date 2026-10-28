@extends('layouts.ap')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid py-4">

    {{-- ============================================================
        EN-TÊTE
    ============================================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="fas fa-edit text-primary me-2"></i>
                Modifier la mission
            </h4>

            <p class="text-muted mb-0">
                Mission :
                <strong>{{ $mission->numero }}</strong>
            </p>

        </div>

        <a
            href="{{ route('missions.index', $mission->id) }}"
            class="btn btn-secondary"
        >
            <i class="fas fa-arrow-left me-1"></i>
            Retour
        </a>

    </div>


    {{-- ============================================================
        ALERTES
    ============================================================ --}}

    <div id="alertContainer"></div>


    {{-- ============================================================
        FORMULAIRE
    ============================================================ --}}

    <form
        id="missionEditForm"
        action="{{ route('missions.update', $mission->id) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        {{-- ========================================================
            INFORMATIONS MISSION
        ======================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">

                    <i class="fas fa-info-circle text-primary me-2"></i>

                    Informations de la mission

                </h5>

            </div>


            <div class="card-body">

                <div class="row g-3">

                    {{-- Numéro --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Numéro
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="{{ $mission->numero }}"
                            readonly
                        >

                    </div>


                    {{-- Statut --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Statut
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="En attente CG"
                            readonly
                        >

                    </div>


                    {{-- Durée --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Durée
                        </label>

                        <div class="input-group">

                            <input
                                type="text"
                                id="duree"
                                class="form-control"
                                value="{{ $mission->duree }}"
                                readonly
                            >

                            <span class="input-group-text">
                                jour(s)
                            </span>

                        </div>

                    </div>


                    {{-- Objet --}}
                    <div class="col-md-12">

                        <label class="form-label">
                            Objet de la mission
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="objet"
                            id="objet"
                            class="form-control"
                            rows="3"
                            required
                        >{{ $mission->objet }}</textarea>

                    </div>


                    {{-- Direction --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Direction
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="direction"
                            id="direction"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Sélectionner --
                            </option>

                            @foreach($directions as $direction)

                                <option
                                    value="{{ $direction->nom_direction }}"
                                    {{ $mission->direction === $direction->nom_direction ? 'selected' : '' }}
                                >
                                    {{ $direction->nom_direction }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Type --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Type
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="type"
                            id="type"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Sélectionner --
                            </option>

                            <option
                                value="nationale"
                                {{ $mission->type === 'nationale' ? 'selected' : '' }}
                            >
                                Nationale
                            </option>

                            <option
                                value="internationale"
                                {{ $mission->type === 'internationale' ? 'selected' : '' }}
                            >
                                Internationale
                            </option>

                        </select>

                    </div>


                    {{-- Pays --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Pays
                        </label>

                        <select
                            name="pays_id"
                            id="pays_id"
                            class="form-select"
                        >

                            <option value="">
                                -- Sélectionner le pays --
                            </option>

                            @foreach($pays as $pay)

                                <option
                                    value="{{ $pay->id }}"
                                    {{ (string)$mission->pays_id === (string)$pay->id ? 'selected' : '' }}
                                >
                                    {{ $pay->nom }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Région --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Région
                        </label>

                        <select
                            name="region_id"
                            id="region_id"
                            class="form-select"
                        >

                            <option value="">
                                -- Sélectionner la région --
                            </option>

                        </select>

                    </div>


                    {{-- Destination --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Destination

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="destination"
                            id="destination"
                            class="form-control"
                            value="{{ $mission->destination }}"
                            required
                        >

                    </div>


                    {{-- Date départ --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            Date de départ

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="date_depart"
                            id="date_depart"
                            class="form-control"
                            value="{{ $mission->date_depart?->format('Y-m-d') }}"
                            required
                        >

                    </div>


                    {{-- Date retour --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            Date de retour

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="date"
                            name="date_retour"
                            id="date_retour"
                            class="form-control"
                            value="{{ $mission->date_retour?->format('Y-m-d') }}"
                            required
                        >

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
            AGENTS
        ============================================================ --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-0">

                        <i class="fas fa-users text-primary me-2"></i>

                        Agents participants

                    </h5>

                    <small class="text-muted">

                        Vous pouvez ajouter ou retirer les agents
                        tant que la mission est en brouillon.

                    </small>

                </div>


                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#agentModal"
                >

                    <i class="fas fa-plus me-1"></i>

                    Ajouter un agent

                </button>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="50">#</th>

                                <th>Matricule</th>

                                <th>Agent</th>

                                <th>Fonction</th>

                                <th>Catégorie</th>

                                <th>Service</th>

                                <th width="100">Action</th>

                            </tr>

                        </thead>


                        <tbody id="agentsTableBody">

                            @forelse($mission->missionAgents as $missionAgent)

                                @php
                                    $agent = $missionAgent->agent;
                                @endphp

                                @if($agent)

                                    <tr data-agent-id="{{ $agent->id }}">

                                        <td class="agent-index">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            {{ $agent->matricule }}
                                        </td>

                                        <td>
                                            <strong>
                                                {{ $agent->prenom }}
                                                {{ $agent->nom }}
                                            </strong>
                                        </td>

                                        <td>
                                            {{ $agent->fonction }}
                                        </td>

                                        <td>
                                            {{ $agent->categorie }}
                                        </td>

                                        <td>
                                            {{ $agent->service }}
                                        </td>

                                        <td>

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm btn-remove-agent"
                                                data-id="{{ $agent->id }}"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </td>

                                    </tr>

                                @endif

                            @empty

                                <tr id="emptyAgents">

                                    <td
                                        colspan="7"
                                        class="text-center text-muted py-4"
                                    >

                                        <i class="fas fa-users fa-2x mb-2"></i>

                                        <br>

                                        Aucun agent sélectionné.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- ============================================================
            BOUTONS
        ============================================================ --}}

        <div class="d-flex justify-content-end gap-2">

            <a
                href="{{ route('missions.show', $mission->id) }}"
                class="btn btn-secondary"
            >

                <i class="fas fa-times me-1"></i>

                Annuler

            </a>


            <button
                type="submit"
                class="btn btn-primary"
                id="btnUpdate"
            >

                <i class="fas fa-save me-1"></i>

                Enregistrer les modifications

            </button>

        </div>

    </form>

</div>


{{-- ===============================================================
    MODAL AGENT
================================================================ --}}

<div
    class="modal fade"
    id="agentModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fas fa-user-plus me-2"></i>

                    Ajouter un agent

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body">

                <input
                    type="text"
                    id="searchAgent"
                    class="form-control mb-3"
                    placeholder="Rechercher un agent..."
                >


                <div
                    id="agentsList"
                    class="list-group"
                    style="max-height:400px; overflow-y:auto;"
                >

                    @foreach($agents as $agent)

                        <button
                            type="button"
                            class="list-group-item list-group-item-action agent-item"
                            data-id="{{ $agent->id }}"
                            data-matricule="{{ $agent->matricule }}"
                            data-prenom="{{ $agent->prenom }}"
                            data-nom="{{ $agent->nom }}"
                            data-fonction="{{ $agent->fonction }}"
                            data-categorie="{{ $agent->categorie }}"
                            data-service="{{ $agent->service }}"
                        >

                            <div class="d-flex justify-content-between">

                                <div>

                                    <strong>
                                        {{ $agent->prenom }}
                                        {{ $agent->nom }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $agent->matricule }}
                                    </small>

                                </div>

                                <div class="text-end">

                                    <small>
                                        {{ $agent->fonction }}
                                    </small>

                                    <br>

                                    <small class="text-muted">
                                        {{ $agent->service }}
                                    </small>

                                </div>

                            </div>

                        </button>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ===============================================================
    DONNÉES JAVASCRIPT
================================================================ --}}

<script>

const missionPaysId =
    @json($mission->pays_id);

const missionRegionId =
    @json($mission->region_id);

const missionType =
    @json($mission->type);

const missionUpdateUrl =
    @json(route('missions.update', $mission->id));

const missionShowUrl =
    @json(route('missions.show', $mission->id));


/*
|--------------------------------------------------------------------------
| PAYS + RÉGIONS
|--------------------------------------------------------------------------
*/

const paysData = {};

@foreach($pays as $pay)

    paysData["{{ $pay->id }}"] = {

        id: {{ $pay->id }},

        nom: @json($pay->nom),

        regions: [

            @foreach($pay->regions as $region)

                {
                    id: {{ $region->id }},
                    nom: @json($region->nom)
                },

            @endforeach

        ]
    };

@endforeach

</script>


<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    const csrfToken =
        $('meta[name="csrf-token"]').attr('content');


    /*
    |--------------------------------------------------------------------------
    | UTILITAIRE : ALERTE
    |--------------------------------------------------------------------------
    */

    function afficherAlerte(
        type,
        message
    ) {

        const html = `

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

        $('#alertContainer')
            .html(html);

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }


    /*
    |--------------------------------------------------------------------------
    | PAYS / RÉGIONS
    |--------------------------------------------------------------------------
    */

    function chargerRegions(
        paysId,
        regionSelectionnee = null
    ) {

        const regionSelect =
            $('#region_id');

        regionSelect.empty();

        regionSelect.append(
            $('<option>', {
                value: '',
                text: '-- Sélectionner la région --'
            })
        );

        if (!paysId) {

            regionSelect
                .prop('disabled', true);

            return;
        }

        const pays =
            paysData[String(paysId)];

        if (!pays) {

            console.error(
                'Pays introuvable :',
                paysId
            );

            regionSelect
                .prop('disabled', true);

            return;
        }

        regionSelect
            .prop('disabled', false);

        if (
            !pays.regions ||
            pays.regions.length === 0
        ) {

            regionSelect.append(
                $('<option>', {
                    value: '',
                    text: '-- Aucune région disponible --'
                })
            );

            return;
        }

        pays.regions.forEach(
            function (region) {

                const option =
                    $('<option>', {
                        value: region.id,
                        text: region.nom
                    });

                if (
                    regionSelectionnee !== null &&
                    String(region.id) ===
                    String(regionSelectionnee)
                ) {

                    option.prop(
                        'selected',
                        true
                    );
                }

                regionSelect.append(
                    option
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TROUVER LE MALI
    |--------------------------------------------------------------------------
    */

    function trouverMali() {

        let mali = null;

        Object.keys(paysData).forEach(
            function (id) {

                const pays =
                    paysData[id];

                if (
                    String(pays.nom)
                        .trim()
                        .toLowerCase() ===
                    'mali'
                ) {

                    mali = pays;
                }
            }
        );

        return mali;
    }


    /*
    |--------------------------------------------------------------------------
    | GESTION DU TYPE
    |--------------------------------------------------------------------------
    */

    function gererTypeMission(
        regionAConserver = null
    ) {

        const type =
            $('#type').val();

        const paysSelect =
            $('#pays_id');

        const regionSelect =
            $('#region_id');


        /*
        |--------------------------------------------------------------------------
        | NATIONALE
        |--------------------------------------------------------------------------
        */

        if (type === 'nationale') {

            const mali =
                trouverMali();

            if (!mali) {

                afficherAlerte(
                    'danger',
                    'Le pays "Mali" est introuvable dans la liste des pays.'
                );

                paysSelect
                    .val('')
                    .prop('disabled', true);

                regionSelect
                    .empty()
                    .append(
                        $('<option>', {
                            value: '',
                            text: '-- Sélectionner la région --'
                        })
                    )
                    .prop('disabled', true);

                return;
            }

            paysSelect
                .val(mali.id)
                .prop('disabled', true);

            chargerRegions(
                mali.id,
                regionAConserver
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | INTERNATIONALE
        |--------------------------------------------------------------------------
        */

        if (type === 'internationale') {

            paysSelect
                .prop('disabled', false);

            chargerRegions(
                paysSelect.val(),
                regionAConserver
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | AUCUN TYPE
        |--------------------------------------------------------------------------
        */

        paysSelect
            .val('')
            .prop('disabled', true);

        regionSelect
            .empty()
            .append(
                $('<option>', {
                    value: '',
                    text: '-- Sélectionner la région --'
                })
            )
            .prop('disabled', true);
    }


    /*
    |--------------------------------------------------------------------------
    | INITIALISATION PAYS / RÉGION
    |--------------------------------------------------------------------------
    */

    if (
        missionType === 'nationale'
    ) {

        const mali =
            trouverMali();

        if (mali) {

            $('#pays_id')
                .val(mali.id)
                .prop('disabled', true);

            chargerRegions(
                mali.id,
                missionRegionId
            );

        }

    }
    else if (
        missionType === 'internationale'
    ) {

        $('#pays_id')
            .val(missionPaysId)
            .prop('disabled', false);

        chargerRegions(
            missionPaysId,
            missionRegionId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT TYPE
    |--------------------------------------------------------------------------
    */

    $('#type').on(
        'change',
        function () {

            const type =
                $(this).val();

            if (type === 'nationale') {

                gererTypeMission(null);

            }
            else if (
                type === 'internationale'
            ) {

                $('#pays_id')
                    .prop('disabled', false);

                chargerRegions(
                    $('#pays_id').val(),
                    null
                );

            }
            else {

                gererTypeMission(null);
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT PAYS
    |--------------------------------------------------------------------------
    */

    $('#pays_id').on(
        'change',
        function () {

            chargerRegions(
                $(this).val(),
                null
            );
        }
    );


    /*
    |--------------------------------------------------------------------------
    | AGENTS SÉLECTIONNÉS
    |--------------------------------------------------------------------------
    */

    let selectedAgents = [];

    @foreach($mission->missionAgents as $missionAgent)

        @if($missionAgent->agent_id)

            selectedAgents.push(
                Number({{ $missionAgent->agent_id }})
            );

        @endif

    @endforeach


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER DOUBLONS
    |--------------------------------------------------------------------------
    */

    selectedAgents =
        [...new Set(selectedAgents)];


    /*
    |--------------------------------------------------------------------------
    | ÉCHAPPER HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        return $('<div>')
            .text(value ?? '')
            .html();
    }


    /*
    |--------------------------------------------------------------------------
    | REINDEXER LE TABLEAU
    |--------------------------------------------------------------------------
    */

    function reindexerAgents() {

        $('#agentsTableBody tr[data-agent-id]')
            .each(function(index) {

                $(this)
                    .find('.agent-index')
                    .text(index + 1);

            });


        if (
            $('#agentsTableBody tr[data-agent-id]')
                .length === 0
        ) {

            if (
                $('#emptyAgents').length === 0
            ) {

                $('#agentsTableBody').html(`

                    <tr id="emptyAgents">

                        <td
                            colspan="7"
                            class="text-center text-muted py-4"
                        >

                            <i class="fas fa-users fa-2x mb-2"></i>

                            <br>

                            Aucun agent sélectionné.

                        </td>

                    </tr>

                `);
            }

        }
        else {

            $('#emptyAgents')
                .remove();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | AJOUTER UN AGENT AU TABLEAU
    |--------------------------------------------------------------------------
    */

    function ajouterAgent(agent) {

        const agentId =
            Number(agent.id);

        /*
        |--------------------------------------------------------------------------
        | ÉVITER LES DOUBLONS
        |--------------------------------------------------------------------------
        */

        if (
            selectedAgents.includes(agentId)
        ) {

            afficherAlerte(
                'warning',
                'Cet agent participe déjà à la mission.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | AJOUTER AU TABLEAU
        |--------------------------------------------------------------------------
        */

        selectedAgents.push(
            agentId
        );


        $('#emptyAgents')
            .remove();


        const row = `

            <tr data-agent-id="${agentId}">

                <td class="agent-index">
                    ${selectedAgents.length}
                </td>

                <td>
                    ${escapeHtml(agent.matricule)}
                </td>

                <td>
                    <strong>
                        ${escapeHtml(agent.prenom)}
                        ${escapeHtml(agent.nom)}
                    </strong>
                </td>

                <td>
                    ${escapeHtml(agent.fonction)}
                </td>

                <td>
                    ${escapeHtml(agent.categorie)}
                </td>

                <td>
                    ${escapeHtml(agent.service)}
                </td>

                <td>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm btn-remove-agent"
                        data-id="${agentId}"
                    >

                        <i class="fas fa-trash"></i>

                    </button>

                </td>

            </tr>

        `;


        $('#agentsTableBody')
            .append(row);


        reindexerAgents();


        /*
        |--------------------------------------------------------------------------
        | FERMER LE MODAL
        |--------------------------------------------------------------------------
        */

        const modalElement =
            document.getElementById(
                'agentModal'
            );

        const modal =
            bootstrap.Modal.getInstance(
                modalElement
            );

        if (modal) {

            modal.hide();
        }


        /*
        |--------------------------------------------------------------------------
        | NETTOYER LA RECHERCHE
        |--------------------------------------------------------------------------
        */

        $('#searchAgent')
            .val('');

        $('.agent-item')
            .show();
    }


    /*
    |--------------------------------------------------------------------------
    | CLIQUER SUR UN AGENT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.agent-item',
        function () {

            const button =
                $(this);

            const agent = {

                id:
                    button.data('id'),

                matricule:
                    button.data('matricule'),

                prenom:
                    button.data('prenom'),

                nom:
                    button.data('nom'),

                fonction:
                    button.data('fonction'),

                categorie:
                    button.data('categorie'),

                service:
                    button.data('service')
            };


            ajouterAgent(agent);
        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER UN AGENT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.btn-remove-agent',
        function () {

            const agentId =
                Number(
                    $(this).data('id')
                );


            selectedAgents =
                selectedAgents.filter(
                    function (id) {

                        return id !== agentId;

                    }
                );


            $(
                '#agentsTableBody tr[data-agent-id="' +
                agentId +
                '"]'
            ).remove();


            reindexerAgents();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | RECHERCHE AGENT
    |--------------------------------------------------------------------------
    */

    $('#searchAgent').on(
        'input',
        function () {

            const recherche =
                $(this)
                    .val()
                    .toLowerCase()
                    .trim();


            $('.agent-item')
                .each(function () {

                    const item =
                        $(this);

                    const texte =
                        item.text()
                            .toLowerCase();

                    if (
                        texte.includes(
                            recherche
                        )
                    ) {

                        item.show();

                    }
                    else {

                        item.hide();

                    }

                });
        }
    );


    /*
    |--------------------------------------------------------------------------
    | CALCUL DURÉE
    |--------------------------------------------------------------------------
    */

    function calculerDuree() {

        const depart =
            $('#date_depart').val();

        const retour =
            $('#date_retour').val();


        if (!depart || !retour) {

            $('#duree').val('');

            return false;
        }


        const dateDepart =
            new Date(
                depart + 'T00:00:00'
            );

        const dateRetour =
            new Date(
                retour + 'T00:00:00'
            );


        if (
            dateRetour < dateDepart
        ) {

            $('#duree').val('');

            return false;
        }


        const difference =
            dateRetour.getTime() -
            dateDepart.getTime();


        const jours =
            Math.floor(
                difference /
                (1000 * 60 * 60 * 24)
            ) + 1;


        $('#duree')
            .val(jours);

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGEMENT DATES
    |--------------------------------------------------------------------------
    */

    $('#date_depart, #date_retour').on(
        'change',
        function () {

            const valide =
                calculerDuree();


            if (!valide) {

                $('#date_retour')
                    .addClass('is-invalid');

            }
            else {

                $('#date_retour')
                    .removeClass('is-invalid');
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | CALCUL INITIAL
    |--------------------------------------------------------------------------
    */

    calculerDuree();


    /*
    |--------------------------------------------------------------------------
    | SOUMISSION AJAX
    |--------------------------------------------------------------------------
    */

    $('#missionEditForm').on(
        'submit',
        async function (event) {

            event.preventDefault();


            /*
            |--------------------------------------------------------------------------
            | NETTOYER ALERTES
            |--------------------------------------------------------------------------
            */

            $('#alertContainer')
                .empty();


            /*
            |--------------------------------------------------------------------------
            | VALIDATION HTML
            |--------------------------------------------------------------------------
            */

            const form =
                this;

            if (
                !form.checkValidity()
            ) {

                form.reportValidity();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDATION DATES
            |--------------------------------------------------------------------------
            */

            if (
                !calculerDuree()
            ) {

                afficherAlerte(
                    'danger',
                    'La date de retour doit être supérieure ou égale à la date de départ.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | AU MOINS UN AGENT
            |--------------------------------------------------------------------------
            */

            if (
                selectedAgents.length === 0
            ) {

                afficherAlerte(
                    'danger',
                    'Veuillez sélectionner au moins un agent participant à la mission.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | PAYS
            |--------------------------------------------------------------------------
            */

            const type =
                $('#type').val();

            const paysSelect =
                $('#pays_id');

            const regionSelect =
                $('#region_id');


            /*
            |--------------------------------------------------------------------------
            | VÉRIFICATION RÉGION
            |--------------------------------------------------------------------------
            */

            if (
                !regionSelect.val()
            ) {

                afficherAlerte(
                    'danger',
                    'Veuillez sélectionner une région.'
                );

                regionSelect
                    .focus();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | CONSTRUIRE FORMDATA
            |--------------------------------------------------------------------------
            */

            const formData =
                new FormData(form);


            /*
            |--------------------------------------------------------------------------
            | IMPORTANT :
            | PAYS DÉSACTIVÉ EN MISSION NATIONALE
            |--------------------------------------------------------------------------
            |
            | Un champ disabled n'est PAS envoyé par FormData.
            | On ajoute donc manuellement pays_id.
            |
            */

            if (
                type === 'nationale'
            ) {

                const mali =
                    trouverMali();

                if (!mali) {

                    afficherAlerte(
                        'danger',
                        'Le pays Mali est introuvable.'
                    );

                    return;
                }

                formData.set(
                    'pays_id',
                    mali.id
                );
            }
            else {

                formData.set(
                    'pays_id',
                    paysSelect.val() || ''
                );
            }


            /*
            |--------------------------------------------------------------------------
            | REGION
            |--------------------------------------------------------------------------
            */

            formData.set(
                'region_id',
                regionSelect.val()
            );


            /*
            |--------------------------------------------------------------------------
            | AGENTS
            |--------------------------------------------------------------------------
            |
            | On supprime les anciennes valeurs éventuelles
            | puis on ajoute chaque agent.
            |
            */

            formData.delete(
                'agent_ids[]'
            );

            selectedAgents.forEach(
                function (agentId) {

                    formData.append(
                        'agent_ids[]',
                        agentId
                    );

                }
            );


            /*
            |--------------------------------------------------------------------------
            | BOUTON
            |--------------------------------------------------------------------------
            */

            const button =
                $('#btnUpdate');

            const texteOriginal =
                button.html();


            button
                .prop('disabled', true)
                .html(`

                    <span
                        class="spinner-border spinner-border-sm me-2"
                        role="status"
                        aria-hidden="true"
                    ></span>

                    Enregistrement...

                `);


            /*
            |--------------------------------------------------------------------------
            | ENVOI AJAX
            |--------------------------------------------------------------------------
            */

            try {

                const response =
                    await fetch(
                        missionUpdateUrl,
                        {

                            method: 'POST',

                            headers: {

                                'X-CSRF-TOKEN':
                                    csrfToken,

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },

                            body: formData
                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | LIRE LA RÉPONSE
                |--------------------------------------------------------------------------
                */

                let data = null;

                try {

                    data =
                        await response.json();

                }
                catch (jsonError) {

                    throw new Error(
                        'Le serveur a retourné une réponse invalide.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | ERREUR HTTP
                |--------------------------------------------------------------------------
                */

                if (
                    !response.ok
                ) {

                    const error =
                        new Error(
                            data.message ||
                            'Une erreur est survenue.'
                        );

                    error.data =
                        data;

                    error.status =
                        response.status;

                    throw error;
                }


                /*
                |--------------------------------------------------------------------------
                | SUCCÈS
                |--------------------------------------------------------------------------
                */

                if (
                    data.success
                ) {

                    afficherAlerte(
                        'success',
                        data.message ||
                        'Mission modifiée avec succès.'
                    );

                    setTimeout(function () {

        window.location.href = "{{ route('missions.index') }}";

    }, 800);


                    /*
                    |--------------------------------------------------------------------------
                    | METTRE À JOUR LA DURÉE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        data.duree !== undefined
                    ) {

                        $('#duree')
                            .val(data.duree);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | APRÈS SUCCÈS
                    |--------------------------------------------------------------------------
                    |
                    | Aucun rechargement automatique.
                    |
                    | On laisse l'utilisateur sur la page
                    | pendant quelques secondes puis on peut
                    | proposer d'aller à la fiche.
                    |
                    */

                    button
                        .prop('disabled', false)
                        .html(texteOriginal);


                    /*
                    |--------------------------------------------------------------------------
                    | Message avec bouton vers la fiche
                    |--------------------------------------------------------------------------
                    */

                    setTimeout(
                        function () {

                            if (
                                data.redirect
                            ) {

                                $('#alertContainer')
                                    .append(`

                                        <div
                                            class="alert alert-info mt-2"
                                        >

                                            <i class="fas fa-info-circle me-1"></i>

                                            La mission a été enregistrée.

                                            <a
                                                href="${data.redirect}"
                                                class="btn btn-sm btn-outline-primary ms-2"
                                            >

                                                Voir la mission

                                            </a>

                                        </div>

                                    `);
                            }

                        },
                        500
                    );

                }
                else {

                    afficherAlerte(
                        'danger',
                        data.message ||
                        'La modification a échoué.'
                    );

                    button
                        .prop('disabled', false)
                        .html(texteOriginal);
                }

            }
            catch (error) {

                console.error(
                    'Erreur UPDATE mission :',
                    error
                );


                let message =
                    'Une erreur est survenue lors de la modification de la mission.';


                /*
                |--------------------------------------------------------------------------
                | MESSAGE SERVEUR
                |--------------------------------------------------------------------------
                */

                if (
                    error.data &&
                    error.data.message
                ) {

                    message =
                        error.data.message;
                }


                /*
                |--------------------------------------------------------------------------
                | ERREURS DE VALIDATION
                |--------------------------------------------------------------------------
                */

                if (
                    error.data &&
                    error.data.errors
                ) {

                    const erreurs =
                        error.data.errors;


                    let liste =
                        '<ul class="mb-0 mt-2">';


                    Object.keys(erreurs)
                        .forEach(
                            function (champ) {

                                erreurs[champ]
                                    .forEach(
                                        function (erreur) {

                                            liste +=
                                                `<li>${escapeHtml(erreur)}</li>`;

                                        }
                                    );
                            }
                        );


                    liste +=
                        '</ul>';


                    message +=
                        liste;
                }


                afficherAlerte(
                    'danger',
                    message
                );


                button
                    .prop('disabled', false)
                    .html(texteOriginal);
            }

        }
    );

});

</script>

@endsection
