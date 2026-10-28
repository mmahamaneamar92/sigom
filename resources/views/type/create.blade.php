<header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Ajouter Des Profils') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Enregistrer des nouvelles profils dans votre base.') }}
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

<form method="post" action="{{ route('types.store') }}">
    @csrf

    <!-- Géstion Des Pofils -->

    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="nom_profil" :value="__('Nom Profil')" />
        <x-text-input id="nom_profil" class="form-control" type="text" name="nom_profil" :value="old('nom_profil')"
            required autofocus autocomplete="nom_profil" />
        <x-input-error :messages="$errors->get('nom_profil')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('Enregistrer') }}
        </x-primary-button>
    </div>
</form>
