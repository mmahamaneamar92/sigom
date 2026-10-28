
@extends('layouts.ap')

@section('title', 'Modifier la demande de mission')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Modifier la demande de mission</h4>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('mission-initiations.update', $initiation->id) }}"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="objet" class="form-label">
                        Objet de la mission
                    </label>

                    <input
                        type="text"
                        id="objet"
                        name="objet"
                        class="form-control"
                        value="{{ old('objet', $initiation->objet) }}"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">TDR actuel</label>

                    @if ($initiation->tdr_path)
                        <div>
                            <a
                                href="{{ route('mission-initiations.tdr', $initiation->id) }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                Télécharger le TDR actuel
                            </a>
                        </div>
                    @else
                        <p class="text-muted">
                            Aucun fichier TDR enregistré.
                        </p>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="tdr" class="form-label">
                        Remplacer le TDR (facultatif)
                    </label>

                    <input
                        type="file"
                        id="tdr"
                        name="tdr"
                        class="form-control"
                        accept=".pdf,.doc,.docx"
                    >

                    <small class="text-muted">
                        PDF, DOC ou DOCX, 10 Mo maximum.
                        Laisse ce champ vide pour conserver le TDR actuel.
                    </small>
                </div>

                <button type="submit" class="btn btn-success">
                    Enregistrer les modifications
                </button>

                <a
                    href="{{ route('mission-initiations.index') }}"
                    class="btn btn-secondary"
                >
                    Annuler
                </a>
            </form>
        </div>
    </div>
</div>
@endsection