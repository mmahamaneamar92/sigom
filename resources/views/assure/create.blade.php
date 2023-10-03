<header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Ajouter Des Assurés') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Enregistrer des nouveaux assurés dans votre base.') }}
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

<form method="post" action="{{ route('assures.store') }}">
    @csrf

    <!-- Géstion Des Entrées -->

    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="numero_assurer" :value="__('Numero Assurer')" />
        <x-text-input id="numero_assurer" class="form-control" type="text" name="numero_assurer" :value="old('numero_assurer')"
            required autofocus autocomplete="numero_assurer" />
        <x-input-error :messages="$errors->get('numero_assurer')" class="mt-2" />

        <x-input-label class="input-group-text" for="prenom" :value="__('Prénom')" />
        <x-text-input id="prenom" class="form-control" type="text" name="prenom" :value="old('prenom')" required
            autofocus autocomplete="prenom" />
        <x-input-error :messages="$errors->get('prenom')" class="mt-2" />

        <x-input-label class="input-group-text" for="nom" :value="__('Nom')" />
        <x-text-input id="nom" class="form-control" type="text" name="nom" :value="old('nom')" required
            autofocus autocomplete="nom" />
        <x-input-error :messages="$errors->get('nom')" class="mt-2" />
    </div>

    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="tel" :value="__('Téléphone')" />
        <x-text-input id="tel" class="form-control" type="text" name="tel" :value="old('tel')" required
            autofocus autocomplete="tel" />
        <x-input-error :messages="$errors->get('tel')" class="mt-2" />

        <x-input-label class="input-group-text" for="ogd" :value="__('OGD')" />
        <select name="ogd" class="form-control">
            <option value="INPS">INPS</option>
            <option value="CMSS">CMSS</option>
        </select>
        <x-input-error :messages="$errors->get('ogd')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('Enregistrer') }}
        </x-primary-button>
    </div>
</form>
