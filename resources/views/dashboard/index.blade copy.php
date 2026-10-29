@extends('layouts.ap')

@section('title', 'Tableau de bord')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         EN-TÊTE
         ========================================================= --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-speedometer2 text-success me-2"></i>
                Tableau de bord
            </h2>

            <p class="text-muted mb-0">
                Gestion des ordres de mission — CANAM
            </p>
        </div>

        <div class="mt-3 mt-md-0">

            <a href="{{ route('missions.index') }}"
               class="btn btn-success">

                <i class="bi bi-list-task me-1"></i>

                Toutes les missions

            </a>

        </div>

    </div>


    {{-- =========================================================
         FILTRE PÉRIODE
         ========================================================= --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('dashboard') }}"
                  class="row g-3 align-items-end">

                <div class="col-xl-3 col-md-6">

    <label class="form-label fw-semibold">
        Date de début
    </label>

    <input
        type="date"
        name="date_debut"
        value="{{ $dateDebut->format('Y-m-d') }}"
        class="form-control"
    >

</div>


<div class="col-xl-3 col-md-6">

    <label class="form-label fw-semibold">
        Date de fin
    </label>

    <input
        type="date"
        name="date_fin"
        value="{{ $dateFin->format('Y-m-d') }}"
        class="form-control"
    >

</div>


<div class="col-xl-3 col-md-6">

    <label class="form-label fw-semibold">
        Type de mission
    </label>

    <select
        name="type"
        class="form-select"
    >

        <option
            value="toutes"
            {{ ($typeMission ?? 'toutes') === 'toutes' ? 'selected' : '' }}
        >
            Toutes les missions
        </option>

        <option
            value="nationale"
            {{ ($typeMission ?? '') === 'nationale' ? 'selected' : '' }}
        >
            Missions nationales
        </option>

        <option
            value="internationale"
            {{ ($typeMission ?? '') === 'internationale' ? 'selected' : '' }}
        >
            Missions internationales
        </option>

    </select>

</div>


<div class="col-xl-3 col-md-6 d-flex gap-2">

    <button
        type="submit"
        class="btn btn-success flex-fill"
        title="Appliquer les filtres"
    >
        <i class="bi bi-filter me-1"></i>
        Filtrer
    </button>


    <a
        href="{{ route('dashboard') }}"
        class="btn btn-outline-secondary"
        title="Réinitialiser"
    >
        <i class="bi bi-arrow-clockwise"></i>
    </a>


    <a
        href="{{ route('dashboard.rapport.agents.pdf', [
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_fin' => $dateFin->format('Y-m-d'),
            'type' => $typeMission ?? 'toutes',
        ]) }}"
        target="_blank"
        class="btn btn-danger"
        title="Exporter le rapport PDF"
    >
        <i class="bi bi-file-earmark-pdf"></i>
    </a>

</div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         CARTES PRINCIPALES
         ========================================================= --}}
    <div class="row g-4 mb-4">

        {{-- TOTAL --}}
        <div class="col-xl-3 col-md-6">

            <div class="card dashboard-card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Missions
                            </p>

                            <h2 class="fw-bold mb-0">
                                {{ number_format($missionsTotal, 0, ',', ' ') }}
                            </h2>

                            <small class="text-muted">
                                Total sur la période
                            </small>

                        </div>

                        <div class="dashboard-icon bg-success-subtle text-success">

                            <i class="bi bi-briefcase-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- EN ATTENTE RH --}}
        <div class="col-xl-3 col-md-6">

            <a
                href="{{ route('missions.index', ['statut' => 'en_attente_rh']) }}"
                class="text-decoration-none"
            >

                <div class="card dashboard-card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="text-muted mb-1">
                                    En attente RH
                                </p>

                                <h2 class="fw-bold mb-0 text-warning">
                                    {{ number_format($missionsAttenteRH, 0, ',', ' ') }}
                                </h2>

                                <small class="text-muted">
                                    Missions à traiter
                                </small>

                            </div>

                            <div class="dashboard-icon bg-warning-subtle text-warning">

                                <i class="bi bi-person-check-fill"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- BUDGET --}}
        <div class="col-xl-3 col-md-6">

            <a
                href="{{ route('missions.index', ['statut' => 'en_attente_budget']) }}"
                class="text-decoration-none"
            >

                <div class="card dashboard-card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="text-muted mb-1">
                                    En attente Budget
                                </p>

                                <h2 class="fw-bold mb-0 text-primary">
                                    {{ number_format($missionsAttenteBudget, 0, ',', ' ') }}
                                </h2>

                                <small class="text-muted">
                                    Missions à budgétiser
                                </small>

                            </div>

                            <div class="dashboard-icon bg-primary-subtle text-primary">

                                <i class="bi bi-cash-stack"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>


        {{-- COMPTABILITÉ --}}
        <div class="col-xl-3 col-md-6">

            <a
                href="{{ route('missions.index', ['statut' => 'en_attente_comptabilite']) }}"
                class="text-decoration-none"
            >

                <div class="card dashboard-card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="text-muted mb-1">
                                    Comptabilité
                                </p>

                                <h2 class="fw-bold mb-0 text-danger">
                                    {{ number_format($missionsAttenteComptabilite, 0, ',', ' ') }}
                                </h2>

                                <small class="text-muted">
                                    Missions à payer
                                </small>

                            </div>

                            <div class="dashboard-icon bg-danger-subtle text-danger">

                                <i class="bi bi-credit-card-fill"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </a>

        </div>

    </div>


    {{-- =========================================================
         FINANCES
         ========================================================= --}}
    <div class="row g-4 mb-4">

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Évolution des missions
                            </h5>

                            <small class="text-muted">
                                Répartition mensuelle
                            </small>

                        </div>

                        <span class="badge bg-success-subtle text-success">
                            {{ $dateDebut->format('Y') }}
                        </span>

                    </div>

                </div>

                <div class="card-body">

                    <canvas
                        id="missionsEvolutionChart"
                        height="110"
                    ></canvas>

                </div>

            </div>

        </div>


        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="fw-bold mb-1">
                        Répartition des missions
                    </h5>

                    <small class="text-muted">
                        Nationales / internationales
                    </small>

                </div>

                <div class="card-body">

                    <canvas
                        id="typeMissionChart"
                        height="230"
                    ></canvas>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FINANCES + STATUTS
         ========================================================= --}}
    <div class="row g-4 mb-4">

        <div class="col-xl-5">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="fw-bold mb-1">
                        Situation financière
                    </h5>

                    <small class="text-muted">
                        Montants en FCFA
                    </small>

                </div>

                <div class="card-body">

                    <div class="finance-item">

                        <div>
                            <span class="text-muted">
                                Montant général
                            </span>
                        </div>

                        <strong>
                            {{ number_format($montantTotalGeneral, 0, ',', ' ') }}
                            FCFA
                        </strong>

                    </div>


                    <div class="finance-item">

                        <div>
                            <span class="text-muted">
                                Montant des participants
                            </span>
                        </div>

                        <strong>
                            {{ number_format($montantTotal, 0, ',', ' ') }}
                            FCFA
                        </strong>

                    </div>


                    <div class="finance-item">

                        <div>
                            <span class="text-muted">
                                Budget international
                            </span>
                        </div>

                        <strong>
                            {{ number_format($montantComplementaireInternational, 0, ',', ' ') }}
                            FCFA
                        </strong>

                    </div>


                    <div class="finance-item">

                        <div>
                            <span class="text-muted">
                                Carburant
                            </span>
                        </div>

                        <strong>
                            {{ number_format($montantCarburant, 0, ',', ' ') }}
                            FCFA
                        </strong>

                    </div>


                    <hr>


                    <div class="finance-item">

                        <div>
                            <span class="text-success fw-semibold">
                                Déjà payé
                            </span>
                        </div>

                        <strong class="text-success">

                            {{ number_format($montantPaye, 0, ',', ' ') }}
                            FCFA

                        </strong>

                    </div>


                    <div class="finance-item">

                        <div>
                            <span class="text-danger fw-semibold">
                                Restant
                            </span>
                        </div>

                        <strong class="text-danger">

                            {{ number_format($montantRestant, 0, ',', ' ') }}
                            FCFA

                        </strong>

                    </div>


                    <div class="mt-4">

                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">
                                Taux de paiement
                            </span>

                            <strong>
                                {{ $tauxPaiement }} %
                            </strong>

                        </div>

                        <div class="progress" style="height: 9px;">

                            <div
                                class="progress-bar bg-success"
                                role="progressbar"
                                style="width: {{ min(100, $tauxPaiement) }}%"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-7">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="fw-bold mb-1">
                        État des missions
                    </h5>

                    <small class="text-muted">
                        Répartition par statut
                    </small>

                </div>

                <div class="card-body">

                    <canvas
                        id="statutMissionChart"
                        height="180"
                    ></canvas>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         À TRAITER
         ========================================================= --}}
    @if($missionsATraiter->count() > 0)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">

                            <i class="bi bi-bell-fill text-warning me-2"></i>

                            Missions à traiter

                        </h5>

                        <small class="text-muted">
                            Actions nécessitant votre intervention
                        </small>

                    </div>

                    <span class="badge bg-warning text-dark">

                        {{ $missionsATraiter->count() }}

                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    N° Mission
                                </th>

                                <th>
                                    Objet
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Départ
                                </th>

                                <th>
                                    Statut
                                </th>

                                <th class="text-end px-4">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($missionsATraiter as $mission)

                                <tr>

                                    <td class="px-4 fw-semibold">

                                        {{ $mission->numero }}

                                    </td>

                                    <td>

                                        {{ Str::limit($mission->objet, 45) }}

                                    </td>

                                    <td>

                                        @if($mission->type === 'internationale')

                                            <span class="badge bg-primary-subtle text-primary">
                                                International
                                            </span>

                                        @else

                                            <span class="badge bg-success-subtle text-success">
                                                Nationale
                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        {{ $mission->date_depart
                                            ? \Carbon\Carbon::parse($mission->date_depart)->format('d/m/Y')
                                            : '-' }}

                                    </td>

                                    <td>

                                        @php
                                            $badgeClass = match($mission->statut) {
                                                'brouillon' => 'bg-secondary-subtle text-secondary',
                                                'en_attente_rh' => 'bg-warning-subtle text-warning',
                                                'en_attente_moyens_generaux' => 'bg-info-subtle text-info',
                                                'en_attente_rh_apres_mg' => 'bg-warning-subtle text-warning',
                                                'en_attente_budget' => 'bg-primary-subtle text-primary',
                                                'en_attente_comptabilite' => 'bg-danger-subtle text-danger',
                                                'payee' => 'bg-success-subtle text-success',
                                                'annulee' => 'bg-dark-subtle text-dark',
                                                default => 'bg-secondary-subtle text-secondary',
                                            };
                                        @endphp

                                        <span class="badge {{ $badgeClass }}">

                                            {{ $libellesStatuts[$mission->statut] ?? $mission->statut }}

                                        </span>

                                    </td>

                                    <td class="text-end px-4">

                                        <a
                                            href="{{ route('missions.show', $mission->id) }}"
                                            class="btn btn-sm btn-outline-success"
                                        >

                                            <i class="bi bi-eye me-1"></i>

                                            Ouvrir

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         DERNIÈRES MISSIONS
         ========================================================= --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        Dernières missions
                    </h5>

                    <small class="text-muted">
                        Les missions récemment enregistrées
                    </small>

                </div>

                <a
                    href="{{ route('missions.index') }}"
                    class="btn btn-sm btn-outline-success"
                >

                    Voir tout

                    <i class="bi bi-arrow-right ms-1"></i>

                </a>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                Mission
                            </th>

                            <th>
                                Objet
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Destination
                            </th>

                            <th>
                                Départ
                            </th>

                            <th>
                                Montant
                            </th>

                            <th>
                                Statut
                            </th>

                            <th class="text-end px-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($missionsRecentes as $mission)

                            <tr>

                                <td class="px-4 fw-semibold">

                                    <span class="fw-bold">
                                        {{ $mission->numero }}
                                    </span>

                                    <span class="badge bg-dark">
                                        {{ $mission->numero_ordre }}
                                    </span>

                                </td>

                                <td>

                                    {{ Str::limit($mission->objet, 40) }}

                                </td>

                                <td>

                                    @if($mission->type === 'internationale')

                                        <span class="badge bg-primary-subtle text-primary">

                                            International

                                        </span>

                                    @else

                                        <span class="badge bg-success-subtle text-success">

                                            Nationale

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{ $mission->destination ?? '-' }}

                                </td>

                                <td>

                                    {{ $mission->date_depart
                                        ? \Carbon\Carbon::parse($mission->date_depart)->format('d/m/Y')
                                        : '-' }}

                                </td>

                                <td class="fw-semibold">

                                    {{ number_format(
                                        (float) ($mission->montant_total_general ?? 0),
                                        0,
                                        ',',
                                        ' '
                                    ) }}

                                    FCFA

                                </td>

                                <td>

                                    @php
                                        $badgeClass = match($mission->statut) {
                                            'brouillon' => 'bg-secondary-subtle text-secondary',
                                            'en_attente_rh' => 'bg-warning-subtle text-warning',
                                            'en_attente_moyens_generaux' => 'bg-info-subtle text-info',
                                            'en_attente_rh_apres_mg' => 'bg-warning-subtle text-warning',
                                            'en_attente_budget' => 'bg-primary-subtle text-primary',
                                            'en_attente_comptabilite' => 'bg-danger-subtle text-danger',
                                            'payee' => 'bg-success-subtle text-success',
                                            'annulee' => 'bg-dark-subtle text-dark',
                                            default => 'bg-secondary-subtle text-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $badgeClass }}">

                                        {{ $libellesStatuts[$mission->statut] ?? $mission->statut }}

                                    </span>

                                </td>

                                <td class="text-end px-4">

                                    <a
                                        href="{{ route('missions.show', $mission->id) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Voir la mission"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5 text-muted"
                                >

                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                                    Aucune mission sur cette période.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     STYLE DASHBOARD
     ========================================================= --}}
<style>

    .dashboard-card {
        transition: all 0.25s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.10) !important;
    }

    .dashboard-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 1.35rem;
    }

    .finance-item {
        display: flex;
        justify-content: space-between;
        align-items: center;

        padding: 12px 0;

        border-bottom: 1px solid #f0f0f0;
    }

    .finance-item:last-child {
        border-bottom: 0;
    }

    .table > :not(caption) > * > * {
        padding-top: 0.85rem;
        padding-bottom: 0.85rem;
    }

</style>


{{-- =========================================================
     CHART.JS
     ========================================================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ÉVOLUTION MENSUELLE
    |--------------------------------------------------------------------------
    */

    const evolutionCanvas =
        document.getElementById('missionsEvolutionChart');

    if (evolutionCanvas) {

        new Chart(evolutionCanvas, {

            type: 'line',

            data: {

                labels: @json($labelsMois),

                datasets: [{

                    label: 'Missions',

                    data: @json($donneesMois),

                    borderWidth: 3,

                    tension: 0.35,

                    fill: true,

                    pointRadius: 4,

                    pointHoverRadius: 7

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {
                    intersect: false,
                    mode: 'index'
                },

                plugins: {

                    legend: {
                        display: false
                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {
                            precision: 0
                        }

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | NATIONAL / INTERNATIONAL
    |--------------------------------------------------------------------------
    */

    const typeCanvas =
        document.getElementById('typeMissionChart');

    if (typeCanvas) {

        new Chart(typeCanvas, {

            type: 'doughnut',

            data: {

                labels: [
                    'Nationales',
                    'Internationales'
                ],

                datasets: [{

                    data: [
                        {{ $missionsNationales }},
                        {{ $missionsInternationales }}
                    ],

                    borderWidth: 0

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '68%',

                plugins: {

                    legend: {

                        position: 'bottom'

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | STATUTS
    |--------------------------------------------------------------------------
    */

    const statutCanvas =
        document.getElementById('statutMissionChart');

    if (statutCanvas) {

        new Chart(statutCanvas, {

            type: 'bar',

            data: {

                labels: @json($statutsLabels),

                datasets: [{

                    label: 'Nombre de missions',

                    data: @json($statutsData),

                    borderRadius: 7,

                    borderSkipped: false

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    }

                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {
                            precision: 0
                        }

                    },

                    x: {

                        ticks: {

                            autoSkip: false

                        }

                    }

                }

            }

        });

    }

});

</script>

@endsection
