@extends('layouts.ap')

@section('title', 'Gestion des missions')

@section('content')

    @php

        $user = auth()->user();

        $profil = $user->profil ?? null;

        $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : $profil === 'admin';

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


            @if ($isAdmin || $profil === 'direction')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalInitiationNationale">
                    <i class="bi bi-plus-circle me-1"></i>
                    Initier une mission nationale
                </button>
            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- MESSAGES --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>
        @endif


        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">

                <i class="bi bi-exclamation-triangle me-2"></i>

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>
        @endif


        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">

                <i class="bi bi-exclamation-circle me-2"></i>

                {{ session('warning') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- ERREURS VALIDATION --}}
        {{-- ========================================================= --}}

        @if ($errors->any())

            <div class="alert alert-danger">

                <strong>
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Vérifiez les informations suivantes :
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- INITIATION D'UNE MISSION NATIONALE --}}
        {{-- ========================================================= --}}

        @if ($isAdmin || $profil === 'direction')
            <div class="modal fade" id="modalInitiationNationale" tabindex="-1" aria-labelledby="titreInitiationNationale"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">

                        <form action="{{ route('mission-initiations.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title" id="titreInitiationNationale">
                                    <i class="bi bi-file-earmark-plus me-2"></i>
                                    Initier une mission nationale
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Fermer"></button>
                            </div>

                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Enregistrez d'abord le brouillon. Vous pourrez ensuite
                                    le modifier ou le transmettre au Contrôle de gestion (CG).
                                </div>

                                <div class="mb-3">
                                    <label for="objet_initiation" class="form-label">
                                        Objet de la mission <span class="text-danger">*</span>
                                    </label>
                                    <textarea id="objet_initiation" name="objet" class="form-control" rows="3" maxlength="2000" required
                                        placeholder="Décrivez l'objet de la mission">{{ old('objet') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="tdr_initiation" class="form-label">
                                        Termes de référence (TDR)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input id="tdr_initiation" type="file" name="tdr" class="form-control"
                                        accept=".pdf,.doc,.docx" required>
                                    <div class="form-text">
                                        Formats acceptés : PDF, DOC ou DOCX. Taille maximale : 10 Mo.
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                    Fermer
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>
                                    Enregistrer le brouillon
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- TABLEAU DES INITIATIONS NATIONALES --}}
        {{-- ========================================================= --}}

        @if (isset($initiations) && ($isAdmin || in_array($profil, ['direction', 'cg', 'controle_gestion', 'dga', 'dagrh', 'comptabilite', 'moyens_generaux', 'budget', 'rh'], true)))

            <div class="card shadow-sm mb-4">

                <div
                    class="card-header bg-white d-flex justify-content-between
                align-items-center flex-wrap gap-2">
                    <strong>
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Initiations des missions nationales
                    </strong>

                    <span class="badge bg-secondary">
                        {{ $initiations->total() }} dossier(s)
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Référence</th>
                                <th>Objet</th>
                                <th>Demandeur</th>
                                <th>Date d'initiation</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($initiations as $initiation)
                                <tr>
                                    <td class="fw-semibold">
                                        INIT-{{ $initiation->id }}
                                    </td>

                                    <td style="min-width:220px">
                                        {{ $initiation->objet }}
                                    </td>

                                    <td>
                                        {{ $initiation->demandeur->name ?? ($initiation->demandeur->nom ?? 'Utilisateur inconnu') }}
                                    </td>

                                    <td>
                                        {{ $initiation->created_at ? $initiation->created_at->format('d/m/Y H:i') : '—' }}
                                    </td>

                                    <td>
                                        @switch($initiation->statut)
                                            @case('brouillon')
                                                <span class="badge bg-secondary">Brouillon</span>
                                            @break

                                            @case('en_cours_cg')
                                                <span class="badge bg-info text-dark">
                                                    En cours de traitement CG
                                                </span>
                                            @break

                                            @case('en_attente_cg')
    <span class="badge bg-secondary">
        En attente du Contrôle de gestion
    </span>
@break

@case('en_attente_service_demandeur')
    <span class="badge bg-info text-dark">
        Direction — Consultation
    </span>
@break

@case('en_attente_visa_dga')
    <span class="badge bg-primary">
        En attente du visa DGA
    </span>
@break

@case('en_attente_visa_dagrh')
    <span class="badge bg-primary">
        En attente du visa DAGRH
    </span>
@break

@case('en_attente_mg')
    <span class="badge bg-info text-dark">
        Moyens généraux
    </span>
@break

@case('en_attente_rh')
    <span class="badge bg-warning text-dark">
        En attente RH
    </span>
@break

                                            @default
                                                <span class="badge bg-light text-dark">
                                                    {{ $initiation->statut }}
                                                </span>
                                        @endswitch
                                    </td>

                                    <td class="text-end">
                                        <div class="d-inline-flex flex-wrap gap-1">

                                            @if ($initiation->tdr_path)
                                                <a href="{{ route('mission-initiations.tdr', $initiation->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Télécharger le TDR">
                                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                                    TDR
                                                </a>
                                            @endif

                                            @php
                                                $estProprietaire = (int) $initiation->user_id === (int) $user->id;

                                                $peutGererBrouillon =
                                                    $initiation->statut === 'brouillon' &&
                                                    ($isAdmin || ($profil === 'direction' && $estProprietaire)) &&
                                                    !$initiation->mission;
                                            @endphp

                                            @if ($peutGererBrouillon)
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modifierInitiation{{ $initiation->id }}"
                                                    title="Modifier le brouillon">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <button type="button" class="btn btn-sm btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#transmettreInitiation{{ $initiation->id }}">
                                                    <i class="bi bi-send me-1"></i>
                                                    Transmettre au CG
                                                </button>

                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#supprimerInitiation{{ $initiation->id }}"
                                                    title="Supprimer le brouillon">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                            @if (
                                                $initiation->statut === 'en_attente_cg' &&
                                                    in_array(strtolower(trim(auth()->user()->profil ?? '')), ['controle_gestion', 'admin'], true) &&
                                                    !$initiation->mission)
                                                <a href="{{ route('missions.create', [
                                                    'initiation_id' => $initiation->id,
                                                ]) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-folder-open"></i>
                                                    Traiter
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                            Aucune initiation de mission nationale.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($initiations->hasPages())
                        <div class="card-footer bg-white">
                            {{ $initiations->links() }}
                        </div>
                    @endif
                </div>


                {{-- ========================================================= --}}
                {{-- MODALES DES INITIATIONS : HORS DU TABLEAU --}}
                {{-- ========================================================= --}}

                @foreach ($initiations as $initiation)
                    @php
                        $estProprietaire = (int) $initiation->user_id === (int) $user->id;

                        $peutGererBrouillon =
                            $initiation->statut === 'brouillon' &&
                            ($isAdmin || ($profil === 'direction' && $estProprietaire)) &&
                            !$initiation->mission;
                    @endphp

                    @if ($peutGererBrouillon)
                        {{-- MODIFIER --}}
                        <div class="modal fade" id="modifierInitiation{{ $initiation->id }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">

                                    <form method="POST"
                                        action="{{ route('mission-initiations.update', $initiation->id) }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                Modifier INIT-{{ $initiation->id }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Objet de la mission
                                                </label>
                                                <textarea name="objet" class="form-control" rows="3" maxlength="2000" required>{{ $initiation->objet }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Remplacer le TDR (facultatif)
                                                </label>
                                                <input type="file" name="tdr" class="form-control"
                                                    accept=".pdf,.doc,.docx">
                                                <div class="form-text">
                                                    Laissez vide pour conserver le TDR actuel.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                Annuler
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-save me-1"></i>
                                                Enregistrer
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>


                        {{-- TRANSMETTRE AU CG --}}
                        <div class="modal fade" id="transmettreInitiation{{ $initiation->id }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Transmettre au CG</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        Confirmez-vous la transmission du dossier
                                        <strong>INIT-{{ $initiation->id }}</strong>
                                        au Contrôle de gestion ?

                                        <div class="alert alert-warning mt-3 mb-0">
                                            Après transmission, le brouillon ne sera plus
                                            modifiable par la Direction. Le CG pourra examiner
                                            le TDR et compléter les informations de la mission.
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                            Annuler
                                        </button>

                                        <form method="POST"
                                            action="{{ route('mission-initiations.transmettre-cg', $initiation->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-send me-1"></i>
                                                Confirmer la transmission
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>


                        {{-- SUPPRIMER --}}
                        <div class="modal fade" id="supprimerInitiation{{ $initiation->id }}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title text-danger">
                                            Supprimer le brouillon
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        Voulez-vous supprimer l'initiation
                                        <strong>INIT-{{ $initiation->id }}</strong> ?
                                        Cette action est réservée aux brouillons non transmis.
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                            Annuler
                                        </button>

                                        <form method="POST"
                                            action="{{ route('mission-initiations.destroy', $initiation->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-trash me-1"></i>
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
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

                    <form method="GET" action="{{ route('missions.index') }}">

                        <div class="row g-3">

                            {{-- Recherche générale --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Recherche
                                </label>

                                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                    placeholder="N° mission, objet, destination...">

                            </div>


                            {{-- Statut --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Statut
                                </label>

                                <select name="statut" class="form-select">

                                    <option value="">
                                        Tous les statuts
                                    </option>

                                    <option value="brouillon" @selected(request('statut') === 'brouillon')>
                                        Brouillon
                                    </option>

                                    <option value="en_attente_rh" @selected(request('statut') === 'en_attente_rh')>
                                        En attente RH
                                    </option>

                                    <option value="en_attente_moyens_generaux" @selected(request('statut') === 'en_attente_moyens_generaux')>
                                        En attente Moyens Généraux
                                    </option>

                                    <option value="en_attente_rh_apres_mg" @selected(request('statut') === 'en_attente_rh_apres_mg')>
                                        En attente RH après MG
                                    </option>

                                    <option value="en_attente_budget" @selected(request('statut') === 'en_attente_budget')>
                                        En attente Budget
                                    </option>

                                    <option value="en_attente_comptabilite" @selected(request('statut') === 'en_attente_comptabilite')>
                                        En attente Comptabilité
                                    </option>

                                    <option value="payee" @selected(request('statut') === 'payee')>
                                        Payée
                                    </option>

                                    <option value="terminee" @selected(request('statut') === 'terminee')>
                                        Terminée
                                    </option>

                                    <option value="annulee" @selected(request('statut') === 'annulee')>
                                        Annulée
                                    </option>

                                </select>

                            </div>


                            {{-- Type --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Type
                                </label>

                                <select name="type" class="form-select">

                                    <option value="">
                                        Tous les types
                                    </option>

                                    <option value="nationale" @selected(request('type') === 'nationale')>
                                        Nationale
                                    </option>

                                    <option value="internationale" @selected(request('type') === 'internationale')>
                                        Internationale
                                    </option>

                                </select>

                            </div>


                            {{-- Direction --}}
                            @if (auth()->user()->profil !== 'direction')

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Direction
                                    </label>

                                    <select name="direction" class="form-select">

                                        <option value="">
                                            Toutes les directions
                                        </option>

                                        @foreach ($directions as $direction)
                                            <option value="{{ $direction->nom_direction }}" @selected(request('direction') === $direction->nom_direction)>
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

                                    <input type="text" class="form-control"
                                        value="{{ auth()->user()->direction->nom_direction ?? '' }}" readonly>

                                </div>

                            @endif


                            {{-- Destination --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Destination
                                </label>

                                <input type="text" name="destination" class="form-control"
                                    value="{{ request('destination') }}" placeholder="Destination">

                            </div>


                            {{-- Date départ du --}}
                            <div class="col-md-2">

                                <label class="form-label">
                                    Départ du
                                </label>

                                <input type="date" name="date_depart_du" class="form-control"
                                    value="{{ request('date_depart_du') }}">

                            </div>


                            {{-- Date départ au --}}
                            <div class="col-md-2">

                                <label class="form-label">
                                    Départ au
                                </label>

                                <input type="date" name="date_depart_au" class="form-control"
                                    value="{{ request('date_depart_au') }}">

                            </div>

                        </div>


                        <div class="mt-3 d-flex gap-2">

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>
                                Rechercher
                            </button>


                            <a href="{{ route('missions.index') }}" class="btn btn-outline-secondary">
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

                                        <a href="{{ route('missions.show', $mission->id) }}"
                                            class="fw-bold text-decoration-none">
                                            {{ $mission->numero }}
                                        </a>

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

                                        @if ($mission->type === 'nationale')
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

                                            @case('en_attente_cg')
                                                <span class="badge bg-secondary">
                                                    En attente CG
                                                </span>
                                            @break

                                            @case('en_attente_service_demandeur')
                                                <span class="badge bg-secondary">
                                                    Direction — Consultation
                                                </span>
                                            @break

                                            @case('en_attente_visa_dga')
                                                <span class="badge bg-secondary">
                                                    En Attente Visa DGA
                                                </span>
                                            @break

                                            @case('en_attente_visa_dagrh')
                                                <span class="badge bg-secondary">
                                                    En Attente Visa DAGRH
                                                </span>
                                            @break

                                            @case('en_attente_rh')
                                                <span class="badge bg-warning text-dark">
                                                    En attente RH
                                                </span>
                                            @break

                                            @case('en_attente_mg')
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

<td style="min-width: 220px;">

    @php
        $dernierRetour = $mission->commentaires
            ->where('action', 'retour')
            ->sortByDesc('created_at')
            ->first();

        $etapesRetour = [
            'CG' => 'Contrôle de gestion',
            'DIRECTION' => 'Direction',
            'SERVICE_DEMANDEUR' => 'Service demandeur',
            'DGA' => 'Direction générale adjointe',
            'DAGRH' => 'DAGRH',
            'MOYENS_GENERAUX' => 'Moyens généraux',
            'RH' => 'Ressources humaines',
            'RH_APRES_MG' => 'RH après Moyens généraux',
            'BUDGET' => 'Budget',
            'COMPTABILITE' => 'Comptabilité',
        ];

        $destinationsRetour = [
            'CG' => 'Service demandeur',
            'SERVICE_DEMANDEUR' => 'Contrôle de gestion',
            'DIRECTION' => 'Contrôle de gestion',
            'DGA' => 'Direction',
            'DAGRH' => 'DGA',
            'MOYENS_GENERAUX' => 'RH',
            'RH' => 'Direction',
            'RH_APRES_MG' => 'Moyens généraux',
            'BUDGET' => 'RH',
            'COMPTABILITE' => 'Budget',
        ];
    @endphp

    @if ($dernierRetour)

        <div class="text-warning fw-bold">
            <i class="bi bi-arrow-return-left me-1"></i>
            RETOURNÉE
        </div>

        <div class="small mt-2">
            <strong>Étape de retour :</strong><br>

            {{ $etapesRetour[$dernierRetour->etape]
                ?? $dernierRetour->etape
                ?? 'Étape non précisée' }}
        </div>

        <div class="small mt-1">
            <strong>Destinataire :</strong><br>

            {{ $destinationsRetour[$dernierRetour->etape]
                ?? 'À vérifier' }}
        </div>

        <small class="text-muted d-block mt-2">
            {{ \Carbon\Carbon::parse($dernierRetour->created_at)->format('d/m/Y à H:i') }}
        </small>

        <div class="mt-2">
            <a href="{{ route('missions.show', $mission->id) }}"
               class="small text-decoration-none">
                <i class="bi bi-chat-left-text me-1"></i>
                Voir le commentaire
            </a>
        </div>

    @else

        <span class="text-muted">
            <i class="bi bi-check-circle me-1"></i>
            Aucun retour
        </span>

    @endif

</td>

{{-- ================================================= --}}
{{-- ACTIONS --}}
{{-- ================================================= --}}

<td class="text-end">
    <div class="btn-group">

        {{-- Voir la mission --}}
        <a href="{{ route('missions.show', $mission->id) }}"
           class="btn btn-sm btn-outline-primary"
           title="Consulter la mission">
            <i class="bi bi-eye"></i>
        </a>

        {{-- ================================================= --}}
        {{-- CONTRÔLE DE GESTION --}}
        {{-- ================================================= --}}

        @if ($isAdmin || in_array($profil, ['cg', 'controle_gestion'], true))

            {{-- Modifier pendant l'étape CG --}}
            @if ($mission->statut === 'en_attente_cg')
                <a href="{{ route('missions.edit', $mission->id) }}"
                   class="btn btn-sm btn-outline-secondary"
                   title="Compléter ou modifier la mission">
                    <i class="bi bi-pencil"></i>
                </a>

                {{-- Transmettre à la Direction pour consultation --}}
                <button type="button"
                        class="btn btn-sm btn-outline-success"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTransmettreDirection{{ $mission->id }}"
                        title="Transmettre à la Direction">
                    <i class="bi bi-send"></i>
                </button>
            @endif

            {{-- Transmission RH existante pour les brouillons --}}
            @if ($mission->statut === 'brouillon')
                <button type="button"
                        class="btn btn-sm btn-outline-success"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTransmettreRH{{ $mission->id }}"
                        title="Transmettre à RH">
                    <i class="bi bi-send"></i>
                </button>
            @endif
        @endif

        {{-- ================================================= --}}
        {{-- DIRECTION : CONSULTATION --}}
        {{-- ================================================= --}}

        @if (
            $profil === 'direction'
            && $mission->statut === 'en_attente_service_demandeur'
        )
            <button type="button"
                    class="btn btn-sm btn-outline-success"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTransmettreDGA{{ $mission->id }}"
                    title="Transmettre au DGA">
                <i class="bi bi-forward"></i>
            </button>
        @endif

        
{{-- DGA : retour à la Direction pour consultation --}}
@if (
    $profil === 'dga'
    && $mission->statut === 'en_attente_visa_dga'
)
    <button type="button"
            class="btn btn-sm btn-outline-danger"
            data-bs-toggle="modal"
            data-bs-target="#modalRetourDirectionConsultation{{ $mission->id }}"
            title="Retourner à la Direction">
        <i class="bi bi-arrow-counterclockwise"></i>
    </button>
@endif


{{-- BOUTON : TRANSMISSION DGA → DAGRH --}}
@if (
    $profil === 'dga'
    && $mission->statut === 'en_attente_visa_dga'
)
    <button type="button"
            class="btn btn-success btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalTransmettreDAGRH{{ $mission->id }}"
            title="Transmettre au DAGRH">
        <i class="bi bi-send"></i>
    </button>
@endif

@if (
    $profil === 'dagrh'
    && $mission->statut === 'en_attente_visa_dagrh'
)
    <button type="button"
            class="btn btn-success btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalTransmettreMG{{ $mission->id }}"
            title="Transmettre aux Moyens généraux">
        <i class="bi bi-send"></i>
    </button>
@endif


{{-- DAGRH : retour au DGA --}}
@if (
    $profil === 'dagrh'
    && $mission->statut === 'en_attente_visa_dagrh'
)
    <button type="button"
            class="btn btn-sm btn-outline-danger"
            data-bs-toggle="modal"
            data-bs-target="#modalRetourDGA{{ $mission->id }}"
            title="Retourner au DGA">
        <i class="bi bi-arrow-counterclockwise"></i>
    </button>
@endif

{{-- Retourner à la DAGRH --}}
@if (
    in_array($profil, ['moyens_generaux', 'mg'], true)
    && $mission->statut === 'en_attente_mg'
)
    <button type="button"
            class="btn btn-warning btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalRetourDAGRH{{ $mission->id }}"
            title="Retourner à la DAGRH">
        <i class="bi bi-arrow-counterclockwise me-1"></i>
    </button>
@endif


        {{-- ================================================= --}}
        {{-- SUPPRESSION --}}
        {{-- ================================================= --}}

        @if (
            ($isAdmin || $profil === 'direction')
            && $mission->statut === 'brouillon'
        )
            <button type="button"
                    class="btn btn-sm btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#modalSuppression{{ $mission->id }}"
                    title="Supprimer">
                <i class="bi bi-trash"></i>
            </button>
        @endif

        
{{-- Direction : retour au Contrôle de gestion --}}
@if (
    $profil === 'direction'
    && $mission->type === 'nationale'
    && $mission->statut === 'en_attente_service_demandeur'
)
    <button type="button"
            class="btn btn-sm btn-outline-danger"
            data-bs-toggle="modal"
            data-bs-target="#modalRetourCG{{ $mission->id }}"
            title="Retourner au Contrôle de gestion">
        <i class="bi bi-arrow-counterclockwise"></i>
    </button>
@endif


@if (
    in_array($profil, ['moyens_generaux', 'mg'], true)
    && $mission->statut === 'en_attente_mg'
)
    {{-- Bouton d'affectation du véhicule --}}
    <button type="button"
            class="btn btn-info btn-sm"
            data-bs-toggle="modal"
            data-bs-target="#modalVehicule{{ $mission->id }}"
            title="Affecter un véhicule">
        <i class="bi bi-car-front me-1"></i>
    </button>

    {{-- Afficher uniquement si un véhicule est affecté --}}
    @if (!empty($mission->voiture_id))
        <button type="button"
                class="btn btn-success btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalTransmettreRH{{ $mission->id }}"
                title="Transmettre à la RH">
            <i class="bi bi-send me-1"></i>
        </button>
    @endif
@endif



        {{-- ================================================= --}}
        {{-- ANNULATION --}}
        {{-- ================================================= --}}

        @if (
            $isAdmin
            && !in_array(
                $mission->statut,
                ['payee', 'terminee', 'annulee'],
                true
            )
        )
            <button type="button"
                    class="btn btn-sm btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAnnulation{{ $mission->id }}"
                    title="Annuler la mission">
                <i class="bi bi-x-circle"></i>
            </button>
        @endif

    </div>
</td>

                                </tr>

{{-- MODALE RETOUR : MG → DAGRH --}}

@if (
    in_array($profil, ['moyens_generaux', 'mg'], true)
    && $mission->statut === 'en_attente_mg'
)
    <div class="modal fade"
         id="modalRetourDAGRH{{ $mission->id }}"
         tabindex="-1"
         aria-labelledby="titreRetourDAGRH{{ $mission->id }}"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST"
                      action="{{ route('missions.retourner-dagrh', $mission->id) }}">
                    @csrf

                    <div class="modal-header bg-warning">
                        <h5 class="modal-title"
                            id="titreRetourDAGRH{{ $mission->id }}">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>
                            Retourner la mission à la DAGRH
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-warning">
                            La mission sera renvoyée à la DAGRH pour correction
                            ou complément d'information.
                        </div>

                        <p>
                            <strong>Mission :</strong>
                            {{ $mission->objet ?? $mission->intitule ?? 'Mission n°'.$mission->id }}
                        </p>

                        <label for="commentaireRetourDAGRH{{ $mission->id }}"
                               class="form-label">
                            Motif du retour <span class="text-danger">*</span>
                        </label>

                        <textarea
                            name="commentaire"
                            id="commentaireRetourDAGRH{{ $mission->id }}"
                            class="form-control"
                            rows="4"
                            maxlength="2000"
                            placeholder="Précisez les corrections ou informations attendues..."
                            required>{{ old('commentaire') }}</textarea>

                        <small class="text-muted">
                            Le motif sera enregistré dans l'historique de la mission.
                        </small>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Annuler
                        </button>

                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-send me-1"></i>
                            Confirmer le retour
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endif

                                
{{-- MODALE : DGA → DAGRH --}}
@if (
    $profil === 'dga'
    && $mission->statut === 'en_attente_visa_dga'
)
    <div class="modal fade"
         id="modalTransmettreDAGRH{{ $mission->id }}"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST"
                      action="{{ route('missions.transmettre-dagrh', $mission->id) }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Transmission à la DAGRH
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Vous allez transmettre la mission
                            <strong>{{ $mission->numero }}</strong>
                            à la DAGRH pour examen et visa.
                        </p>

                        <label class="form-label">
                            Observation (facultative)
                        </label>

                        <textarea name="commentaire"
                                  class="form-control"
                                  rows="3"
                                  maxlength="2000"
                                  placeholder="Ajouter une observation éventuelle..."></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Annuler
                        </button>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send"></i>
                            Confirmer la transmission
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endif


{{-- ========================================================= --}}
{{-- MODALE : CG → DIRECTION POUR CONSULTATION --}}
{{-- ========================================================= --}}

@if (
    in_array($profil, ['cg', 'controle_gestion'], true)
    && $mission->type === 'nationale'
    && $mission->statut === 'en_attente_cg'
)
    <div class="modal fade"
         id="modalTransmettreDirection{{ $mission->id }}"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST"
                      action="{{ route('missions.transmettreDirectionConsultation', $mission->id) }}">

                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-send me-2"></i>
                            Transmettre à la Direction
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        Voulez-vous transmettre la mission
                        <strong>{{ $mission->numero }}</strong>
                        à la Direction pour consultation avant le visa du DGA ?

                        <div class="alert alert-info mt-3 mb-0">
                            Après confirmation, la mission passera à l'étape
                            « Direction — Consultation ».
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Annuler
                        </button>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>
                            Confirmer la transmission
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endif




{{-- MODALE : DGA → DIRECTION --}}
@if (
    $profil === 'dga'
    && $mission->statut === 'en_attente_visa_dga'
)
    <div class="modal fade"
         id="modalRetourDirectionConsultation{{ $mission->id }}"
         tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST"
                      action="{{ route('missions.retour-direction-consultation', $mission->id) }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            Retour à la Direction
                        </h5>
                        <button type="button" class="btn-close"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            La mission <strong>{{ $mission->numero }}</strong>
                            sera renvoyée à la Direction pour consultation.
                        </p>

                        <label class="form-label">
                            Motif du retour <span class="text-danger">*</span>
                        </label>
                        <textarea name="commentaire"
                                  class="form-control"
                                  rows="4"
                                  minlength="3"
                                  maxlength="2000"
                                  required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">
                            Confirmer le retour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

{{-- MODALE : DAGRH → DGA --}}
@if (
    $profil === 'dagrh'
    && $mission->statut === 'en_attente_visa_dagrh'
)
    <div class="modal fade"
         id="modalRetourDGA{{ $mission->id }}"
         tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST"
                      action="{{ route('missions.retour-dga', $mission->id) }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            Retour au DGA
                        </h5>
                        <button type="button" class="btn-close"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            La mission <strong>{{ $mission->numero }}</strong>
                            sera renvoyée au DGA pour réexamen.
                        </p>

                        <label class="form-label">
                            Motif du retour <span class="text-danger">*</span>
                        </label>
                        <textarea name="commentaire"
                                  class="form-control"
                                  rows="4"
                                  minlength="3"
                                  maxlength="2000"
                                  required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">
                            Confirmer le retour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif



{{-- MODALE : DIRECTION → CONTRÔLE DE GESTION --}}
@if (
    $profil === 'direction'
    && $mission->type === 'nationale'
    && $mission->statut === 'en_attente_service_demandeur'
)
    <div class="modal fade"
         id="modalRetourCG{{ $mission->id }}"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST"
                      action="{{ route('missions.retour-cg', $mission->id) }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>
                            Retour au Contrôle de gestion
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Voulez-vous retourner la mission
                            <strong>{{ $mission->numero }}</strong>
                            au Contrôle de gestion pour correction ?
                        </p>

                        <div class="mb-3">
                            <label class="form-label">
                                Motif du retour
                                <span class="text-danger">*</span>
                            </label>

                            <textarea name="commentaire"
                                      class="form-control"
                                      rows="4"
                                      minlength="3"
                                      maxlength="2000"
                                      required
                                      placeholder="Précisez les corrections attendues..."></textarea>
                        </div>

                        <div class="alert alert-warning mb-0">
                            La mission reviendra à l’étape
                            « Contrôle de gestion ».
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Annuler
                        </button>

                        <button type="submit" class="btn btn-danger">
                            Confirmer le retour
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endif



{{-- ========================================================= --}}
{{-- MODALE : AFFECTATION DU VÉHICULE PAR LES MOYENS GÉNÉRAUX --}}
{{-- ========================================================= --}}

@if (
    in_array($profil, ['moyens_generaux', 'mg'], true)
    && $mission->statut === 'en_attente_mg'
)
    @php
        $voituresDisponibles = \App\Models\Voiture::where('disponible', true)
            ->where('etat', '!=', 'hors_service')
            ->orderBy('immatriculation')
            ->get();
    @endphp

    <div class="modal fade"
         id="modalVehicule{{ $mission->id }}"
         tabindex="-1"
         aria-labelledby="titreVehicule{{ $mission->id }}"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST"
                      action="{{ route('missions.affecter-voiture', $mission->id) }}">

                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="titreVehicule{{ $mission->id }}">
                            <i class="bi bi-car-front me-2"></i>
                            Affectation du véhicule
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body">

                        <div class="alert alert-info">
                            <strong>Mission :</strong>
                            {{ $mission->numero }}

                            <p class="mb-0 mt-1">
                                Sélectionnez le véhicule à affecter à cette mission.
                            </p>
                        </div>

                        <div class="mb-3">
                            <label for="voiture_id{{ $mission->id }}"
                                   class="form-label">
                                Véhicule <span class="text-danger">*</span>
                            </label>

                            <select name="voiture_id"
                                    id="voiture_id{{ $mission->id }}"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Sélectionner un véhicule
                                </option>

                                @forelse ($voituresDisponibles as $voiture)
                                    <option value="{{ $voiture->id }}">
                                        {{ $voiture->immatriculation }}

                                        @if ($voiture->marque)
                                            — {{ $voiture->marque }}
                                        @endif

                                        @if ($voiture->modele)
                                            {{ $voiture->modele }}
                                        @endif
                                    </option>
                                @empty
                                    <option value="" disabled>
                                        Aucun véhicule disponible
                                    </option>
                                @endforelse

                            </select>

                            @if ($voituresDisponibles->isEmpty())
                                <div class="form-text text-danger">
                                    Aucun véhicule n'est actuellement disponible.
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="observationVehicule{{ $mission->id }}"
                                   class="form-label">
                                Observation
                                <span class="text-muted">(facultative)</span>
                            </label>

                            <textarea
                                name="observation_moyens_generaux"
                                id="observationVehicule{{ $mission->id }}"
                                class="form-control"
                                rows="3"
                                maxlength="2000"
                                placeholder="Préciser les informations relatives au véhicule..."></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Annuler
                        </button>

                        <button type="submit"
                                class="btn btn-info"
                                @disabled($voituresDisponibles->isEmpty())>
                            <i class="bi bi-check-circle me-1"></i>
                            Affecter le véhicule
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endif


{{-- ========================================================= --}}
{{-- MODALE : TRANSMISSION MOYENS GÉNÉRAUX → RH --}}
{{-- ========================================================= --}}

@if (
    in_array($profil, ['moyens_generaux', 'mg'], true)
    && $mission->statut === 'en_attente_mg'
)
    <div class="modal fade"
         id="modalTransmettreRH{{ $mission->id }}"
         tabindex="-1"
         aria-labelledby="titreTransmettreRH{{ $mission->id }}"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST"
                      action="{{ route('missions.transmettre-rh-apres-mg', $mission->id) }}">

                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="titreTransmettreRH{{ $mission->id }}">
                            <i class="bi bi-send me-2"></i>
                            Transmettre à la RH
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body">

                        <p>
                            Confirmez-vous la transmission de la mission
                            <strong>{{ $mission->numero }}</strong>
                            aux Ressources humaines pour la désignation du
                            chauffeur et la finalisation de l'ordre de mission ?
                        </p>

                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-info-circle me-1"></i>
                            Cette opération fera passer la mission à l'étape
                            « En attente des Ressources humaines ».
                        </div>

                        <div class="mb-3">
                            <label for="commentaireMG{{ $mission->id }}"
                                   class="form-label">
                                Commentaire / Observation
                                <span class="text-muted">(facultatif)</span>
                            </label>

                            <textarea
                                name="commentaire"
                                id="commentaireMG{{ $mission->id }}"
                                class="form-control"
                                rows="4"
                                maxlength="2000"
                                placeholder="Ajouter une observation à l'attention des Ressources humaines..."></textarea>

                            <div class="form-text">
                                Cette observation sera enregistrée dans l'historique
                                de la mission si le contrôleur traite ce champ.
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
                            <i class="bi bi-send me-1"></i>
                            Confirmer la transmission
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endif





{{-- ========================================================= --}}
{{-- MODALE : DIRECTION → DGA --}}
{{-- ========================================================= --}}

@if (
    $profil === 'direction'
    && $mission->statut === 'en_attente_service_demandeur'
)
    <div class="modal fade"
         id="modalTransmettreDGA{{ $mission->id }}"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST"
                      action="{{ route('missions.transmettreDGA', $mission->id) }}">

                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-forward me-2"></i>
                            Transmettre au DGA
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Après consultation, confirmez-vous la transmission
                            de la mission
                            <strong>{{ $mission->numero }}</strong>
                            au Directeur général adjoint pour visa ?
                        </p>

                        <div class="alert alert-warning mt-3 mb-3">
                            Cette opération fera passer la mission à l'étape
                            « En attente du visa DGA ».
                        </div>

                        <div class="mb-3">
                            <label for="commentaireDGA{{ $mission->id }}"
                                   class="form-label">
                                <i class="bi bi-chat-left-text me-1"></i>
                                Commentaire / Observation
                                <span class="text-muted">(facultatif)</span>
                            </label>

                            <textarea
                                name="commentaire"
                                id="commentaireDGA{{ $mission->id }}"
                                class="form-control"
                                rows="4"
                                maxlength="2000"
                                placeholder="Saisir une observation éventuelle à l'attention du DGA..."></textarea>

                            <div class="form-text">
                                Vous pouvez préciser une observation ou une
                                information utile à l'examen de la mission.
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Annuler
                        </button>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send me-1"></i>
                            Transmettre au DGA
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endif


@if (
    $profil === 'dagrh'
    && $mission->statut === 'en_attente_visa_dagrh'
)
    <div class="modal fade"
         id="modalTransmettreMG{{ $mission->id }}"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST"
                      action="{{ route('missions.transmettre-moyens-generaux', $mission->id) }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Transmission aux Moyens généraux
                        </h5>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Fermer"></button>
                    </div>

                    <div class="modal-body">
                        <p>
                            Vous allez transmettre la mission
                            <strong>{{ $mission->numero }}</strong>
                            aux Moyens généraux pour la prise en charge
                            des dispositions logistiques.
                        </p>

                        <label class="form-label">
                            Observation (facultative)
                        </label>

                        <textarea name="commentaire"
                                  class="form-control"
                                  rows="3"
                                  maxlength="2000"
                                  placeholder="Ajouter une observation éventuelle..."></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Annuler
                        </button>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send"></i>
                            Confirmer la transmission
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endif


                                {{-- ========================================================= --}}
                                {{-- MODAL TRANSMISSION RH --}}
                                {{-- ========================================================= --}}

                                @if ($mission->statut === 'brouillon')
                                    <div class="modal fade" id="modalTransmettreRH{{ $mission->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Transmettre à RH
                                                    </h5>

                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>

                                                </div>

                                                <div class="modal-body">

                                                    Voulez-vous transmettre la mission
                                                    <strong>
                                                        {{ $mission->numero }}
                                                    </strong>
                                                    à RH ?

                                                </div>

                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Annuler
                                                    </button>

                                                    <form method="POST"
                                                        action="{{ route('missions.transmettre-rh', $mission->id) }}">

                                                        @csrf

                                                        <button type="submit" class="btn btn-primary">
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

                                @if ($mission->statut === 'en_attente_rh')
                                    <div class="modal fade" id="modalValidationRH{{ $mission->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <form method="POST"
                                                    action="{{ route('missions.valider-rh', $mission->id) }}">

                                                    @csrf

                                                    <div class="modal-header">

                                                        <h5 class="modal-title">
                                                            Traitement RH
                                                        </h5>

                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>

                                                    </div>


                                                    <div class="modal-body">

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Chauffeur
                                                            </label>

                                                            <select name="chauffeur_id" class="form-select" required>

                                                                <option value="">
                                                                    Sélectionner le chauffeur
                                                                </option>

                                                                @foreach ($chauffeurs as $chauffeur)
                                                                    <option value="{{ $chauffeur->id }}"
                                                                        @selected($mission->chauffeur_id == $chauffeur->id)>

                                                                        {{ $chauffeur->prenom }}
                                                                        {{ $chauffeur->nom }}

                                                                        @if ($chauffeur->matricule)
                                                                            — {{ $chauffeur->matricule }}
                                                                        @endif

                                                                    </option>
                                                                @endforeach

                                                            </select>

                                                        </div>


                                                        <div class="alert alert-info mb-0">

                                                            <i class="bi bi-info-circle me-1"></i>

                                                            Le chauffeur sera automatiquement
                                                            ajouté aux participants et ses
                                                            frais et indemnités seront calculés.

                                                        </div>

                                                    </div>


                                                    <div class="modal-footer">

                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Annuler
                                                        </button>

                                                        <button type="submit" class="btn btn-success">
                                                            <i class="bi bi-check-circle me-1"></i>
                                                            Valider RH
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

                                @if ($mission->statut === 'en_attente_rh_apres_mg')
                                    {{-- Envoyer Budget --}}

                                    <div class="modal fade" id="modalEnvoyerBudget{{ $mission->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Envoyer au Budget
                                                    </h5>

                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>

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

                                                        {{ number_format($mission->montant_total, 0, ',', ' ') }}
                                                        FCFA

                                                    </div>

                                                </div>


                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Annuler
                                                    </button>

                                                    <form method="POST"
                                                        action="{{ route('missions.envoyer-budget', $mission->id) }}">

                                                        @csrf

                                                        <button type="submit" class="btn btn-success">
                                                            Envoyer au Budget
                                                        </button>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- Retour MG --}}

                                    <div class="modal fade" id="modalRetourMG{{ $mission->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <form method="POST"
                                                    action="{{ route('missions.retour-moyens-generaux', $mission->id) }}">

                                                    @csrf

                                                    <div class="modal-header">

                                                        <h5 class="modal-title text-danger">
                                                            Retour aux Moyens Généraux
                                                        </h5>

                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>

                                                    </div>


                                                    <div class="modal-body">

                                                        <div class="mb-3">

                                                            <label class="form-label">

                                                                Motif du retour
                                                                <span class="text-danger">*</span>

                                                            </label>

                                                            <textarea name="commentaire" class="form-control" rows="4" maxlength="2000" required></textarea>

                                                        </div>

                                                    </div>


                                                    <div class="modal-footer">

                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Annuler
                                                        </button>

                                                        <button type="submit" class="btn btn-danger">
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

                                @if ($mission->statut === 'en_attente_budget')
                                    {{-- Validation Budget --}}

                                    <div class="modal fade" id="modalValidationBudget{{ $mission->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Validation du Budget
                                                    </h5>

                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>

                                                </div>


                                                <div class="modal-body">

                                                    <p>
                                                        Valider le budget de cette mission ?
                                                    </p>

                                                    <div class="alert alert-info">

                                                        <strong>
                                                            Montant :
                                                        </strong>

                                                        {{ number_format($mission->montant_total, 0, ',', ' ') }}
                                                        FCFA

                                                    </div>

                                                </div>


                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Annuler
                                                    </button>

                                                    <form method="POST"
                                                        action="{{ route('missions.valider-budget', $mission->id) }}">

                                                        @csrf

                                                        <button type="submit" class="btn btn-success">
                                                            Valider le Budget
                                                        </button>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- Retour RH --}}

                                    <div class="modal fade" id="modalRetourRHBudget{{ $mission->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <form method="POST"
                                                    action="{{ route('missions.retour-rh-budget', $mission->id) }}">

                                                    @csrf

                                                    <div class="modal-header">

                                                        <h5 class="modal-title text-danger">
                                                            Retour à RH
                                                        </h5>

                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>

                                                    </div>


                                                    <div class="modal-body">

                                                        <div class="mb-3">

                                                            <label class="form-label">

                                                                Motif du retour
                                                                <span class="text-danger">*</span>

                                                            </label>

                                                            <textarea name="commentaire" class="form-control" rows="4" maxlength="2000" required></textarea>

                                                        </div>

                                                    </div>


                                                    <div class="modal-footer">

                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Annuler
                                                        </button>

                                                        <button type="submit" class="btn btn-danger">
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

                                @if ($mission->statut === 'en_attente_comptabilite')
                                    {{-- Paiement --}}

                                    <div class="modal fade" id="modalPaiement{{ $mission->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <form method="POST" action="{{ route('missions.payer', $mission->id) }}">

                                                    @csrf

                                                    <div class="modal-header">

                                                        <h5 class="modal-title">
                                                            Enregistrer le paiement
                                                        </h5>

                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>

                                                    </div>


                                                    <div class="modal-body">

                                                        <div class="alert alert-info">

                                                            Montant total :

                                                            <strong>
                                                                {{ number_format($mission->montant_total, 0, ',', ' ') }}
                                                                FCFA
                                                            </strong>

                                                        </div>


                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Montant payé
                                                            </label>

                                                            <input type="number" name="montant_paye" class="form-control"
                                                                value="{{ $mission->montant_total }}" min="0"
                                                                step="0.01" required>

                                                        </div>


                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Date de paiement
                                                            </label>

                                                            <input type="date" name="date_paiement" class="form-control"
                                                                value="{{ now()->format('Y-m-d') }}" required>

                                                        </div>


                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Référence paiement
                                                            </label>

                                                            <input type="text" name="reference_paiement"
                                                                class="form-control" required>

                                                        </div>

                                                    </div>


                                                    <div class="modal-footer">

                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Annuler
                                                        </button>

                                                        <button type="submit" class="btn btn-success">
                                                            Enregistrer le paiement
                                                        </button>

                                                    </div>

                                                </form>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- Retour Budget --}}

                                    <div class="modal fade" id="modalRetourBudget{{ $mission->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <form method="POST"
                                                    action="{{ route('missions.show', $mission->id) }}">

                                                    @csrf

                                                    <div class="modal-header">

                                                        <h5 class="modal-title text-danger">
                                                            Retour au Budget
                                                        </h5>

                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>

                                                    </div>


                                                    <div class="modal-body">

                                                        <div class="mb-3">

                                                            <label class="form-label">

                                                                Motif du retour
                                                                <span class="text-danger">*</span>

                                                            </label>

                                                            <textarea name="commentaire" class="form-control" rows="4" maxlength="2000" required></textarea>

                                                        </div>

                                                    </div>


                                                    <div class="modal-footer">

                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Annuler
                                                        </button>

                                                        <button type="submit" class="btn btn-danger">
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

                                @if ($mission->statut === 'payee')
                                    <div class="modal fade" id="modalTerminer{{ $mission->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title">
                                                        Terminer la mission
                                                    </h5>

                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>

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

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Annuler
                                                    </button>

                                                    <form method="POST"
                                                        action="{{ route('missions.terminer', $mission->id) }}">

                                                        @csrf

                                                        <button type="submit" class="btn btn-success">
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

                                @if ($mission->statut === 'brouillon')
                                    <div class="modal fade" id="modalSuppression{{ $mission->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title text-danger">
                                                        Supprimer la mission
                                                    </h5>

                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>

                                                </div>


                                                <div class="modal-body">

                                                    Voulez-vous réellement supprimer
                                                    cette mission ?

                                                </div>


                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Annuler
                                                    </button>

                                                    <form method="POST"
                                                        action="{{ route('missions.destroy', $mission->id) }}">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-danger">
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

                                @if (!in_array($mission->statut, ['payee', 'terminee', 'annulee']))
                                    <div class="modal fade" id="modalAnnulation{{ $mission->id }}" tabindex="-1">

                                        <div class="modal-dialog">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title text-danger">
                                                        Annuler la mission
                                                    </h5>

                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>

                                                </div>


                                                <div class="modal-body">

                                                    Voulez-vous annuler la mission

                                                    <strong>
                                                        {{ $mission->numero }}
                                                    </strong>

                                                    ?

                                                </div>


                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Non
                                                    </button>

                                                    <form method="POST"
                                                        action="{{ route('missions.annuler', $mission->id) }}">

                                                        @csrf

                                                        <button type="submit" class="btn btn-danger">
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

                                        <td colspan="11" class="text-center py-5">

                                            <div class="text-muted">

                                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>

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

                    @if ($missions->hasPages())
                        <div class="card-footer bg-white">

                            {{ $missions->withQueryString()->links() }}

                        </div>
                    @endif

                </div>

            </div>

        @endsection
