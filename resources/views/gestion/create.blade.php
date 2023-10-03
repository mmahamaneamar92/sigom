<header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Ajouter Des Matériels') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Enregistrer des nouveaux matériaux dans votre stocks.') }}
    </p>
</header>
<div class="flex items-center justify-end mt-4">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div><br />
    @endif
</div>

<form method="post" action="{{ route('gestions.store') }}">
    @csrf

    <!-- Entrer et Nom -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="type" :value="__('Type')" />
        <select name="type" class="form-control">
            <option value="Entrer">ENTRER</option>
            <option value="Sortie">SORTIE</option>
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />

        <x-input-label class="input-group-text" for="users_email" :value="__('Nom')" />
        <select name="users_email" class="form-control">
            <option value={{ Auth::user()->email }}>{{ Auth::user()->name }}</option>
        </select>
        <x-input-error :messages="$errors->get('users_email')" class="mt-2" />
    </div>

    <!-- Matériel -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="materiels_designation" :value="__('Matériel')" />
        <select name="materiels_designation" class="form-control">
            @foreach ($materiels as $materiel)
                <option value="{{ $materiel->designation }}">{{ $materiel->designation }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('materiels_designation')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite" :value="__('Quantité')" />
        <x-text-input id="quantite" class="form-control" type="text" name="quantite" :value="old('quantite')" required
            autofocus autocomplete="quantite" />
        <x-input-error :messages="$errors->get('quantite')" class="mt-2" />
    </div>

    <!-- Commentaire -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="commentaire" :value="__('Commentaire')" />
        <textarea class="form-control" id="commentaire" aria-label="With textarea" name="commentaire"
            :value="old('commentaire')" required autofocus autocomplete="commentaire"></textarea>
        <x-input-error :messages="$errors->get('commentaire')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('Enregistrer') }}
        </x-primary-button>
    </div>
</form>
