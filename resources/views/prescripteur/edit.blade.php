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

    <form method="post" action="{{ route('prescripteurs.update', $prescripteur->id) }}">

        <!-- Code Prescripteur -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="code_prescripteur" :value="__('Code')" />
            <x-text-input id="code_prescripteur" class="block mt-1 w-full" type="text" name="code_prescripteur"
                value="{{ $prescripteur->code_prescripteur }}" />
            <x-input-error :messages="$errors->get('code_prescripteur')" class="mt-2" />
        </div>

        <!-- Prénom Prescripteur -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="prenom_prescripteur" :value="__('Prénom')" />
            <x-text-input id="prenom_prescripteur" class="block mt-1 w-full" type="text" name="prenom_prescripteur"
                value="{{ $prescripteur->prenom_prescripteur }}" />
            <x-input-error :messages="$errors->get('prenom_prescripteur')" class="mt-2" />
        </div>

        <!-- Nom Prescripteur -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="nom_prescripteur" :value="__('Nom')" />
            <x-text-input id="nom_prescripteur" class="block mt-1 w-full" type="text" name="nom_prescripteur"
                value="{{ $prescripteur->nom_prescripteur }}" />
            <x-input-error :messages="$errors->get('nom_prescripteur')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ml-4">
                {{ __('Modifier') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
