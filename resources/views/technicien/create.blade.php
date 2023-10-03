<header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Ajouter Des techniciens') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Enregistrer des nouveaux techniciens dans votre base.') }}
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

<form method="post" action="{{ route('techniciens.store') }}">
    @csrf

    <!-- Géstion Des Entrées -->

    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="code_technicien" :value="__('Code')" />
        <x-text-input id="code_technicien" class="form-control" type="text" name="code_technicien" :value="old('code_technicien')"
            required autofocus autocomplete="code_technicien" />
        <x-input-error :messages="$errors->get('code_technicien')" class="mt-2" />

        <x-input-label class="input-group-text" for="prenom_technicien" :value="__('Prénom')" />
        <x-text-input id="prenom_technicien" class="form-control" type="text" name="prenom_technicien"
            :value="old('prenom_technicien')" required autofocus autocomplete="prenom_technicien" />
        <x-input-error :messages="$errors->get('prenom_technicien')" class="mt-2" />

        <x-input-label class="input-group-text" for="nom_technicien" :value="__('Nom')" />
        <x-text-input id="nom_technicien" class="form-control" type="text" name="nom_technicien"
            :value="old('nom_technicien')" required autofocus autocomplete="nom_technicien" />
        <x-input-error :messages="$errors->get('prenom_technicien')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('Enregistrer') }}
        </x-primary-button>
    </div>
</form>
