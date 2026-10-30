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

    <form method="post" action="{{ route('techniciens.update', $technicien->id) }}">

        <!-- Code Technicien -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="code_technicien" :value="__('Code')" />
            <x-text-input id="code_technicien" class="block mt-1 w-full" type="text" name="code_technicien"
                value="{{ $technicien->code_technicien }}" />
            <x-input-error :messages="$errors->get('code_technicien')" class="mt-2" />
        </div>

        <!-- Prénom Technicien -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="prenom_technicien" :value="__('Prénom')" />
            <x-text-input id="prenom_technicien" class="block mt-1 w-full" type="text" name="prenom_technicien"
                value="{{ $technicien->prenom_technicien }}" />
            <x-input-error :messages="$errors->get('prenom_technicien')" class="mt-2" />
        </div>

        <!-- Nom Technicien -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="nom_technicien" :value="__('Nom')" />
            <x-text-input id="nom_technicien" class="block mt-1 w-full" type="text" name="nom_technicien"
                value="{{ $technicien->nom_technicien }}" />
            <x-input-error :messages="$errors->get('nom_technicien')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ml-4">
                {{ __('Modifier') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
