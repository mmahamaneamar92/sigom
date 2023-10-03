<header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Ajouter Des Préscripteurs') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Enregistrer des nouveaux préscripteurs dans votre stocks.') }}
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

<form method="post" action="{{ route('prescripteurs.store') }}">
    @csrf

    <!-- Géstion Des Entrées -->

    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="code_prescripteur" :value="__('Code')" />
        <x-text-input id="code_prescripteur" class="form-control" type="text" name="code_prescripteur" :value="old('code_prescripteur')" required
            autofocus autocomplete="code_prescripteur" />
        <x-input-error :messages="$errors->get('code_prescripteur')" class="mt-2" />

        <x-input-label class="input-group-text" for="prenom_prescripteur" :value="__('Prénom')" />
        <x-text-input id="prenom_prescripteur" class="form-control" type="text" name="prenom_prescripteur" :value="old('prenom_prescripteur')"
            required autofocus autocomplete="prenom_prescripteur" />
        <x-input-error :messages="$errors->get('prenom_prescripteur')" class="mt-2" />

        <x-input-label class="input-group-text" for="nom_prescripteur" :value="__('Nom')" />
        <x-text-input id="nom_prescripteur" class="form-control" type="text" name="nom_prescripteur" :value="old('nom_prescripteur')"
            required autofocus autocomplete="nom_prescripteur" />
        <x-input-error :messages="$errors->get('nom_prescripteur')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('Enregistrer') }}
        </x-primary-button>
    </div>
</form>
