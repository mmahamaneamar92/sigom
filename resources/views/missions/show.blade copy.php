@extends('layouts.ap')

@section('title', 'Détails de la mission')

@section('content')

<div class="container-fluid py-4">

    {{-- ============================================================
        EN-TÊTE
    ============================================================ --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="fas fa-file-alt me-2"></i>
                Détails de la mission
            </h3>

            <div class="text-muted">
                Mission :
                <strong>{{ $mission->numero }}</strong>
            </div>
        </div>

        <div>
            <a href="{{ route('missions.index') }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>
                Retour

            </a>
        </div>

    </div>


    {{-- ============================================================
        ALERTES
    ============================================================ --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
        ERREURS DE VALIDATION
    ============================================================ --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                <i class="fas fa-exclamation-triangle me-2"></i>
                Des erreurs sont survenues :
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ============================================================
        WORKFLOW
    ============================================================ --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="fas fa-project-diagram me-2"></i>

                État de la mission

            </h5>

        </div>


        <div class="card-body">

            @php

                $statuts = [

                    'brouillon' =>
                        'Brouillon',

                    'en_attente_rh' =>
                        'En attente RH',

                    'en_attente_moyens_generaux' =>
                        'En attente Moyens généraux',

                    'en_attente_rh_apres_mg' =>
                        'Retour RH',

                    'en_attente_budget' =>
                        'En attente Budget',

                    'en_attente_comptabilite' =>
                        'En attente Comptabilité',

                    'payee' =>
                        'Payée',

                    'terminee' =>
                        'Terminée',

                    'annulee' =>
                        'Annulée',

                ];

            @endphp


            <div class="d-flex flex-wrap gap-2">

                @foreach($statuts as $key => $label)

                    @php

                        $active =
                            $mission->statut === $key;

                        $class = match($key) {

                            'brouillon'
                                => 'secondary',

                            'en_attente_rh'
                                => 'warning',

                            'en_attente_moyens_generaux'
                                => 'info',

                            'en_attente_rh_apres_mg'
                                => 'primary',

                            'en_attente_budget'
                                => 'warning',

                            'en_attente_comptabilite'
                                => 'primary',

                            'payee'
                                => 'success',

                            'terminee'
                                => 'success',

                            'annulee'
                                => 'danger',

                            default
                                => 'secondary',

                        };

                    @endphp


                    <span class="badge bg-{{ $active ? $class : 'light text-dark' }} p-2">

                        {{ $label }}

                    </span>

                @endforeach

            </div>

        </div>

    </div>


    {{-- ============================================================
        INFORMATIONS GÉNÉRALES
    ============================================================ --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-info-circle me-2"></i>

                Informations générales

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <label class="text-muted small">
                        Numéro de mission
                    </label>

                    <div class="fw-bold">
                        {{ $mission->numero }}
                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Numéro d'ordre
                    </label>

                    <div class="fw-bold">

                        {{ $mission->numero_ordre ?: 'Non attribué' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Type
                    </label>

                    <div>

                        @if($mission->type === 'nationale')

                            <span class="badge bg-info">
                                Nationale
                            </span>

                        @else

                            <span class="badge bg-primary">
                                Internationale
                            </span>

                        @endif

                    </div>

                </div>


                <div class="col-md-12">

                    <label class="text-muted small">
                        Objet
                    </label>

                    <div class="fw-bold">
                        {{ $mission->objet }}
                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Direction
                    </label>

                    <div>

                        {{ $mission->direction ?: 'Non renseignée' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Pays
                    </label>

                    <div>

                        {{ optional($mission->pays)->name ?: 'Mali' }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Région
                    </label>

                    <div>

                        {{ optional($mission->region)->nom ?: 'Non renseignée' }}

                    </div>

                </div>


                <div class="col-md-12">

                    <label class="text-muted small">
                        Destination
                    </label>

                    <div>
                        {{ $mission->destination }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        PÉRIODE
    ============================================================ --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-calendar-alt me-2"></i>

                Période de la mission

            </h5>

        </div>


        <div class="card-body">

            <div class="row">

                <div class="col-md-4">

                    <label class="text-muted small">
                        Date de départ
                    </label>

                    <div class="fw-bold">

                        {{ optional($mission->date_depart)->format('d/m/Y') }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Date de retour
                    </label>

                    <div class="fw-bold">

                        {{ optional($mission->date_retour)->format('d/m/Y') }}

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="text-muted small">
                        Durée
                    </label>

                    <div class="fw-bold">

                        {{ $mission->duree }} jour(s)

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        PARTICIPANTS
    ============================================================ --}}
    <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0">
            <i class="fas fa-users me-2 text-primary"></i>
            Participants à la mission
        </h5>
    </div>


<div class="card-body p-0">

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle mb-0">

            <thead class="table-light text-center">

                <tr>
                    <th rowspan="2">Participant</th>
                    <th colspan="2">Frais de mission</th>
                    <th colspan="2">Hébergement</th>
                    <th rowspan="2">Sous-total</th>
                    <th rowspan="2">Taux<br>majoration</th>
                    <th rowspan="2">Total général</th>
                    <th rowspan="2">Decharge</th>
                </tr>

                <tr>
                    <th>Montant / jour</th>
                    <th>Nombre de jours</th>
                    <th>Montant / nuit</th>
                    <th>Nombre de nuits</th>
                </tr>

            </thead>

            <tbody>

            @forelse($mission->missionAgents as $missionAgent)

                @php

                    $agent = $missionAgent->agent;

                    /*
                     * Montant des frais par jour
                     */
                    $montantJour = (float) (
                        $missionAgent->frais_journalier ?? 0
                    );

                    /*
                     * Nombre de jours
                     */
                    $nombreJours = (int) (
                        $missionAgent->nombre_jours
                        ?? $mission->duree
                        ?? 0
                    );

                    /*
                     * Montant hébergement par nuit.
                     *
                     * Si ton champ porte un autre nom dans
                     * mission_agents, remplace simplement
                     * "montant_nuit".
                     */
                    $montantNuit = (float) (
                        $missionAgent->indemnite_journaliere ?? 0
                    );

                    /*
                     * Nombre de nuits.
                     *
                     * Par défaut, on prend nombre_jours - 1.
                     */
                    $nombreNuits = (int) (
                        $missionAgent->nombre_nuits
                        ?? max($nombreJours - 1, 0)
                    );

                    /*
                     * Frais de mission
                     */
                    $totalFrais = $montantJour * $nombreJours;

                    /*
                     * Hébergement
                     */
                    $totalNuits = $montantNuit * $nombreNuits;

                    /*
                     * Indemnité journalière
                     */
                    $indemniteJournaliere = (float) (
                        $missionAgent->indemnite_journaliere ?? 0
                    );

                    /*
                     * Indemnité de mission
                     */
                    $indemniteMission = $totalFrais
                        + $totalNuits;

                    /*
                     * Sous-total avant majoration
                     */
                    $sousTotal = $indemniteMission;

                    /*
                     * Taux de majoration
                     */
                    $tauxMajoration = (float) (
                        $missionAgent->taux_majoration ?? 0
                    );

                    /*
                     * Montant de la majoration
                     */
                    $montantMajoration =
                        $sousTotal * ($tauxMajoration / 100);

                    /*
                     * Total général du participant
                     */
                    $totalGeneral =
                        $sousTotal + $montantMajoration;

                @endphp

                @if($agent)

                    <tr>

                        {{-- Participant --}}
                        <td>

                            <div class="fw-bold">
                                {{ $agent->prenom }}
                                {{ $agent->nom }}
                            </div>

                        </td>

                        {{-- Montant / jour --}}
                        <td class="text-end">

                            <strong>
                                {{ number_format(
                                    $montantJour,
                                    0,
                                    ',',
                                    ' '
                                ) }}
                            </strong>

                            <div class="small text-muted">
                                FCFA
                            </div>

                        </td>

                        {{-- Nombre de jours --}}
                        <td class="text-center">

                            <span class="badge bg-secondary">
                                {{ $nombreJours }}
                                {{ $nombreJours > 1 ? 'jours' : 'jour' }}
                            </span>

                        </td>

                        {{-- Montant / nuit --}}
                        <td class="text-end">

                            <strong>
                                {{ number_format(
                                    $montantNuit,
                                    0,
                                    ',',
                                    ' '
                                ) }}
                            </strong>

                            <div class="small text-muted">
                                FCFA
                            </div>

                        </td>

                        {{-- Nombre de nuits --}}
                        <td class="text-center">

                            <span class="badge bg-secondary">
                                {{ $nombreNuits }}
                                {{ $nombreNuits > 1 ? 'nuits' : 'nuit' }}
                            </span>

                        </td>

                        {{-- Sous-total --}}
                        <td class="text-end">

                            <strong class="text-primary">
                                {{ number_format(
                                    $indemniteMission,
                                    0,
                                    ',',
                                    ' '
                                ) }}
                            </strong>

                            <div class="small text-muted">
                                FCFA
                            </div>

                        </td>

                        {{-- Taux majoration --}}
                        <td class="text-center">

                            @if($tauxMajoration > 0)

                                <span class="badge bg-warning text-dark">
                                    {{ number_format(
                                        $tauxMajoration,
                                        0
                                    ) }} %
                                </span>

                                <div class="small text-muted mt-1">
                                    +
                                    {{ number_format(
                                        $montantMajoration,
                                        0,
                                        ',',
                                        ' '
                                    ) }}
                                    FCFA
                                </div>

                            @else

                                <span class="badge bg-secondary">
                                    0 %
                                </span>

                            @endif

                        </td>


                        {{-- Total général --}}
                        <td class="text-end">

                            <strong class="text-success fs-6">
                                {{ number_format(
                                    $totalGeneral,
                                    0,
                                    ',',
                                    ' '
                                ) }}
                            </strong>

                            <div class="small text-muted">
                                FCFA
                            </div>

                        </td>

                        {{-- Payement Par Agent --}}
                        <td class="text-end">

                            <a href="{{ route('missions.pdf.decharge', [
                                'mission' => $mission->id,
                                'agent' => $missionAgent->agent_id
                            ]) }}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-pdf"></i>
                                Décharge
                            </a>

                        </td>

                    </tr>

                @endif

            @empty

                <tr>
                    <td colspan="9"
                        class="text-center text-muted py-4">

                        <i class="fas fa-users-slash fa-2x mb-2"></i>

                        <div>
                            Aucun participant enregistré.
                        </div>

                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


</div>



    {{-- ============================================================
        CARBURANT / TOTAL GÉNÉRAL
    ============================================================ --}}
    @php

        $carburant = $mission->carburant;

        $montantCarburant = $carburant
            ? (float) $carburant->sous_total
            : 0;

        $montantTotalGeneral =
            (float) ($mission->montant_total_general ?? 0);

        if ($montantTotalGeneral <= 0) {

            $montantTotalGeneral =
                (float) ($mission->montant_total ?? 0)
                + $montantCarburant;

        }

    @endphp


    @if($mission->type === 'nationale')

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <h5 class="mb-0">

                    <i class="fas fa-gas-pump me-2 text-warning"></i>

                    Détails carburant

                </h5>

            </div>


            <div class="card-body p-0">

                @if($carburant)

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>Nombre de véhicules</th>

                                    <th>Carburant</th>

                                    <th>Carburant ville</th>

                                    <th>Prix/Litre</th>

                                    <th>Montant carburant</th>

                                    <th>Montant carburant ville</th>

                                    <th>Sous-total</th>

                                    <th>Montant mission</th>

                                    <th>Total général</th>

                                </tr>

                            </thead>


                            <tbody>

                                <tr>

                                    <td>
                                        {{ $carburant->nombre_voitures }}
                                    </td>

                                    <td>

                                        {{ number_format(
                                            $carburant->quantite_carburant,
                                            2,
                                            ',',
                                            ' '
                                        ) }}

                                        L

                                    </td>


                                    <td>

                                        {{ number_format(
                                            $carburant->quantite_carburant_ville,
                                            2,
                                            ',',
                                            ' '
                                        ) }}

                                        L

                                    </td>


                                    <td>

                                        {{ number_format(
                                            $carburant->prix_litre,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>


                                    <td class="text-end">

                                        {{ number_format(
                                            $carburant->montant_carburant,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>


                                    <td class="text-end">

                                        {{ number_format(
                                            $carburant->montant_carburant_ville,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>


                                    <td class="text-end fw-bold">

                                        {{ number_format(
                                            $carburant->sous_total,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>


                                    <td class="text-end">

                                        {{ number_format(
                                            $mission->montant_total,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </td>


                                    <td class="text-end">

                                        <strong class="text-success fs-5">

                                            {{ number_format(
                                                $mission->montant_total_general,
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </strong>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="alert alert-warning m-3 mb-0">

                        <i class="fas fa-exclamation-triangle me-2"></i>

                        Les informations relatives au carburant
                        n'ont pas encore été enregistrées.

                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- ============================================================
        INFORMATIONS COMPLÉMENTAIRES INTERNATIONALES
    ============================================================ --}}
    @if($mission->type === 'internationale')

        @php

            $totalParticipationInternational = 0;
            $totalVisaInternational = 0;
            $totalBilletsInternational = 0;
            $totalBudgetInternational = 0;

            foreach($mission->budgetsInternationaux as $budget) {

                $totalParticipationInternational +=
                    (float) $budget->frais_participation;

                $totalVisaInternational +=
                    (float) $budget->frais_visa;

                $totalBilletsInternational +=
                    (float) $budget->billet_avion;

                $totalBudgetInternational +=
                    (float) $budget->montant_total;

            }

        @endphp


        <div class="card shadow-sm mb-4">

            <div class="card-header bg-light">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">

                        <i class="fas fa-plane-departure me-2 text-primary"></i>

                        Informations complémentaires
                        de la mission internationale

                    </h5>


                    @if($mission->statut === 'en_attente_budget')

                        <button type="button"
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBudgetInternational">

                            <i class="fas fa-edit me-1"></i>

                            {{ $mission->budgetsInternationaux->count() > 0
                                ? 'Modifier'
                                : 'Renseigner'
                            }}

                        </button>

                    @endif

                </div>

            </div>


            <div class="card-body p-0">

                @if($mission->budgetsInternationaux->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>#</th>

                                    <th>Participant</th>

                                    <th>Matricule</th>

                                    <th>Classe billet</th>

                                    <th class="text-end">
                                        Frais participation
                                    </th>

                                    <th class="text-end">
                                        Frais visa
                                    </th>

                                    <th class="text-end">
                                        Billet d'avion
                                    </th>

                                    <th class="text-end">
                                        Total
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($mission->budgetsInternationaux as $index => $budget)

                                    <tr>

                                        <td>
                                            {{ $index + 1 }}
                                        </td>


                                        <td>

                                            <strong>

                                                {{ optional($budget->agent)->prenom }}

                                                {{ optional($budget->agent)->nom }}

                                            </strong>

                                        </td>


                                        <td>

                                            {{ optional($budget->agent)->matricule ?? '-' }}

                                        </td>


                                        <td>

                                            @if($budget->classe_billet === 'affaires')

                                                <span class="badge bg-primary">

                                                    Classe affaires

                                                </span>

                                            @else

                                                <span class="badge bg-secondary">

                                                    Classe économique

                                                </span>

                                            @endif

                                        </td>


                                        <td class="text-end">

                                            {{ number_format(
                                                $budget->frais_participation,
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </td>


                                        <td class="text-end">

                                            {{ number_format(
                                                $budget->frais_visa,
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </td>


                                        <td class="text-end">

                                            {{ number_format(
                                                $budget->billet_avion,
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </td>


                                        <td class="text-end fw-bold">

                                            {{ number_format(
                                                $budget->montant_total,
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            FCFA

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>


                            <tfoot>

                                <tr class="table-primary">

                                    <th colspan="4"
                                        class="text-end">

                                        Total

                                    </th>


                                    <th class="text-end">

                                        {{ number_format(
                                            $totalParticipationInternational,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </th>


                                    <th class="text-end">

                                        {{ number_format(
                                            $totalVisaInternational,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </th>


                                    <th class="text-end">

                                        {{ number_format(
                                            $totalBilletsInternational,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </th>


                                    <th class="text-end text-success">

                                        {{ number_format(
                                            $totalBudgetInternational,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>


                @else

                    <div class="alert alert-warning m-3">

                        <i class="fas fa-exclamation-triangle me-2"></i>

                        Aucune information complémentaire
                        internationale n'a encore été renseignée.

                        @if($mission->statut === 'en_attente_budget')

                            Cliquez sur
                            <strong>« Renseigner »</strong>
                            pour compléter le budget.

                        @endif

                    </div>

                @endif

            </div>

        </div>

    @endif


    {{-- ============================================================
        RÉCAPITULATIF FINANCIER
    ============================================================ --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header bg-success text-white">

            <h5 class="mb-0">

                <i class="fas fa-money-bill-wave me-2"></i>

                Récapitulatif financier

            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">


                {{-- Montant mission --}}
                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">

                            Montant de la mission

                        </div>

                        <div class="fw-bold fs-5">

                            {{ number_format(
                                $mission->montant_total,
                                0,
                                ',',
                                ' '
                            ) }}

                            FCFA

                        </div>

                    </div>

                </div>


                {{-- Frais complémentaires --}}
                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="text-muted small">

                            @if($mission->type === 'nationale')
                                Carburant
                            @else
                                Frais internationaux
                            @endif

                        </div>


                        <div class="fw-bold fs-5">

                            @if($mission->type === 'nationale')

                                {{ number_format(
                                    $mission->montant_carburant ?? $montantCarburant,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                            @else

                                {{ number_format(
                                    $totalBudgetInternational,
                                    0,
                                    ',',
                                    ' '
                                ) }}

                            @endif

                            FCFA

                        </div>

                    </div>

                </div>


                {{-- Total général --}}
                <div class="col-md-4">

                    <div class="border border-success rounded p-3 h-100">

                        <div class="text-muted small">

                            Montant total général

                        </div>


                        <div class="fw-bold fs-4 text-success">

                            {{ number_format(
                                $mission->montant_total_general > 0
                                    ? $mission->montant_total_general
                                    : $mission->montant_total,
                                0,
                                ',',
                                ' '
                            ) }}

                            FCFA

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        VÉHICULE
    ============================================================ --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-car me-2"></i>

                Moyen de transport

            </h5>

        </div>


        <div class="card-body">

            @if($mission->voiture)

                <div class="row">

                    <div class="col-md-4">

                        <label class="text-muted small">
                            Véhicule
                        </label>

                        <div class="fw-bold">

                            {{ $mission->voiture->immatriculation ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="text-muted small">
                            Marque
                        </label>

                        <div>

                            {{ $mission->voiture->marque ?? '-' }}

                        </div>

                    </div>


                    <div class="col-md-4">

                        <label class="text-muted small">
                            Modèle
                        </label>

                        <div>

                            {{ $mission->voiture->modele ?? '-' }}

                        </div>

                    </div>

                </div>

            @else

                <span class="text-muted">

                    Aucun véhicule affecté.

                </span>

            @endif

        </div>

    </div>


    {{-- ============================================================
        PAIEMENT
    ============================================================ --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-credit-card me-2"></i>

                Paiement

            </h5>

        </div>


        <div class="card-body">

            @php

                $montantAPayer =
                    $mission->montant_total_general > 0
                        ? $mission->montant_total_general
                        : $mission->montant_total;

            @endphp


            <div class="row">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Montant à payer
                    </div>

                    <div class="fw-bold fs-4 text-primary">

                        {{ number_format(
                            $montantAPayer,
                            0,
                            ',',
                            ' '
                        ) }}

                        FCFA

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Montant payé
                    </div>

                    <div class="fw-bold fs-5">

                        {{ number_format(
                            $mission->montant_paye ?? 0,
                            0,
                            ',',
                            ' '
                        ) }}

                        FCFA

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="text-muted small">
                        Reste à payer
                    </div>

                    <div class="fw-bold fs-5 text-danger">

                        {{ number_format(
                            max(
                                0,
                                $montantAPayer -
                                ($mission->montant_paye ?? 0)
                            ),
                            0,
                            ',',
                            ' '
                        ) }}

                        FCFA

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        ACTIONS
    ============================================================ --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-cogs me-2"></i>

                Actions

            </h5>

        </div>


        <div class="card-body">


            {{-- ====================================================
                RH
            ==================================================== --}}
            @if($mission->statut === 'en_attente_rh')

                <div class="d-flex gap-2 flex-wrap">

                    <button type="button"
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#modalValidationRH">

                        <i class="fas fa-check-circle me-1"></i>

                        Valider RH

                    </button>

                </div>

            @endif

           


            
{{-- ====================================================
    MOYENS GÉNÉRAUX
==================================================== --}}
@if(
    $mission->statut === 'en_attente_moyens_generaux' &&
    in_array(
        strtolower(trim(auth()->user()->profil ?? '')),
        ['moyens_generaux', 'admin']
    )
)

    <div class="d-flex gap-2 flex-wrap">

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalAffecterVehicule"
        >

            <i class="fas fa-car me-1"></i>

            Affecter véhicule

        </button>

    </div>

@endif




            {{-- ====================================================
                RETOUR RH APRÈS MG
            ==================================================== --}}
            @if($mission->statut === 'en_attente_rh_apres_mg')

                <div class="d-flex gap-2 flex-wrap">

                    <form method="POST"
                          action="{{ route('missions.envoyer-budget', $mission->id) }}"
                          class="form-transition-rh-budget">

                        @csrf

                        <button type="submit"
                                class="btn btn-success"
                                >

                            <i class="fas fa-paper-plane me-1"></i>

                            Transmettre au Budget

                        </button>

                    </form>

                </div>

            @endif


            {{-- ====================================================
                BUDGET
            ==================================================== --}}
            @if($mission->statut === 'en_attente_budget')

                @php

                    if($mission->type === 'nationale') {

                        $budgetPret =
                            $mission->carburant !== null;

                    } else {

                        $participantsBudget =
                            $mission->missionAgents
                                ->filter(
                                    fn($ma) =>
                                        $ma->agent !== null
                                );

                        $nombreParticipants =
                            $participantsBudget->count();

                        $nombreBudgets =
                            $mission->budgetsInternationaux->count();

                        $budgetPret =
                            $nombreParticipants > 0 &&
                            $nombreBudgets ===
                            $nombreParticipants;

                    }

                @endphp


                <div class="d-flex flex-wrap gap-2">


                    {{-- Mission nationale --}}
                    @if($mission->type === 'nationale')

                        <button type="button"
                                class="btn btn-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#modalGestionCarburant">

                            <i class="fas fa-gas-pump me-1"></i>

                            {{ $mission->carburant
                                ? 'Modifier le carburant'
                                : 'Renseigner le carburant'
                            }}

                        </button>


                    {{-- Mission internationale --}}
                    @else

                        <button type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalBudgetInternational">

                            <i class="fas fa-plane-departure me-1"></i>

                            {{ $budgetPret
                                ? 'Modifier les informations internationales'
                                : 'Renseigner les informations internationales'
                            }}

                        </button>

                    @endif


                    {{-- Validation Budget --}}
                    @if($budgetPret)

                        <form method="POST"
                              action="{{ route('missions.valider-budget', ['id' => $mission->id]) }}"
                              class="d-inline form-validation-budget">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success"
                                    >

                                <i class="fas fa-check-double me-1"></i>

                                Valider Budget

                            </button>

                        </form>

                    @else

                        <button type="button"
                                class="btn btn-secondary"
                                disabled>

                            <i class="fas fa-lock me-1"></i>

                            Valider Budget

                        </button>

                    @endif

                    @if($mission->type === 'nationale')
                        <a href="{{ route('missions.pdf.budget-national', $mission->id) }}"
                        target="_blank"
                        class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf me-1"></i>
                            Budget national
                        </a>
                    @endif

                     @if($mission->type === 'internationale')
                        <div class="d-flex gap-2 flex-wrap">

                            <a href="{{ route('missions.pdf.budget-international', $mission->id) }}"
                            target="_blank"
                            class="btn btn-danger">
                                <i class="fas fa-file-pdf me-1"></i>
                                Budget international
                            </a>

                        </div>
                    @endif


                </div>


                {{-- Message budget --}}
                @if(!$budgetPret)

                    <div class="alert alert-warning mt-3 mb-0">

                        <i class="fas fa-exclamation-triangle me-2"></i>


                        @if($mission->type === 'nationale')

                            <strong>Budget incomplet.</strong>

                            Veuillez renseigner les informations
                            relatives au carburant avant de valider
                            le budget.

                        @else

                            <strong>
                                Budget international incomplet.
                            </strong>

                            Veuillez renseigner les informations
                            complémentaires pour chaque participant
                            avant de valider le budget.

                        @endif

                    </div>

                @else

                    <div class="alert alert-success mt-3 mb-0">

                        <i class="fas fa-check-circle me-2"></i>

                        <strong>
                            Budget prêt pour validation.
                        </strong>

                        Toutes les informations nécessaires
                        sont renseignées.

                    </div>

                @endif

            @endif


            {{-- ====================================================
                COMPTABILITÉ
            ==================================================== --}}
            @if($mission->statut === 'en_attente_comptabilite')

                <div class="d-flex gap-2 flex-wrap">

                    <button type="button"
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#modalPaiement">

                        <i class="fas fa-money-check-alt me-1"></i>

                        Effectuer le paiement

                    </button>

                </div>

            @endif


            {{-- ====================================================
                PAYÉE
            ==================================================== --}}
            @if($mission->statut === 'payee')

                <div class="alert alert-success mb-0">

                    <i class="fas fa-check-circle me-2"></i>

                    Cette mission a été payée.

                </div>

            @endif

        </div>

    </div>

</div>


{{-- ================================================================
    MODAL VALIDATION RH
================================================================ --}}
@if(
    $mission->statut === 'en_attente_rh' &&
    in_array(auth()->user()->profil, ['rh', 'admin'])
)

    <div class="modal fade"
         id="modalValidationRH"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <form method="POST"
                      action="{{ route('missions.valider-rh', $mission->id) }}"
                      id="formValidationRH">

                    @csrf

                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-check-circle me-2"></i>
                            Validation RH
                        </h5>

                        <button type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="numero_ordre" class="form-label">
                                Numéro d'ordre de mission
                                @if($mission->type === 'nationale')
                                    <span class="text-danger">*</span>
                                @endif
                            </label>

                            <input type="text"
                                   name="numero_ordre"
                                   id="numero_ordre"
                                   class="form-control"
                                   value="{{ old('numero_ordre', $mission->numero_ordre) }}"
                                   placeholder="Exemple : 0012/CANAM/2026"
                                   @if($mission->type === 'nationale') required @endif>
                        </div>

                        @if($mission->type === 'nationale')

                            <div class="mb-3">
                                <label for="chauffeur_id" class="form-label">
                                    Chauffeur <span class="text-danger">*</span>
                                </label>

                                <select name="chauffeur_id"
                                        id="chauffeur_id"
                                        class="form-select"
                                        required>

                                    <option value="">
                                        -- Sélectionner le chauffeur --
                                    </option>

                                    @foreach($chauffeurs as $chauffeur)
                                        <option value="{{ $chauffeur->id }}"
                                            {{ old('chauffeur_id', $mission->chauffeur_id) == $chauffeur->id ? 'selected' : '' }}>
                                            {{ $chauffeur->prenom }} {{ $chauffeur->nom }}
                                            @if(!empty($chauffeur->matricule))
                                                — {{ $chauffeur->matricule }}
                                            @endif
                                        </option>
                                    @endforeach

                                </select>

                                <div class="form-text">
                                    Le chauffeur sera ajouté aux participants et ses frais et indemnités de mission seront calculés.
                                </div>
                            </div>

                        @else

                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Pour une mission internationale, aucun chauffeur n'est affecté à cette étape.
                                Le numéro d'ordre sera enregistré s'il est renseigné, puis la mission sera transmise directement au Budget.
                            </div>

                        @endif

                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Annuler
                        </button>

                        <button type="submit"
                                class="btn btn-success">
                            <i class="fas fa-check-circle me-1"></i>
                            Confirmer la validation RH
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

@endif


{{-- ================================================================
    MODAL CARBURANT
================================================================ --}}
@if(
    $mission->statut === 'en_attente_budget' &&
    $mission->type === 'nationale'
)

    <div class="modal fade"
         id="modalGestionCarburant"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <form method="POST"
                      action="{{ route('missions.carburant.update', $mission->id) }}"
                      id="formCarburant">

                    @csrf


                    <div class="modal-header">

                        <h5 class="modal-title">

                            <i class="fas fa-gas-pump me-2"></i>

                            Gestion du carburant

                        </h5>


                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="alert alert-info">

                            <i class="fas fa-info-circle me-2"></i>

                            Les montants sont calculés automatiquement
                            à partir des quantités et du prix au litre.

                        </div>


                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Nombre de véhicules
                                </label>

                                <input type="number"
                                       name="nombre_voitures"
                                       id="nombre_voitures"
                                       class="form-control"
                                       min="1"
                                       value="{{ old(
                                           'nombre_voitures',
                                           optional($carburant)->nombre_voitures ?? 1
                                       ) }}"
                                       required>

                            </div>


                            <div class="col-md-4">

                                <label class="form-label">
                                    Prix du litre
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="prix_litre"
                                           id="prix_litre"
                                           class="form-control"
                                           min="0"
                                           step="0.01"
                                           value="{{ old(
                                               'prix_litre',
                                               optional($carburant)->prix_litre ?? ''
                                           ) }}"
                                           required>

                                    <span class="input-group-text">
                                        FCFA
                                    </span>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <label class="form-label">
                                    Quantité carburant
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="quantite_carburant"
                                           id="quantite_carburant"
                                           class="form-control"
                                           min="0"
                                           step="0.01"
                                           value="{{ old(
                                               'quantite_carburant',
                                               optional($carburant)->quantite_carburant ?? 0
                                           ) }}"
                                           required>

                                    <span class="input-group-text">
                                        L
                                    </span>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <label class="form-label">
                                    Quantité carburant ville
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="quantite_carburant_ville"
                                           id="quantite_carburant_ville"
                                           class="form-control"
                                           min="0"
                                           step="0.01"
                                           value="{{ old(
                                               'quantite_carburant_ville',
                                               optional($carburant)->quantite_carburant_ville ?? 0
                                           ) }}"
                                           required>

                                    <span class="input-group-text">
                                        L
                                    </span>

                                </div>

                            </div>

                        </div>


                        <hr>


                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Montant carburant
                                </label>

                                <input type="text"
                                       id="montant_carburant_affiche"
                                       class="form-control"
                                       readonly>

                            </div>


                            <div class="col-md-4">

                                <label class="form-label">
                                    Montant carburant ville
                                </label>

                                <input type="text"
                                       id="montant_carburant_ville_affiche"
                                       class="form-control"
                                       readonly>

                            </div>


                            <div class="col-md-4">

                                <label class="form-label">
                                    Sous-total carburant
                                </label>

                                <input type="text"
                                       id="sous_total_carburant_affiche"
                                       class="form-control fw-bold"
                                       readonly>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                            Annuler

                        </button>


                        <button type="submit"
                                class="btn btn-success">

                            <i class="fas fa-save me-1"></i>

                            Enregistrer le carburant

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif


{{-- ================================================================
    MODAL BUDGET INTERNATIONAL
================================================================ --}}
@if(
    $mission->statut === 'en_attente_budget' &&
    $mission->type === 'internationale'
)

    @php

        $budgetsExistants =
            $mission->budgetsInternationaux
                ->keyBy('agent_id');

    @endphp


    <div class="modal fade"
         id="modalBudgetInternational"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content">

                <form method="POST"
      action="{{ route('missions.budget-international.update', $mission->id) }}">
    @csrf


                    <div class="modal-header bg-primary text-white">

                        <h5 class="modal-title">

                            <i class="fas fa-plane-departure me-2"></i>

                            Informations complémentaires
                            de la mission internationale

                        </h5>


                        <button type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="modal">
                        </button>

                    </div>


                    <div class="modal-body">

                        <div class="alert alert-info">

                            <i class="fas fa-info-circle me-2"></i>

                            Renseignez les frais de participation,
                            les frais de visa et le billet d'avion
                            pour chaque participant.

                        </div>


                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead class="table-light">

                                    <tr>

                                        <th>
                                            Participant
                                        </th>

                                        <th>
                                            Frais participation
                                        </th>

                                        <th>
                                            Frais visa
                                        </th>

                                        <th>
                                            Billet d'avion
                                        </th>

                                        <th>
                                            Classe billet
                                        </th>

                                        <th>
                                            Total
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @forelse(
                                        $mission->missionAgents
                                        as $missionAgent
                                    )

                                        @if($missionAgent->agent)

                                            @php

                                                $agent =
                                                    $missionAgent->agent;

                                                $budget =
                                                    $budgetsExistants
                                                        ->get($agent->id);

                                            @endphp


                                            <tr>

                                                <td>

                                                    <strong>

                                                        {{ $agent->prenom }}
                                                        {{ $agent->nom }}

                                                    </strong>

                                                    <br>

                                                    <small class="text-muted">

                                                        {{ $agent->matricule }}

                                                    </small>

                                                    <br>

                                                    <small>

                                                        {{ $agent->fonction }}

                                                    </small>

                                                </td>


                                                <td>

                                                    <div class="input-group">

                                                        <input type="number"
                                                               name="participants[{{ $agent->id }}][frais_participation]"
                                                               class="form-control montant-international"
                                                               data-agent="{{ $agent->id }}"
                                                               value="{{ old(
                                                                   'participants.'.$agent->id.'.frais_participation',
                                                                   optional($budget)->frais_participation ?? 0
                                                               ) }}"
                                                               min="0"
                                                               step="0.01"
                                                               required>

                                                        <span class="input-group-text">
                                                            FCFA
                                                        </span>

                                                    </div>

                                                </td>


                                                <td>

                                                    <div class="input-group">

                                                        <input type="number"
                                                               name="participants[{{ $agent->id }}][frais_visa]"
                                                               class="form-control montant-international"
                                                               data-agent="{{ $agent->id }}"
                                                               value="{{ old(
                                                                   'participants.'.$agent->id.'.frais_visa',
                                                                   optional($budget)->frais_visa ?? 0
                                                               ) }}"
                                                               min="0"
                                                               step="0.01"
                                                               required>

                                                        <span class="input-group-text">
                                                            FCFA
                                                        </span>

                                                    </div>

                                                </td>


                                                <td>

                                                    <div class="input-group">

                                                        <input type="number"
                                                               name="participants[{{ $agent->id }}][billet_avion]"
                                                               class="form-control montant-international"
                                                               data-agent="{{ $agent->id }}"
                                                               value="{{ old(
                                                                   'participants.'.$agent->id.'.billet_avion',
                                                                   optional($budget)->billet_avion ?? 0
                                                               ) }}"
                                                               min="0"
                                                               step="0.01"
                                                               required>

                                                        <span class="input-group-text">
                                                            FCFA
                                                        </span>

                                                    </div>

                                                </td>


                                                <td>

                                                    <select name="participants[{{ $agent->id }}][classe_billet]"
                                                            class="form-select"
                                                            required>

                                                        <option value="economique"
                                                            {{ old(
                                                                'participants.'.$agent->id.'.classe_billet',
                                                                optional($budget)->classe_billet ?? 'economique'
                                                            ) === 'economique'
                                                                ? 'selected'
                                                                : ''
                                                            }}>

                                                            Classe économique

                                                        </option>


                                                        <option value="affaires"
                                                            {{ old(
                                                                'participants.'.$agent->id.'.classe_billet',
                                                                optional($budget)->classe_billet ?? 'economique'
                                                            ) === 'affaires'
                                                                ? 'selected'
                                                                : ''
                                                            }}>

                                                            Classe affaires

                                                        </option>

                                                    </select>

                                                </td>


                                                <td>

                                                    <input type="text"
                                                           id="total_agent_{{ $agent->id }}"
                                                           class="form-control fw-bold total-agent"
                                                           value="{{ number_format(
                                                               optional($budget)->montant_total ?? 0,
                                                               0,
                                                               ',',
                                                               ' '
                                                           ) }} FCFA"
                                                           readonly>

                                                </td>

                                            </tr>

                                        @endif

                                    @empty

                                        <tr>

                                            <td colspan="6"
                                                class="text-center text-muted py-4">

                                                Aucun participant enregistré.

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>


                                <tfoot>

                                    <tr class="table-primary">

                                        <th colspan="5"
                                            class="text-end">

                                            TOTAL BUDGET INTERNATIONAL

                                        </th>


                                        <th>

                                            <input type="text"
                                                   id="totalBudgetInternational"
                                                   class="form-control fw-bold text-success"
                                                   readonly>

                                        </th>

                                    </tr>

                                </tfoot>

                            </table>

                        </div>


                        {{-- RÉCAPITULATIF --}}
                        <div class="alert alert-success mt-3">

                            <div class="d-flex justify-content-between">

                                <strong>
                                    Montant de la mission :
                                </strong>

                                <strong>

                                    {{ number_format(
                                        $mission->montant_total,
                                        0,
                                        ',',
                                        ' '
                                    ) }}

                                    FCFA

                                </strong>

                            </div>


                            <hr>


                            <div class="d-flex justify-content-between">

                                <strong>
                                    Budget international :
                                </strong>

                                <strong id="recapBudgetInternational">
                                    0 FCFA
                                </strong>

                            </div>


                            <hr>


                            <div class="d-flex justify-content-between fs-5">

                                <strong>
                                    Montant total général :
                                </strong>

                                <strong id="recapTotalGeneral">

                                    {{ number_format(
                                        $mission->montant_total,
                                        0,
                                        ',',
                                        ' '
                                    ) }}

                                    FCFA

                                </strong>

                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                            <i class="fas fa-times me-1"></i>

                            Annuler

                        </button>


                        <button type="submit"
                                class="btn btn-success">

                            <i class="fas fa-save me-1"></i>

                            Enregistrer les informations

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif


{{-- ================================================================
    MODAL PAIEMENT
================================================================ --}}
@if($mission->statut === 'en_attente_comptabilite')

    @php

        $montantAPayerModal =
            $mission->montant_total_general > 0
                ? $mission->montant_total_general
                : $mission->montant_total;

    @endphp


    {{-- =========================================================
         MODAL PAIEMENT COMPTABILITÉ
    ========================================================== --}}

    <div class="modal fade"
         id="modalPaiement"
         tabindex="-1"
         aria-labelledby="modalPaiementLabel"
         aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content border-0 shadow-lg">

                <form method="POST"
                      action="{{ route('missions.payer', $mission->id) }}">

                    @csrf


                    {{-- =================================================
                         HEADER
                    ================================================== --}}

                    <div class="modal-header bg-success text-white">

                        <div class="d-flex align-items-center">

                            <div class="me-3">

                                <div class="rounded-circle bg-white bg-opacity-25
                                            d-flex align-items-center
                                            justify-content-center"
                                     style="width:48px;height:48px;">

                                    <i class="fas fa-money-check-alt fa-lg"></i>

                                </div>

                            </div>

                            <div>

                                <h5 class="modal-title mb-1"
                                    id="modalPaiementLabel">

                                    Paiement de la mission

                                </h5>

                                <small class="opacity-75">

                                    {{ $mission->numero_ordre }}

                                </small>

                            </div>

                        </div>


                        <button type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="modal"
                                aria-label="Fermer">
                        </button>

                    </div>


                    {{-- =================================================
                         BODY
                    ================================================== --}}

                    <div class="modal-body p-4">


                        {{-- Montant à payer --}}

                        <div class="alert alert-primary border-0 shadow-sm">

                            <div class="d-flex
                                        justify-content-between
                                        align-items-center
                                        flex-wrap
                                        gap-3">

                                <div>

                                    <div class="small text-muted mb-1">

                                        Montant total à payer

                                    </div>

                                    <div class="fs-4 fw-bold text-primary">

                                        {{ number_format(
                                            $montantAPayerModal,
                                            0,
                                            ',',
                                            ' '
                                        ) }}

                                        FCFA

                                    </div>

                                </div>


                                <div>

                                    <span class="badge bg-primary">

                                        <i class="fas fa-file-invoice me-1"></i>

                                        {{ $mission->numero }}

                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             MONTANT PAYÉ
                        ================================================== --}}

                        <div class="mb-3">

                            <label for="montant_paye"
                                   class="form-label fw-semibold">

                                <i class="fas fa-money-bill-wave text-success me-1"></i>

                                Montant payé

                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <input type="number"
                                       name="montant_paye"
                                       id="montant_paye"
                                       class="form-control"
                                       value="{{ old('montant_paye', $montantAPayerModal) }}"
                                       min="0"
                                       step="0.01"
                                       required>

                                <span class="input-group-text">
                                    FCFA
                                </span>

                            </div>

                        </div>


                        {{-- =================================================
                             RÉFÉRENCE PAIEMENT
                        ================================================== --}}

                        <div class="mb-3">

                            <label for="reference_paiement"
                                   class="form-label fw-semibold">

                                <i class="fas fa-hashtag text-primary me-1"></i>

                                Référence du paiement

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="reference_paiement"
                                   id="reference_paiement"
                                   class="form-control"
                                   value="{{ old('reference_paiement') }}"
                                   maxlength="100"
                                   placeholder="Ex. VIR-2026-001245"
                                   required>

                            <div class="form-text">

                                Indiquez le numéro ou la référence permettant
                                d'identifier le paiement.

                            </div>

                        </div>


                        {{-- =================================================
                             DATE PAIEMENT
                        ================================================== --}}

                        <div class="mb-3">

                            <label for="date_paiement"
                                   class="form-label fw-semibold">

                                <i class="fas fa-calendar-check text-success me-1"></i>

                                Date du paiement

                                <span class="text-danger">*</span>

                            </label>

                            <input type="date"
                                   name="date_paiement"
                                   id="date_paiement"
                                   class="form-control"
                                   value="{{ old(
                                       'date_paiement',
                                       now()->format('Y-m-d')
                                   ) }}"
                                   required>

                        </div>


                        {{-- =================================================
                             OBSERVATION
                        ================================================== --}}

                        <div class="mb-2">

                            <label for="observation"
                                   class="form-label fw-semibold">

                                <i class="fas fa-comment-alt text-secondary me-1"></i>

                                Observation

                            </label>

                            <textarea name="observation"
                                      id="observation"
                                      class="form-control"
                                      rows="3"
                                      maxlength="1000"
                                      placeholder="Observation éventuelle concernant le paiement...">{{ old('observation') }}</textarea>

                        </div>


                        {{-- Information --}}

                        <div class="alert alert-warning mt-4 mb-0">

                            <div class="d-flex align-items-start">

                                <i class="fas fa-info-circle me-2 mt-1"></i>

                                <div class="small">

                                    <strong>Attention :</strong>

                                    Après confirmation, le paiement sera
                                    enregistré avec la référence et la date
                                    indiquées.

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         FOOTER
                    ================================================== --}}

                    <div class="modal-footer bg-light">

                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">

                            <i class="fas fa-times me-1"></i>

                            Annuler

                        </button>


                        <button type="submit"
                                class="btn btn-success">

                            <i class="fas fa-check-circle me-1"></i>

                            Confirmer le paiement

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif


{{-- ================================================================
    MODAL AFFECTATION VÉHICULE
================================================================ --}}
@if(
    $mission->statut === 'en_attente_moyens_generaux' &&
    in_array(
        strtolower(trim(auth()->user()->profil ?? '')),
        ['moyens_generaux', 'admin']
    )
)

<div
    class="modal fade"
    id="modalAffecterVehicule"
    tabindex="-1"
    aria-labelledby="modalAffecterVehiculeLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('missions.affecter-voiture', $mission->id) }}"
                id="formAffecterVehicule"
            >

                @csrf

                {{-- ====================================================
                    HEADER
                ==================================================== --}}
                <div class="modal-header bg-primary text-white">

                    <h5
                        class="modal-title"
                        id="modalAffecterVehiculeLabel"
                    >

                        <i class="fas fa-car me-2"></i>

                        Affectation du véhicule

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Fermer"
                    ></button>

                </div>


                {{-- ====================================================
                    BODY
                ==================================================== --}}
                <div class="modal-body">

                    {{-- Informations mission --}}
                    <div class="alert alert-light border">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Mission
                                </small>

                                <strong>
                                    {{ $mission->numero }}
                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Départ
                                </small>

                                <strong>

                                    {{ optional($mission->date_depart)->format('d/m/Y') }}

                                </strong>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted d-block">
                                    Retour
                                </small>

                                <strong>

                                    {{ optional($mission->date_retour)->format('d/m/Y') }}

                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- ====================================================
                        CHARGEMENT
                    ==================================================== --}}
                    <div
                        id="chargementVehicules"
                        class="text-center py-5"
                    >

                        <div
                            class="spinner-border text-primary"
                            role="status"
                        >
                        </div>

                        <div class="mt-3 text-muted">

                            Recherche des véhicules disponibles...

                        </div>

                    </div>


                    {{-- ====================================================
                        LISTE DES VÉHICULES
                    ==================================================== --}}
                    <div
                        id="contenuVehicules"
                        style="display:none;"
                    >

                        <div class="mb-3">

                            <label
                                for="voiture_id"
                                class="form-label fw-semibold"
                            >

                                <i class="fas fa-car me-1 text-primary"></i>

                                Véhicule disponible

                                <span class="text-danger">*</span>

                            </label>


                            <select
                                name="voiture_id"
                                id="voiture_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- Sélectionner un véhicule --
                                </option>

                            </select>

                            <div class="form-text">

                                Seuls les véhicules libres pendant toute
                                la période de la mission sont affichés.

                            </div>

                        </div>


                        {{-- ====================================================
                            OBSERVATION
                        ==================================================== --}}
                        <div class="mb-3">

                            <label
                                for="observation_moyens_generaux"
                                class="form-label fw-semibold"
                            >

                                Observation

                            </label>

                            <textarea
                                name="observation_moyens_generaux"
                                id="observation_moyens_generaux"
                                class="form-control"
                                rows="3"
                                maxlength="1000"
                                placeholder="Observation éventuelle des Moyens généraux..."
                            ></textarea>

                        </div>

                    </div>


                    {{-- ====================================================
                        AUCUN VÉHICULE
                    ==================================================== --}}
                    <div
                        id="aucunVehicule"
                        class="alert alert-warning"
                        style="display:none;"
                    >

                        <i class="fas fa-exclamation-triangle me-2"></i>

                        Aucun véhicule n'est disponible pour cette période.

                    </div>


                    {{-- ====================================================
                        ERREUR
                    ==================================================== --}}
                    <div
                        id="erreurVehicules"
                        class="alert alert-danger"
                        style="display:none;"
                    >

                    </div>

                </div>


                {{-- ====================================================
                    FOOTER
                ==================================================== --}}
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
                        class="btn btn-success"
                        id="btnConfirmerVehicule"
                        disabled
                    >

                        <i class="fas fa-check me-1"></i>

                        Affecter le véhicule

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif



{{-- ================================================================
    JAVASCRIPT
================================================================ --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | CARBURANT
    |--------------------------------------------------------------------------
    */

    const quantiteCarburant =
        document.getElementById('quantite_carburant');

    const quantiteCarburantVille =
        document.getElementById('quantite_carburant_ville');

    const prixLitre =
        document.getElementById('prix_litre');

    const montantCarburant =
        document.getElementById('montant_carburant_affiche');

    const montantCarburantVille =
        document.getElementById('montant_carburant_ville_affiche');

    const sousTotal =
        document.getElementById('sous_total_carburant_affiche');


    function formatMontant(montant)
    {
        return new Intl.NumberFormat('fr-FR', {

            minimumFractionDigits: 0,
            maximumFractionDigits: 0

        }).format(montant) + ' FCFA';
    }


    function calculerCarburant()
    {
        if (
            !quantiteCarburant ||
            !quantiteCarburantVille ||
            !prixLitre
        ) {
            return;
        }


        const quantite =
            parseFloat(
                quantiteCarburant.value
            ) || 0;


        const quantiteVille =
            parseFloat(
                quantiteCarburantVille.value
            ) || 0;


        const prix =
            parseFloat(
                prixLitre.value
            ) || 0;


        const montant =
            quantite * prix;


        const montantVille =
            quantiteVille * prix;


        const total =
            montant + montantVille;


        if(montantCarburant) {

            montantCarburant.value =
                formatMontant(montant);

        }


        if(montantCarburantVille) {

            montantCarburantVille.value =
                formatMontant(montantVille);

        }


        if(sousTotal) {

            sousTotal.value =
                formatMontant(total);

        }

    }


    if(quantiteCarburant) {

        quantiteCarburant.addEventListener(
            'input',
            calculerCarburant
        );

    }


    if(quantiteCarburantVille) {

        quantiteCarburantVille.addEventListener(
            'input',
            calculerCarburant
        );

    }


    if(prixLitre) {

        prixLitre.addEventListener(
            'input',
            calculerCarburant
        );

    }


    calculerCarburant();


    /*
    |--------------------------------------------------------------------------
    | BUDGET INTERNATIONAL
    |--------------------------------------------------------------------------
    */

    const inputsInternational =
        document.querySelectorAll(
            '.montant-international'
        );


    const totalBudgetInternational =
        document.getElementById(
            'totalBudgetInternational'
        );


    const recapBudgetInternational =
        document.getElementById(
            'recapBudgetInternational'
        );


    const recapTotalGeneral =
        document.getElementById(
            'recapTotalGeneral'
        );


    const montantMission =
        {{ (float) $mission->montant_total }};


    function calculerBudgetInternational()
    {
        if(!inputsInternational.length) {

            return;

        }


        const totauxAgents = {};


        let totalGeneral = 0;


        inputsInternational.forEach(function(input) {

            const agentId =
                input.dataset.agent;


            const montant =
                parseFloat(input.value) || 0;


            if(!totauxAgents[agentId]) {

                totauxAgents[agentId] = 0;

            }


            totauxAgents[agentId] += montant;

        });


        Object.keys(totauxAgents).forEach(function(agentId) {

            const totalAgent =
                totauxAgents[agentId];


            totalGeneral +=
                totalAgent;


            const champTotal =
                document.getElementById(
                    'total_agent_' + agentId
                );


            if(champTotal) {

                champTotal.value =
                    formatMontant(totalAgent);

            }

        });


        if(totalBudgetInternational) {

            totalBudgetInternational.value =
                formatMontant(totalGeneral);

        }


        if(recapBudgetInternational) {

            recapBudgetInternational.textContent =
                formatMontant(totalGeneral);

        }


        if(recapTotalGeneral) {

            recapTotalGeneral.textContent =
                formatMontant(
                    montantMission +
                    totalGeneral
                );

        }

    }


    inputsInternational.forEach(function(input) {

        input.addEventListener(
            'input',
            calculerBudgetInternational
        );

    });


    calculerBudgetInternational();


    /*
    |--------------------------------------------------------------------------
    | CONFIRMATION VALIDATION RH
    |--------------------------------------------------------------------------
    */

    const formValidationRH =
        document.getElementById('formValidationRH');

    if (formValidationRH) {

        formValidationRH.addEventListener('submit', function (event) {

            const typeMission = @json($mission->type);

            let message;

            if (typeMission === 'internationale') {

                message =
                    "Vous êtes sur le point de valider cette mission internationale au niveau RH.\n\n" +
                    "• Aucun chauffeur ne sera affecté à cette étape.\n" +
                    "• Le numéro d'ordre sera enregistré s'il est renseigné.\n" +
                    "• Après validation, la mission sera transmise directement au Budget.\n\n" +
                    "Voulez-vous confirmer la validation RH ?";

            } else {

                message =
                    "Vous êtes sur le point de valider cette mission nationale au niveau RH.\n\n" +
                    "• Le chauffeur sélectionné sera affecté à la mission.\n" +
                    "• Ses frais et indemnités de mission seront calculés.\n" +
                    "• La mission sera ensuite transmise aux Moyens généraux pour l'affectation du véhicule.\n\n" +
                    "Voulez-vous confirmer la validation RH ?";

            }

            if (!confirm(message)) {
                event.preventDefault();
            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRMATION RH → BUDGET APRÈS MOYENS GÉNÉRAUX
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.form-transition-rh-budget')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                const ok = confirm(
                    "La mission a été contrôlée après l'intervention des Moyens généraux.\n\n" +
                    "Voulez-vous confirmer sa transmission au Budget ?\n\n" +
                    "Après confirmation, le service Budget pourra renseigner et valider les éléments financiers."
                );

                if (!ok) {
                    event.preventDefault();
                }

            });

        });


    /*
    |--------------------------------------------------------------------------
    | CONFIRMATION VALIDATION BUDGET
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.form-validation-budget')
        .forEach(function (form) {

            form.addEventListener('submit', function (event) {

                const typeMission = @json($mission->type);

                const message = typeMission === 'internationale'
                    ? "Le budget complémentaire de la mission internationale est complet.\n\n" +
                      "La validation du Budget transmettra la mission à la Comptabilité pour paiement.\n\n" +
                      "Voulez-vous confirmer la validation du Budget ?"
                    : "Les informations de carburant sont renseignées.\n\n" +
                      "La validation du Budget transmettra la mission à la Comptabilité pour paiement.\n\n" +
                      "Voulez-vous confirmer la validation du Budget ?";

                if (!confirm(message)) {
                    event.preventDefault();
                }

            });

        });


    /*
    |--------------------------------------------------------------------------
    | CONFIRMATION PAIEMENT
    |--------------------------------------------------------------------------
    */

    const formPaiement =
        document.querySelector('#modalPaiement form');

    if (formPaiement) {

        formPaiement.addEventListener('submit', function (event) {

            const montant = @json((float) (
                $mission->montant_total_general > 0
                    ? $mission->montant_total_general
                    : $mission->montant_total
            ));

            const montantFormate =
                new Intl.NumberFormat('fr-FR').format(montant);

            const ok = confirm(
                "Vous êtes sur le point de confirmer le paiement de cette mission.\n\n" +
                "Montant total à payer : " +
                montantFormate +
                " FCFA\n\n" +
                "Voulez-vous confirmer le paiement ?"
            );

            if (!ok) {
                event.preventDefault();
            }

        });

    }

});

/*
|--------------------------------------------------------------------------
| AFFECTATION VÉHICULE
|--------------------------------------------------------------------------
*/

const modalVehicule =
    document.getElementById('modalAffecterVehicule');

if (modalVehicule) {

    const selectVehicule =
        document.getElementById('voiture_id');

    const chargementVehicules =
        document.getElementById('chargementVehicules');

    const contenuVehicules =
        document.getElementById('contenuVehicules');

    const aucunVehicule =
        document.getElementById('aucunVehicule');

    const erreurVehicules =
        document.getElementById('erreurVehicules');

    const btnConfirmerVehicule =
        document.getElementById('btnConfirmerVehicule');


    /*
    |--------------------------------------------------------------------------
    | URL de récupération
    |--------------------------------------------------------------------------
    */

    const urlVehicules =
        @json(route(
            'missions.vehicules-disponibles',
            $mission->id
        ));


    /*
    |--------------------------------------------------------------------------
    | OUVERTURE DU MODAL
    |--------------------------------------------------------------------------
    */

    modalVehicule.addEventListener(
        'show.bs.modal',
        function () {

            chargerVehicules();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CHARGER LES VÉHICULES
    |--------------------------------------------------------------------------
    */

    function chargerVehicules()
    {
        /*
        |----------------------------------------------------------------------
        | Réinitialiser
        |----------------------------------------------------------------------
        */

        chargementVehicules.style.display =
            'block';

        contenuVehicules.style.display =
            'none';

        aucunVehicule.style.display =
            'none';

        erreurVehicules.style.display =
            'none';

        erreurVehicules.innerHTML =
            '';

        selectVehicule.innerHTML = `
            <option value="">
                -- Sélectionner un véhicule --
            </option>
        `;

        btnConfirmerVehicule.disabled =
            true;


        /*
        |----------------------------------------------------------------------
        | Requête AJAX
        |----------------------------------------------------------------------
        */

        fetch(
            urlVehicules,
            {
                method: 'GET',

                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }
        )

        .then(function(response) {

            return response.json()
                .then(function(data) {

                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Impossible de récupérer les véhicules.'
                        );

                    }

                    return data;

                });

        })

        .then(function(data) {

            chargementVehicules.style.display =
                'none';


            /*
            |--------------------------------------------------------------------------
            | Aucun véhicule
            |--------------------------------------------------------------------------
            */

            if (
                !data.vehicules ||
                data.vehicules.length === 0
            ) {

                aucunVehicule.style.display =
                    'block';

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Remplir la liste
            |--------------------------------------------------------------------------
            */

            data.vehicules.forEach(
                function(voiture) {

                    const option =
                        document.createElement('option');

                    option.value =
                        voiture.id;


                    let texte =
                        voiture.immatriculation ||
                        'Véhicule sans immatriculation';


                    if (voiture.marque) {

                        texte +=
                            ' — ' +
                            voiture.marque;

                    }


                    if (voiture.modele) {

                        texte +=
                            ' ' +
                            voiture.modele;

                    }


                    option.textContent =
                        texte;


                    selectVehicule.appendChild(
                        option
                    );

                }
            );


            contenuVehicules.style.display =
                'block';

        })

        .catch(function(error) {

            chargementVehicules.style.display =
                'none';

            erreurVehicules.style.display =
                'block';

            erreurVehicules.innerHTML =
                '<i class="fas fa-exclamation-circle me-2"></i>' +
                error.message;

        });
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVER LE BOUTON APRÈS SÉLECTION
    |--------------------------------------------------------------------------
    */

    selectVehicule.addEventListener(
        'change',
        function() {

            btnConfirmerVehicule.disabled =
                !this.value;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CONFIRMATION
    |--------------------------------------------------------------------------
    */

    const formAffecterVehicule =
        document.getElementById(
            'formAffecterVehicule'
        );

    if (formAffecterVehicule) {

        formAffecterVehicule.addEventListener(
            'submit',
            function(event) {

                const nomVehicule =
                    selectVehicule
                        .options[
                            selectVehicule.selectedIndex
                        ]
                        ?.text || '';

                const ok = confirm(
                    "Vous êtes sur le point d'affecter le véhicule :\n\n" +
                    nomVehicule +
                    "\n\n" +
                    "à la mission " +
                    @json($mission->numero) +
                    ".\n\n" +
                    "Voulez-vous confirmer cette affectation ?"
                );

                if (!ok) {

                    event.preventDefault();

                }

            }
        );

    }

}

</script>

@endsection
