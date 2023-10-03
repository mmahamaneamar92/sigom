<x-guest-layout>

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

    <form method="post" action="{{ route('assures.update', $assure->id) }}">

        <!-- Numéro Assuré -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="numero_assurer" :value="__('Numéro AMO')" />
            <x-text-input id="numero_assurer" class="block mt-1 w-full" type="text" name="numero_assurer"
                value="{{ $assure->numero_assurer }}" />
            <x-input-error :messages="$errors->get('numero_assurer')" class="mt-2" />
        </div>

        <!-- Prénom -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="prenom" :value="__('Prénom')" />
            <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom"
                value="{{ $assure->prenom }}" />
            <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
        </div>

        <!-- Nom -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="nom" :value="__('Nom')" />
            <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom"
                value="{{ $assure->nom }}" />
            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
        </div>

        <!-- Tel -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="tel" :value="__('Téléphone')" />
            <x-text-input id="tel" class="block mt-1 w-full" type="text" name="tel"
                value="{{ $assure->tel }}" />
            <x-input-error :messages="$errors->get('tel')" class="mt-2" />
        </div>

        <!-- OGD -->
        <div>
            <x-input-label for="ogd" :value="__('OGD')" />
            <select name="ogd" class="block mt-1 w-full">
                <option value="{{ $assure->ogd }}">{{ $assure->ogd }}</option>
                <option value="INPS">INPS</option>
            <option value="CMSS">CMSS</option>
            </select>
            <x-input-error :messages="$errors->get('ogd')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ml-4">
                {{ __('Modifier') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
