<header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Ajouter Des Accords Préalables') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Enregistrer des nouveaux accords préalable dans votre base.') }}
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

<form method="post" action="{{ route('aps.store') }}">
    @csrf

    <!-- Géstion Des Entrées -->

    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="code_ap" :value="__('Code')" />
        <x-text-input id="code_ap" class="form-control" type="text" name="code_ap" :value="old('code_ap')" required
            autofocus autocomplete="code_ap" />
        <x-input-error :messages="$errors->get('code_ap')" class="mt-2" />

        <x-input-label class="input-group-text" for="designation_ap" :value="__('Désignation')" />
        <x-text-input id="designation_ap" class="form-control" type="text" name="designation_ap" :value="old('designation_ap')"
            required autofocus autocomplete="designation_ap" />
        <x-input-error :messages="$errors->get('designation_ap')" class="mt-2" />
    </div>

    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="prix_rembousement_ap" :value="__('Prix Remboursement')" />
        <x-text-input id="prix_rembousement_ap" class="form-control" type="text" name="prix_rembousement_ap" :value="old('prix_rembousement_ap')" required
            autofocus autocomplete="prix_rembousement_ap" />
        <x-input-error :messages="$errors->get('prix_rembousement_ap')" class="mt-2" />

        <x-input-label class="input-group-text" for="prix_public_ap" :value="__('Prix Public')" />
        <x-text-input id="prix_public_ap" class="form-control" type="text" name="prix_public_ap" :value="old('prix_public_ap')"
            required autofocus autocomplete="prix_public_ap" />
        <x-input-error :messages="$errors->get('prix_public_ap')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('Enregistrer') }}
        </x-primary-button>
    </div>
</form>
