@extends('layouts.ap')

@section('title', 'Gestion des missions')

@section('content')

@php

    $user = auth()->user();

    $profil = $user->profil ?? null;

    $isAdmin = method_exists($user, 'isAdmin')
        ? $user->isAdmin()
        : ($profil === 'admin');

@endphp


<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-briefcase me-2"></i>
                Gestion des missions
            </h4>

            <div class="text-muted">
                Gestion et suivi des ordres de mission CANAM
            </div>
        </div>


        @if($isAdmin || $profil === 'direction')

            <a
                href="{{ route('missions.create') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-circle me-1"></i>
                Nouvelle mission
            </a>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- MESSAGES --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('warning'))

        <div class="alert alert-warning alert-dismissible fade show">

            <i class="bi bi-exclamation-circle me-2"></i>

            {{ session('warning') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ERREURS VALIDATION --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                <i class="bi bi-exclamation-triangle me-2"></i>
                Vérifiez les informations suivantes :
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FILTRES --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header bg-white">

            <strong>
                <i class="bi bi-funnel me-2"></i>
                Filtres de recherche
            </strong>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('missions.index') }}"
            >

                <div class="row g-3">

                    {{-- Recherche générale --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Recherche
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="N° mission, objet, destination..."
                        >

                    </div>


                    {{-- N° ordre --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            N° ordre
                        </label>

                        <input
                            type="text"
                            name="numero_ordre"
                            class="form-control"
                            value="{{ request('numero_ordre') }}"
                            placeholder="N° ordre"
                        >

                    </div>


                    {{-- Statut --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Statut
                        </label>

                        <select
                            name="statut"
                            class="form-select"
                        >

                            <option value="">
                                Tous les statuts
                            </option>

                            <option
                                value="brouillon"
                                @selected(request('statut') === 'brouillon')
                            >
                                Brouillon
                            </option>

                            <option
                                value="en_attente_rh"
                                @selected(request('statut') === 'en_attente_rh')
                            >
                                En attente RH
                            </option>

                            <option
                                value="en_attente_moyens_generaux"
                                @selected(request('statut') === 'en_attente_moyens_generaux')
                            >
                                En attente Moyens Généraux
                            </option>

                            <option
                                value="en_attente_rh_apres_mg"
                                @selected(request('statut') === 'en_attente_rh_apres_mg')
                            >
                                En attente RH après MG
                            </option>

                            <option
                                value="en_attente_budget"
                                @selected(request('statut') === 'en_attente_budget')
                            >
                                En attente Budget
                            </option>

                            <option
                                value="en_attente_comptabilite"
                                @selected(request('statut') === 'en_attente_comptabilite')
                            >
                                En attente Comptabilité
                            </option>

                            <option
                                value="payee"
                                @selected(request('statut') === 'payee')
                            >
                                Payée
                            </option>

                            <option
                                value="terminee"
                                @selected(request('statut') === 'terminee')
                            >
                                Terminée
                            </option>

                            <option
                                value="annulee"
                                @selected(request('statut') === 'annulee')
                            >
                                Annulée
                            </option>

                        </select>

                    </div>


                    {{-- Type --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Type
                        </label>

                        <select
                            name="type"
                            class="form-select"
                        >

                            <option value="">
                                Tous les types
                            </option>

                            <option
                                value="nationale"
                                @selected(request('type') === 'nationale')
                            >
                                Nationale
                            </option>

                            <option
                                value="internationale"
                                @selected(request('type') === 'internationale')
                            >
                                Internationale
                            </option>

                        </select>

                    </div>


                    {{-- Direction --}}
                    @if(auth()->user()->profil !== 'direction')

    <div class="col-md-3">

        <label class="form-label">
            Direction
        </label>

        <select name="direction"
                class="form-select">

            <option value="">
                Toutes les directions
            </option>

            @foreach($directions as $direction)

                <option value="{{ $direction->id }}"
                    {{ request('direction') == $direction->id ? 'selected' : '' }}>

                    {{ $direction->nom_direction }}

                </option>

            @endforeach

        </select>

    </div>

@else

    <div class="col-md-3">

        <label class="form-label">
            Direction
        </label>

        <input type="text"
               class="form-control"
               value="{{ auth()->user()->direction->nom_direction ?? '' }}"
               readonly>

    </div>

@endif


                    {{-- Destination --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Destination
                        </label>

                        <input
                            type="text"
                            name="destination"
                            class="form-control"
                            value="{{ request('destination') }}"
                            placeholder="Destination"
                        >

                    </div>


                    {{-- Date départ du --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Départ du
                        </label>

                        <input
                            type="date"
                            name="date_depart_du"
                            class="form-control"
                            value="{{ request('date_depart_du') }}"
                        >

                    </div>


                    {{-- Date départ au --}}
                    <div class="col-md-2">

                        <label class="form-label">
                            Départ au
                        </label>

                        <input
                            type="date"
                            name="date_depart_au"
                            class="form-control"
                            value="{{ request('date_depart_au') }}"
                        >

                    </div>

                </div>


                <div class="mt-3 d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-search me-1"></i>
                        Rechercher
                    </button>


                    <a
                        href="{{ route('missions.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                        Réinitialiser
                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TABLEAU --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm">

        <div class="card-header bg-white d-flex justify-content-between">

            <strong>
                <i class="bi bi-list-ul me-2"></i>
                Liste des missions
            </strong>

            <span class="badge bg-secondary">
                {{ $missions->total() }} mission(s)
            </span>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>N°</th>

                        <th>N° ordre</th>

                        <th>Objet</th>

                        <th>Direction</th>

                        <th>Type</th>

                        <th>Destination</th>

                        <th>Départ</th>

                        <th>Retour</th>

                        <th>Durée</th>

                        <th>Statut</th>

                        <th>État / Retour</th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($missions as $mission)

                        <tr>

                            {{-- N° mission --}}
                            <td>

                                <a
                                    href="{{ route('missions.show', $mission->id) }}"
                                    class="fw-bold text-decoration-none"
                                >
                                    {{ $mission->numero }}
                                </a>

                            </td>


                            {{-- N° ordre --}}
                            <td>

                                @if($mission->numero_ordre)

                                    <span class="badge bg-dark">
                                        {{ $mission->numero_ordre }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Objet --}}
                            <td>

                                <div class="fw-semibold">
                                    {{ $mission->objet }}
                                </div>

                            </td>


                            {{-- Direction --}}
                            <td>

                                {{ $mission->direction }}

                            </td>


                            {{-- Type --}}
                            <td>

                                @if($mission->type === 'nationale')

                                    <span class="badge bg-primary">
                                        Nationale
                                    </span>

                                @else

                                    <span class="badge bg-info">
                                        Internationale
                                    </span>

                                @endif

                            </td>


                            {{-- Destination --}}
                            <td>

                                {{ $mission->destination }}

                            </td>


                            {{-- Départ --}}
                            <td>

                                {{ \Carbon\Carbon::parse($mission->date_depart)->format('d/m/Y') }}

                            </td>


                            {{-- Retour --}}
                            <td>

                                {{ \Carbon\Carbon::parse($mission->date_retour)->format('d/m/Y') }}

                            </td>


                            {{-- Durée --}}
                            <td>

                                {{ $mission->duree }}
                                jour{{ $mission->duree > 1 ? 's' : '' }}

                            </td>


                            {{-- Statut --}}
                            <td>

                                @switch($mission->statut)

                                    @case('brouillon')
                                        <span class="badge bg-secondary">
                                            Brouillon
                                        </span>
                                        @break

                                    @case('en_attente_rh')
                                        <span class="badge bg-warning text-dark">
                                            En attente RH
                                        </span>
                                        @break

                                    @case('en_attente_moyens_generaux')
                                        <span class="badge bg-info text-dark">
                                            Moyens Généraux
                                        </span>
                                        @break

                                    @case('en_attente_rh_apres_mg')
                                        <span class="badge bg-primary">
                                            RH après MG
                                        </span>
                                        @break

                                    @case('en_attente_budget')
                                        <span class="badge bg-warning text-dark">
                                            Budget
                                        </span>
                                        @break

                                    @case('en_attente_comptabilite')
                                        <span class="badge bg-primary">
                                            Comptabilité
                                        </span>
                                        @break

                                    @case('payee')
                                        <span class="badge bg-success">
                                            Payée
                                        </span>
                                        @break

                                    @case('terminee')
                                        <span class="badge bg-success">
                                            Terminée
                                        </span>
                                        @break

                                    @case('annulee')
                                        <span class="badge bg-danger">
                                            Annulée
                                        </span>
                                        @break

                                    @default
                                        <span class="badge bg-secondary">
                                            {{ $mission->statut }}
                                        </span>

                                @endswitch

                            </td>


                            <td style="min-width: 200px;">

    @php
        $dernierRetour = $mission->commentaires
            ->where('action', 'retour')
            ->sortByDesc('created_at')
            ->first();
    @endphp

    @if($dernierRetour)

        <div class="text-warning fw-bold">

            <i class="bi bi-arrow-return-left"></i>

            RETOURNÉE

        </div>

        <div class="small mt-1">

            <strong>
                {{ $dernierRetour->etape }}
            </strong>

            →

            @if($dernierRetour->etape === 'RH')
                Direction
            @elseif($dernierRetour->etape === 'MOYENS_GENERAUX')
                RH
            @elseif($dernierRetour->etape === 'RH_APRES_MG')
                Moyens Généraux
            @elseif($dernierRetour->etape === 'BUDGET')
                RH
            @elseif($dernierRetour->etape === 'COMPTABILITE')
                Budget
            @else
                -
            @endif

        </div>

        <small class="text-muted">

            {{ \Carbon\Carbon::parse($dernierRetour->created_at)
                ->format('d/m/Y à H:i') }}

        </small>

        <div class="mt-1">

            <a href="{{ route('missions.show', $mission->id) }}"
               class="small text-decoration-none">

                <i class="bi bi-chat-left-text"></i>

                Voir le commentaire

            </a>

        </div>

    @else

        <span class="text-muted">
            Aucun retour
        </span>

    @endif

</td>


                            {{-- ACTIONS --}}
                            <td class="text-end">

                                <div class="btn-group">

                                    {{-- Voir --}}
                                    <a
                                        href="{{ route('missions.show', $mission->id) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Voir"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    {{-- ================================================= --}}
                                    {{-- DIRECTION --}}
                                    {{-- ================================================= --}}

                                    @if(
                                        $isAdmin ||
                                        $profil === 'direction'
                                    )

                                        {{-- Modifier --}}
                                        @if($mission->statut === 'brouillon')

                                            <a
                                                href="{{ route('missions.edit', $mission->id) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                                title="Modifier"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                        @endif


                                        {{-- Transmettre RH --}}
                                        @if($mission->statut === 'brouillon')

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalTransmettreRH{{ $mission->id }}"
                                                title="Transmettre à RH"
                                            >
                                                <i class="bi bi-send"></i>
                                            </button>

                                        @endif

                                    @endif





                                    {{-- ================================================= --}}
                                    {{-- SUPPRESSION --}}
                                    {{-- ================================================= --}}

                                    @if(
                                        ($isAdmin || $profil === 'direction') &&
                                        $mission->statut === 'brouillon'
                                    )

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalSuppression{{ $mission->id }}"
                                            title="Supprimer"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    @endif


                                    {{-- ================================================= --}}
                                    {{-- ANNULATION --}}
                                    {{-- ================================================= --}}

                                    @if(
                                        !in_array(
                                            $mission->statut,
                                            [
                                                'payee',
                                                'terminee',
                                                'annulee'
                                            ]
                                        )
                                    )

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalAnnulation{{ $mission->id }}"
                                            title="Annuler"
                                        >
                                            <i class="bi bi-x-circle"></i>
                                        </button>

                                    @endif

                                </div>

                            </td>

                        </tr>


                        {{-- ========================================================= --}}
                        {{-- MODAL TRANSMISSION RH --}}
                        {{-- ========================================================= --}}

                        @if($mission->statut === 'brouillon')

                            <div
                                class="modal fade"
                                id="modalTransmettreRH{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Transmettre à RH
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>

                                        <div class="modal-body">

                                            Voulez-vous transmettre la mission
                                            <strong>
                                                {{ $mission->numero }}
                                            </strong>
                                            à RH ?

                                        </div>

                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Annuler
                                            </button>

                                            <form
                                                method="POST"
                                                action="{{ route('missions.transmettre-rh', $mission->id) }}"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-primary"
                                                >
                                                    Confirmer
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ========================================================= --}}
                        {{-- MODAL VALIDATION RH --}}
                        {{-- ========================================================= --}}

                        @if($mission->statut === 'en_attente_rh')

                            <div
                                class="modal fade"
                                id="modalValidationRH{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route('missions.valider-rh', $mission->id) }}"
                                        >

                                            @csrf

                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    Traitement RH
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Chauffeur
                                                    </label>

                                                    <select
                                                        name="chauffeur_id"
                                                        class="form-select"
                                                        required
                                                    >

                                                        <option value="">
                                                            Sélectionner le chauffeur
                                                        </option>

                                                        @foreach($chauffeurs as $chauffeur)

                                                            <option
                                                                value="{{ $chauffeur->id }}"
                                                                @selected(
                                                                    $mission->chauffeur_id == $chauffeur->id
                                                                )
                                                            >

                                                                {{ $chauffeur->prenom }}
                                                                {{ $chauffeur->nom }}

                                                                @if($chauffeur->matricule)
                                                                    — {{ $chauffeur->matricule }}
                                                                @endif

                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Numéro d'ordre
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="numero_ordre"
                                                        class="form-control"
                                                        value="{{ $mission->numero_ordre }}"
                                                        required
                                                    >

                                                </div>


                                                <div class="alert alert-info mb-0">

                                                    <i class="bi bi-info-circle me-1"></i>

                                                    Le chauffeur sera automatiquement
                                                    ajouté aux participants et ses
                                                    frais et indemnités seront calculés.

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
                                                    class="btn btn-success"
                                                >
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Valider RH
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            {{-- Retour Direction --}}

                            <div
                                class="modal fade"
                                id="modalRetourDirection{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route('missions.retour-direction', $mission->id) }}"
                                        >

                                            @csrf

                                            <div class="modal-header">

                                                <h5 class="modal-title text-danger">

                                                    <i class="bi bi-arrow-left me-2"></i>

                                                    Retour à la Direction

                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="alert alert-warning">

                                                    La mission sera remise en
                                                    <strong>brouillon</strong>
                                                    afin que la Direction puisse
                                                    effectuer les corrections.

                                                </div>


                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Motif du retour
                                                        <span class="text-danger">*</span>

                                                    </label>

                                                    <textarea
                                                        name="commentaire"
                                                        class="form-control"
                                                        rows="4"
                                                        maxlength="2000"
                                                        required
                                                        placeholder="Indiquez les corrections demandées..."
                                                    ></textarea>

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
                                                    class="btn btn-danger"
                                                >
                                                    Retourner à la Direction
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ========================================================= --}}
                        {{-- MODAL VÉHICULE --}}
                        {{-- ========================================================= --}}

                        @if(
                            $mission->statut ===
                            'en_attente_moyens_generaux'
                        )

                            @php

                                $voituresDisponibles =
                                    \App\Models\Voiture::where(
                                        'disponible',
                                        true
                                    )
                                    ->where(
                                        'etat',
                                        '!=',
                                        'hors_service'
                                    )
                                    ->orderBy(
                                        'immatriculation'
                                    )
                                    ->get();

                            @endphp


                            <div
                                class="modal fade"
                                id="modalVehicule{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route('missions.affecter-voiture', $mission->id) }}"
                                        >

                                            @csrf

                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    Affectation du véhicule
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Véhicule
                                                    </label>

                                                    <select
                                                        name="voiture_id"
                                                        class="form-select"
                                                        required
                                                    >

                                                        <option value="">
                                                            Sélectionner un véhicule
                                                        </option>

                                                        @foreach(
                                                            $voituresDisponibles
                                                            as $voiture
                                                        )

                                                            <option
                                                                value="{{ $voiture->id }}"
                                                            >

                                                                {{ $voiture->immatriculation }}

                                                                @if($voiture->marque)
                                                                    — {{ $voiture->marque }}
                                                                @endif

                                                                @if($voiture->modele)
                                                                    {{ $voiture->modele }}
                                                                @endif

                                                            </option>

                                                        @endforeach

                                                    </select>

                                                </div>


                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Observation
                                                    </label>

                                                    <textarea
                                                        name="observation_moyens_generaux"
                                                        class="form-control"
                                                        rows="3"
                                                        maxlength="2000"
                                                    ></textarea>

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
                                                    class="btn btn-info"
                                                >
                                                    Affecter le véhicule
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            {{-- Retour RH --}}

                            <div
                                class="modal fade"
                                id="modalRetourRH{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route('missions.retour-rh', $mission->id) }}"
                                        >

                                            @csrf

                                            <div class="modal-header">

                                                <h5 class="modal-title text-danger">

                                                    Retour à RH

                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Motif du retour
                                                        <span class="text-danger">*</span>

                                                    </label>

                                                    <textarea
                                                        name="commentaire"
                                                        class="form-control"
                                                        rows="4"
                                                        maxlength="2000"
                                                        required
                                                        placeholder="Indiquez la raison du retour..."
                                                    ></textarea>

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
                                                    class="btn btn-danger"
                                                >
                                                    Retourner à RH
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ========================================================= --}}
                        {{-- RH APRÈS MG --}}
                        {{-- ========================================================= --}}

                        @if(
                            $mission->statut ===
                            'en_attente_rh_apres_mg'
                        )

                            {{-- Envoyer Budget --}}

                            <div
                                class="modal fade"
                                id="modalEnvoyerBudget{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Envoyer au Budget
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body">

                                            <p>
                                                Voulez-vous envoyer cette mission
                                                au Budget ?
                                            </p>

                                            <div class="alert alert-info">

                                                <strong>
                                                    Montant total :
                                                </strong>

                                                {{ number_format(
                                                    $mission->montant_total,
                                                    0,
                                                    ',',
                                                    ' '
                                                ) }}
                                                FCFA

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

                                            <form
                                                method="POST"
                                                action="{{ route('missions.envoyer-budget', $mission->id) }}"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-success"
                                                >
                                                    Envoyer au Budget
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- Retour MG --}}

                            <div
                                class="modal fade"
                                id="modalRetourMG{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route('missions.retour-moyens-generaux', $mission->id) }}"
                                        >

                                            @csrf

                                            <div class="modal-header">

                                                <h5 class="modal-title text-danger">
                                                    Retour aux Moyens Généraux
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Motif du retour
                                                        <span class="text-danger">*</span>

                                                    </label>

                                                    <textarea
                                                        name="commentaire"
                                                        class="form-control"
                                                        rows="4"
                                                        maxlength="2000"
                                                        required
                                                    ></textarea>

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
                                                    class="btn btn-danger"
                                                >
                                                    Retourner aux Moyens Généraux
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ========================================================= --}}
                        {{-- BUDGET --}}
                        {{-- ========================================================= --}}

                        @if(
                            $mission->statut ===
                            'en_attente_budget'
                        )

                            {{-- Validation Budget --}}

                            <div
                                class="modal fade"
                                id="modalValidationBudget{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Validation du Budget
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body">

                                            <p>
                                                Valider le budget de cette mission ?
                                            </p>

                                            <div class="alert alert-info">

                                                <strong>
                                                    Montant :
                                                </strong>

                                                {{ number_format(
                                                    $mission->montant_total,
                                                    0,
                                                    ',',
                                                    ' '
                                                ) }}
                                                FCFA

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

                                            <form
                                                method="POST"
                                                action="{{ route('missions.valider-budget', $mission->id) }}"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-success"
                                                >
                                                    Valider le Budget
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- Retour RH --}}

                            <div
                                class="modal fade"
                                id="modalRetourRHBudget{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route('missions.retour-rh-budget', $mission->id) }}"
                                        >

                                            @csrf

                                            <div class="modal-header">

                                                <h5 class="modal-title text-danger">
                                                    Retour à RH
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Motif du retour
                                                        <span class="text-danger">*</span>

                                                    </label>

                                                    <textarea
                                                        name="commentaire"
                                                        class="form-control"
                                                        rows="4"
                                                        maxlength="2000"
                                                        required
                                                    ></textarea>

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
                                                    class="btn btn-danger"
                                                >
                                                    Retourner à RH
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ========================================================= --}}
                        {{-- COMPTABILITÉ --}}
                        {{-- ========================================================= --}}

                        @if(
                            $mission->statut ===
                            'en_attente_comptabilite'
                        )

                            {{-- Paiement --}}

                            <div
                                class="modal fade"
                                id="modalPaiement{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route('missions.payer', $mission->id) }}"
                                        >

                                            @csrf

                                            <div class="modal-header">

                                                <h5 class="modal-title">
                                                    Enregistrer le paiement
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="alert alert-info">

                                                    Montant total :

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


                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Montant payé
                                                    </label>

                                                    <input
                                                        type="number"
                                                        name="montant_paye"
                                                        class="form-control"
                                                        value="{{ $mission->montant_total }}"
                                                        min="0"
                                                        step="0.01"
                                                        required
                                                    >

                                                </div>


                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Date de paiement
                                                    </label>

                                                    <input
                                                        type="date"
                                                        name="date_paiement"
                                                        class="form-control"
                                                        value="{{ now()->format('Y-m-d') }}"
                                                        required
                                                    >

                                                </div>


                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Référence paiement
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="reference_paiement"
                                                        class="form-control"
                                                        required
                                                    >

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
                                                    class="btn btn-success"
                                                >
                                                    Enregistrer le paiement
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            {{-- Retour Budget --}}

                            <div
                                class="modal fade"
                                id="modalRetourBudget{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route('missions.retour-budget', $mission->id) }}"
                                        >

                                            @csrf

                                            <div class="modal-header">

                                                <h5 class="modal-title text-danger">
                                                    Retour au Budget
                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Motif du retour
                                                        <span class="text-danger">*</span>

                                                    </label>

                                                    <textarea
                                                        name="commentaire"
                                                        class="form-control"
                                                        rows="4"
                                                        maxlength="2000"
                                                        required
                                                    ></textarea>

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
                                                    class="btn btn-danger"
                                                >
                                                    Retourner au Budget
                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ========================================================= --}}
                        {{-- TERMINER --}}
                        {{-- ========================================================= --}}

                        @if($mission->statut === 'payee')

                            <div
                                class="modal fade"
                                id="modalTerminer{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title">
                                                Terminer la mission
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body">

                                            La mission

                                            <strong>
                                                {{ $mission->numero }}
                                            </strong>

                                            a été payée.

                                            <br>

                                            Voulez-vous la marquer comme terminée ?

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Annuler
                                            </button>

                                            <form
                                                method="POST"
                                                action="{{ route('missions.terminer', $mission->id) }}"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-success"
                                                >
                                                    Terminer
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ========================================================= --}}
                        {{-- SUPPRESSION --}}
                        {{-- ========================================================= --}}

                        @if($mission->statut === 'brouillon')

                            <div
                                class="modal fade"
                                id="modalSuppression{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title text-danger">
                                                Supprimer la mission
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body">

                                            Voulez-vous réellement supprimer
                                            cette mission ?

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Annuler
                                            </button>

                                            <form
                                                method="POST"
                                                action="{{ route('missions.destroy', $mission->id) }}"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >
                                                    Supprimer
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- ========================================================= --}}
                        {{-- ANNULATION --}}
                        {{-- ========================================================= --}}

                        @if(
                            !in_array(
                                $mission->statut,
                                [
                                    'payee',
                                    'terminee',
                                    'annulee'
                                ]
                            )
                        )

                            <div
                                class="modal fade"
                                id="modalAnnulation{{ $mission->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title text-danger">
                                                Annuler la mission
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body">

                                            Voulez-vous annuler la mission

                                            <strong>
                                                {{ $mission->numero }}
                                            </strong>

                                            ?

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Non
                                            </button>

                                            <form
                                                method="POST"
                                                action="{{ route('missions.annuler', $mission->id) }}"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >
                                                    Oui, annuler
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endif

                    @empty

                        <tr>

                            <td
                                colspan="11"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <i
                                        class="bi bi-inbox"
                                        style="font-size: 3rem;"
                                    ></i>

                                    <div class="mt-2">
                                        Aucune mission trouvée.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ========================================================= --}}
        {{-- PAGINATION --}}
        {{-- ========================================================= --}}

        @if($missions->hasPages())

            <div class="card-footer bg-white">

                {{ $missions->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
