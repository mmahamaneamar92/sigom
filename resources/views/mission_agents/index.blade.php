@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ==========================================================
         MESSAGES
    =========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Attention :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- ==========================================================
         INFORMATIONS DE LA MISSION
    =========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <i class="fas fa-briefcase"></i>
                    <strong>Gestion des agents de la mission</strong>
                </div>

                <a href="{{ route('missions.index') }}"
                   class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i>
                    Retour aux missions
                </a>

            </div>
        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="text-muted">
                        Numéro de mission
                    </label>

                    <div class="fw-bold">
                        {{ $mission->numero }}
                    </div>
                </div>


                <div class="col-md-5 mb-3">
                    <label class="text-muted">
                        Objet
                    </label>

                    <div class="fw-bold">
                        {{ $mission->objet }}
                    </div>
                </div>


                <div class="col-md-2 mb-3">
                    <label class="text-muted">
                        Type
                    </label>

                    <div>
                        @if($mission->type === 'internationale')
                            <span class="badge bg-info">
                                Internationale
                            </span>
                        @else
                            <span class="badge bg-success">
                                Nationale
                            </span>
                        @endif
                    </div>
                </div>


                <div class="col-md-2 mb-3">
                    <label class="text-muted">
                        Statut
                    </label>

                    <div>
                        <span class="badge bg-secondary">
                            {{ ucfirst($mission->statut) }}
                        </span>
                    </div>
                </div>

            </div>


            <hr>


            <div class="row">

                <div class="col-md-3">
                    <label class="text-muted">
                        Date de départ
                    </label>

                    <div class="fw-bold">
                        {{ \Carbon\Carbon::parse($mission->date_depart)->format('d/m/Y') }}
                    </div>
                </div>


                <div class="col-md-3">
                    <label class="text-muted">
                        Date de retour
                    </label>

                    <div class="fw-bold">
                        {{ \Carbon\Carbon::parse($mission->date_retour)->format('d/m/Y') }}
                    </div>
                </div>


                <div class="col-md-2">
                    <label class="text-muted">
                        Durée
                    </label>

                    <div class="fw-bold">
                        {{ $mission->duree ?? '-' }} jour(s)
                    </div>
                </div>


                <div class="col-md-2">
                    <label class="text-muted">
                        Pays
                    </label>

                    <div class="fw-bold">
                        {{ $mission->pays->nom ?? '-' }}
                    </div>
                </div>


                <div class="col-md-2">
                    <label class="text-muted">
                        Région
                    </label>

                    <div class="fw-bold">
                        {{ $mission->region->nom ?? '-' }}
                    </div>
                </div>

            </div>


            @if($mission->pays && $mission->pays->zoneMajoration)

                <hr>

                <div class="alert alert-info mb-0">

                    <i class="fas fa-percentage"></i>

                    Zone de majoration :
                    <strong>
                        {{ $mission->pays->zoneMajoration->libelle }}
                    </strong>

                    — Taux :

                    <strong>
                        {{ number_format(
                            $mission->pays->zoneMajoration->taux,
                            2,
                            ',',
                            ' '
                        ) }} %
                    </strong>

                </div>

            @endif

        </div>
    </div>



    {{-- ==========================================================
         AJOUTER UN AGENT
    =========================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-success text-white">
            <i class="fas fa-user-plus"></i>
            <strong>Ajouter un agent à la mission</strong>
        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('mission-agents.store', $mission->id) }}"
                  id="missionAgentForm">

                @csrf


                <div class="row">

                    {{-- ==================================================
                         SELECTION AGENT
                    =================================================== --}}

                    <div class="col-md-6 mb-3">

                        <label for="agent_id" class="form-label">
                            Agent <span class="text-danger">*</span>
                        </label>

                        <select name="agent_id"
                                id="agent_id"
                                class="form-select @error('agent_id') is-invalid @enderror"
                                required>

                            <option value="">
                                -- Sélectionner un agent --
                            </option>

                            @foreach($agents as $agent)

                                <option value="{{ $agent->id }}">

                                    {{ $agent->matricule }}
                                    -
                                    {{ $agent->prenom }}
                                    {{ $agent->nom }}

                                </option>

                            @endforeach

                        </select>

                        @error('agent_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- ==================================================
                         NOMBRE DE JOURS
                    =================================================== --}}

                    <div class="col-md-3 mb-3">

                        <label for="nombre_jours" class="form-label">
                            Nombre de jours
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="nombre_jours"
                               id="nombre_jours"
                               class="form-control"
                               min="1"
                               value="{{ old('nombre_jours', $mission->duree ?? 1) }}"
                               required>

                    </div>


                    {{-- ==================================================
                         TAUX MAJORATION
                    =================================================== --}}

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Taux de majoration
                        </label>

                        <div class="input-group">

                            <input type="text"
                                   id="taux_majoration"
                                   class="form-control"
                                   value="{{ $mission->pays->zoneMajoration->taux ?? 0 }}"
                                   readonly>

                            <span class="input-group-text">
                                %
                            </span>

                        </div>

                    </div>

                </div>



                {{-- ======================================================
                     INFORMATIONS AGENT
                ======================================================= --}}

                <div id="agentInformations"
                     class="alert alert-light border d-none">

                    <h6 class="border-bottom pb-2 mb-3">
                        <i class="fas fa-user"></i>
                        Informations de l'agent
                    </h6>


                    <div class="row">

                        <div class="col-md-3 mb-2">
                            <small class="text-muted">
                                Matricule
                            </small>

                            <div id="agentMatricule"
                                 class="fw-bold">
                                -
                            </div>
                        </div>


                        <div class="col-md-3 mb-2">
                            <small class="text-muted">
                                Nom complet
                            </small>

                            <div id="agentNom"
                                 class="fw-bold">
                                -
                            </div>
                        </div>


                        <div class="col-md-3 mb-2">
                            <small class="text-muted">
                                Fonction
                            </small>

                            <div id="agentFonction"
                                 class="fw-bold">
                                -
                            </div>
                        </div>


                        <div class="col-md-3 mb-2">
                            <small class="text-muted">
                                Catégorie
                            </small>

                            <div id="agentCategorie"
                                 class="fw-bold">
                                -
                            </div>
                        </div>


                        <div class="col-md-6 mb-2">
                            <small class="text-muted">
                                Service
                            </small>

                            <div id="agentService"
                                 class="fw-bold">
                                -
                            </div>
                        </div>


                        <div class="col-md-6 mb-2">
                            <small class="text-muted">
                                Direction
                            </small>

                            <div id="agentDirection"
                                 class="fw-bold">
                                -
                            </div>
                        </div>

                    </div>

                </div>



                {{-- ======================================================
                     CALCUL FINANCIER
                ======================================================= --}}

                <div class="card bg-light border mb-3">

                    <div class="card-header">
                        <strong>
                            <i class="fas fa-calculator"></i>
                            Estimation financière
                        </strong>
                    </div>


                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <label class="text-muted">
                                    Frais journalier
                                </label>

                                <div class="input-group">

                                    <input type="text"
                                           id="frais_journalier"
                                           class="form-control"
                                           value="50 000"
                                           readonly>

                                    <span class="input-group-text">
                                        FCFA
                                    </span>

                                </div>

                            </div>


                            <div class="col-md-3 mb-3">

                                <label class="text-muted">
                                    Indemnité journalière
                                </label>

                                <div class="input-group">

                                    <input type="text"
                                           id="indemnite_journaliere"
                                           class="form-control"
                                           value="150 000"
                                           readonly>

                                    <span class="input-group-text">
                                        FCFA
                                    </span>

                                </div>

                            </div>


                            <div class="col-md-3 mb-3">

                                <label class="text-muted">
                                    Montant majoration
                                </label>

                                <div class="input-group">

                                    <input type="text"
                                           id="montant_majoration"
                                           class="form-control"
                                           value="0"
                                           readonly>

                                    <span class="input-group-text">
                                        FCFA
                                    </span>

                                </div>

                            </div>


                            <div class="col-md-3 mb-3">

                                <label class="text-muted">
                                    Total agent
                                </label>

                                <div class="input-group">

                                    <input type="text"
                                           id="montant_total"
                                           class="form-control fw-bold"
                                           value="0"
                                           readonly>

                                    <span class="input-group-text">
                                        FCFA
                                    </span>

                                </div>

                            </div>

                        </div>


                        <div class="alert alert-warning mb-0">

                            <i class="fas fa-info-circle"></i>

                            <strong>Formule :</strong>

                            Frais =
                            50 000 × nombre de jours

                            <br>

                            Indemnité =
                            150 000 × nombre de jours

                            <br>

                            Majoration =
                            Indemnité × taux de majoration

                            <br>

                            <strong>
                                Total =
                                Frais + Indemnité + Majoration
                            </strong>

                        </div>

                    </div>

                </div>



                {{-- ======================================================
                     BOUTONS
                ======================================================= --}}

                <div class="d-flex justify-content-end">

                    <button type="reset"
                            class="btn btn-secondary me-2"
                            id="btnReset">

                        <i class="fas fa-undo"></i>
                        Réinitialiser

                    </button>


                    <button type="submit"
                            class="btn btn-success">

                        <i class="fas fa-user-plus"></i>
                        Affecter l'agent

                    </button>

                </div>

            </form>

        </div>

    </div>



    {{-- ==========================================================
         AGENTS DEJA AFFECTES
    =========================================================== --}}

    <div class="card shadow-sm">

        <div class="card-header bg-dark text-white">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    <i class="fas fa-users"></i>
                    Agents affectés à la mission
                </strong>

                <span class="badge bg-light text-dark">

                    {{ $mission->missionAgents->count() }}

                    agent(s)

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($mission->missionAgents->count() > 0)

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Matricule</th>

                                <th>Agent</th>

                                <th>Service</th>

                                <th>Direction</th>

                                <th>Jours</th>

                                <th>Frais</th>

                                <th>Indemnité</th>

                                <th>Majoration</th>

                                <th>Total</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($mission->missionAgents as $index => $missionAgent)

                                <tr>

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>
                                        {{ $missionAgent->agent->matricule ?? '-' }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $missionAgent->agent->prenom ?? '' }}
                                            {{ $missionAgent->agent->nom ?? '' }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            {{ $missionAgent->agent->fonction ?? '-' }}

                                        </small>

                                    </td>


                                    <td>

                                        {{ $missionAgent->agent->service->nom_service ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $missionAgent->agent->service->direction->nom_direction ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $missionAgent->nombre_jours }}

                                    </td>


                                    <td>

                                        {{ number_format(
                                            $missionAgent->frais_journalier * $missionAgent->nombre_jours,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>


                                    <td>

                                        {{ number_format(
                                            $missionAgent->indemnite_journaliere * $missionAgent->nombre_jours,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>


                                    <td>

                                        {{ number_format(
                                            $missionAgent->montant_majoration,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                        <br>

                                        <small class="text-muted">

                                            {{ $missionAgent->taux_majoration }} %

                                        </small>

                                    </td>


                                    <td>

                                        <strong>

                                            {{ number_format(
                                                $missionAgent->montant_total,
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </strong>

                                    </td>


                                    <td>

                                        <div class="d-flex">

                                            <a href="{{ route('mission-agents.edit', $missionAgent->id) }}"
                                               class="btn btn-sm btn-warning me-1">

                                                <i class="fas fa-edit"></i>

                                            </a>


                                            <form method="POST"
                                                  action="{{ route('mission-agents.destroy', $missionAgent->id) }}"
                                                  onsubmit="return confirm('Voulez-vous vraiment retirer cet agent de la mission ?');">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-danger">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>


                        <tfoot class="table-light">

                            <tr>

                                <th colspan="9"
                                    class="text-end">

                                    TOTAL DE LA MISSION

                                </th>

                                <th colspan="2">

                                    <strong class="text-success">

                                        {{ number_format(
                                            $mission->montant_total,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </strong>

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            @else

                <div class="text-center p-5">

                    <i class="fas fa-users fa-3x text-muted mb-3"></i>

                    <h5>
                        Aucun agent affecté
                    </h5>

                    <p class="text-muted mb-0">

                        Aucun agent n'est encore affecté à cette mission.

                    </p>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- ==============================================================
     JAVASCRIPT
=============================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const agentSelect = document.getElementById('agent_id');

    const nombreJours = document.getElementById('nombre_jours');

    const tauxMajoration = document.getElementById('taux_majoration');

    const montantMajoration = document.getElementById('montant_majoration');

    const montantTotal = document.getElementById('montant_total');

    const agentInformations =
        document.getElementById('agentInformations');


    /*
    |--------------------------------------------------------------------------
    | Données des agents
    |--------------------------------------------------------------------------
    */

    const agents = @json($agents);


    /*
    |--------------------------------------------------------------------------
    | Fonctions utilitaires
    |--------------------------------------------------------------------------
    */

    function formatMoney(value)
    {
        return new Intl.NumberFormat('fr-FR', {
            maximumFractionDigits: 0
        }).format(value);
    }


    /*
    |--------------------------------------------------------------------------
    | Afficher les informations de l'agent
    |--------------------------------------------------------------------------
    */

    agentSelect.addEventListener('change', function () {

        const agentId = this.value;

        const agent = agents.find(
            item => String(item.id) === String(agentId)
        );


        if (!agent) {

            agentInformations.classList.add('d-none');

            return;
        }


        agentInformations.classList.remove('d-none');


        document.getElementById('agentMatricule').textContent =
            agent.matricule ?? '-';

        document.getElementById('agentNom').textContent =
            `${agent.prenom ?? ''} ${agent.nom ?? ''}`;

        document.getElementById('agentFonction').textContent =
            agent.fonction ?? '-';

        document.getElementById('agentCategorie').textContent =
            agent.categorie ?? '-';


        /*
        |--------------------------------------------------------------------------
        | Attention
        |--------------------------------------------------------------------------
        | Les relations service/direction ne sont pas forcément chargées
        | par Agent::orderBy()->get().
        |
        | On utilise donc les données disponibles directement.
        |--------------------------------------------------------------------------
        */

        if (agent.service) {

            if (typeof agent.service === 'object') {

                document.getElementById('agentService').textContent =
                    agent.service.nom_service ?? '-';

                if (agent.service.direction) {

                    if (typeof agent.service.direction === 'object') {

                        document.getElementById('agentDirection').textContent =
                            agent.service.direction.nom_direction ?? '-';

                    } else {

                        document.getElementById('agentDirection').textContent =
                            agent.service.direction;

                    }

                } else {

                    document.getElementById('agentDirection').textContent =
                        '-';

                }

            } else {

                document.getElementById('agentService').textContent =
                    agent.service;

                document.getElementById('agentDirection').textContent =
                    '-';

            }

        } else {

            document.getElementById('agentService').textContent =
                '-';

            document.getElementById('agentDirection').textContent =
                '-';

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Calcul financier
    |--------------------------------------------------------------------------
    */

    function calculerMontant()
    {
        const jours = parseInt(nombreJours.value) || 0;

        const fraisJournalier = 50000;

        const indemniteJournaliere = 150000;

        const taux =
            parseFloat(tauxMajoration.value) || 0;


        const frais =
            fraisJournalier * jours;


        const indemnite =
            indemniteJournaliere * jours;


        const majoration =
            indemnite * (taux / 100);


        const total =
            frais +
            indemnite +
            majoration;


        montantMajoration.value =
            formatMoney(majoration);


        montantTotal.value =
            formatMoney(total);
    }


    nombreJours.addEventListener(
        'input',
        calculerMontant
    );


    /*
    |--------------------------------------------------------------------------
    | Calcul initial
    |--------------------------------------------------------------------------
    */

    calculerMontant();


    /*
    |--------------------------------------------------------------------------
    | Réinitialisation
    |--------------------------------------------------------------------------
    */

    document.getElementById('btnReset')
        .addEventListener('click', function () {

            setTimeout(function () {

                agentInformations.classList.add('d-none');

                document.getElementById('agentMatricule')
                    .textContent = '-';

                document.getElementById('agentNom')
                    .textContent = '-';

                document.getElementById('agentFonction')
                    .textContent = '-';

                document.getElementById('agentCategorie')
                    .textContent = '-';

                document.getElementById('agentService')
                    .textContent = '-';

                document.getElementById('agentDirection')
                    .textContent = '-';

                calculerMontant();

            }, 50);

        });


});

</script>

@endsection