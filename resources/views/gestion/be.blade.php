<!-- index.blade.php -->

<x-app-layout>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <link rel="canonical"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css">

    <div>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Rédiger Un Bordereau') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Renseignez le formulaire rélatif au borderau.') }}
            </p>
        </header>

        <form action="{{ route('generateBE-pdf') }}" method="GET">
    @csrf

    <!-- Numéro Brodereau -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="numero_bordereau" :value="__('Numéro Bordereau ')" />
        <x-text-input id="numero_bordereau" class="form-control" type="text" name="numero_bordereau" :value="old('numero_bordereau')" required
            autofocus autocomplete="numero_bordereau" />
        <x-input-error :messages="$errors->get('numero_bordereau')" class="mt-2" />

            <x-input-label class="input-group-text" for="type_bordereau" :value="__('Tye De Bordereau ')" />
            <select name="type_bordereau" class="form-control">
                <option value="D'ENVOI">ENVOI</option>
                <option value="DE REPARATION">REPARATION</option>
            </select>
        <x-input-error :messages="$errors->get('type_bordereau')" class="mt-2" />

            <x-input-label class="input-group-text" for="date" :value="__('Date')" />
            <x-text-input id="date" class="form-control" type="date" name="date" :value="old('date')" required
            autofocus autocomplete="date" />
        <x-input-error :messages="$errors->get('date')" class="mt-2" />
    </div>

    <!-- Matériel 1 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="materiels_designation" :value="__('Matériel')" />
        <select name="materiels_designation" class="form-control">
            <option value=""></option>
            @foreach ($materiels as $materiel)
                <option value="{{ $materiel->designation }}">{{ $materiel->designation }}</option> @endforeach
        </select>
        <x-input-error :messages="$errors->get('materiels_designation1')" class="mt-2" />

    <x-input-label class="input-group-text" for="quantite" :value="__('Quantité')" />
    <x-text-input id="quantite" class="form-control" type="text" name="quantite" :value="old('quantite')" required autofocus
        autocomplete="quantite" />
    <x-input-error :messages="$errors->get('quantite')" class="mt-2" />
    </div>

    <!-- Matériel 2 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="materiels_designation" :value="__('Matériel')" />
        <select name="materiels_designation2" class="form-control">
            <option value=""></option>
            @foreach ($materiels as $materiel)
                <option value="{{ $materiel->designation }}">{{ $materiel->designation }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('materiels_designation2')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite2" :value="__('Quantité')" />
        <x-text-input id="quantite2" class="form-control" type="text" name="quantite2" :value="old('quantite')" autofocus
            autocomplete="quantite2" />
        <x-input-error :messages="$errors->get('quantite2')" class="mt-2" />
    </div>

    <!-- Matériel 3 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="materiels_designation" :value="__('Matériel')" />
        <select name="materiels_designation3" class="form-control">
            <option value=""></option>
            @foreach ($materiels as $materiel)
                <option value="{{ $materiel->designation }}">{{ $materiel->designation }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('materiels_designation3')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite3" :value="__('Quantité')" />
        <x-text-input id="quantite3" class="form-control" type="text" name="quantite3" :value="old('quantite3')" autofocus
            autocomplete="quantite3" />
        <x-input-error :messages="$errors->get('quantite3')" class="mt-2" />
    </div>

    <!-- Matériel 4 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="materiels_designation4" :value="__('Matériel')" />
        <select name="materiels_designation4" class="form-control">
            <option value=""></option>
            @foreach ($materiels as $materiel)
                <option value="{{ $materiel->designation }}">{{ $materiel->designation }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('materiels_designation4')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite" :value="__('Quantité')" />
        <x-text-input id="quantite4" class="form-control" type="text" name="quantite4" :value="old('quantite4')" autofocus
            autocomplete="quantite4" />
        <x-input-error :messages="$errors->get('quantite4')" class="mt-2" />
    </div>

    <!-- Matériel 5 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="materiels_designation" :value="__('Matériel')" />
        <select name="materiels_designation5" class="form-control">
            <option value=""></option>
            @foreach ($materiels as $materiel)
                <option value="{{ $materiel->designation }}">{{ $materiel->designation }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('materiels_designation5')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite" :value="__('Quantité')" />
        <x-text-input id="quantite5" class="form-control" type="text" name="quantite5" :value="old('quantite5')" autofocus
            autocomplete="quantite5" />
        <x-input-error :messages="$errors->get('quantite5')" class="mt-2" />
    </div>

    <!-- Commentaire -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="commentaire" :value="__('Commentaire')" />
        <textarea class="form-control" id="commentaire" aria-label="With textarea" name="commentaire"
            :value="old('commentaire')" required autofocus autocomplete="commentaire"></textarea>
        <x-input-error :messages="$errors->get('commentaire')" class="mt-2" />
    </div>

    <!-- Destinateur -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="destination" :value="__('Destinateur')" />
        <x-text-input id="destination" class="form-control" type="text" name="destination" :value="old('destination')"
            autofocus autocomplete="destination" />
        <x-input-error :messages="$errors->get('destination')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <button type="submit" class="btn btn-primary mt-2">Exporter En PDF</button>
    </div>
    <div class="flex items-center justify-end mt-4">

    </div>
    </form>
    </div>
</x-app-layout>
