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

<form method="post" action="{{ route('materiels.store') }}">
    @csrf

    <!-- Géstion Des Entrées -->

    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="designation" :value="__('Désignation')" />
        <x-text-input id="designation" class="form-control" type="text" name="designation" :value="old('designation')" required
            autofocus autocomplete="designation" />
        <x-input-error :messages="$errors->get('designation')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('Enregistrer') }}
        </x-primary-button>
    </div>
</form>
