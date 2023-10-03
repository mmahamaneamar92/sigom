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

    <form method="post" action="{{ route('pharmacies.update', $pharmacie->id) }}">

        <!-- Code Pharmacie -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="code_pharmacie" :value="__('Code')" />
            <x-text-input id="code_pharmacie" class="block mt-1 w-full" type="text" name="code_pharmacie"
                value="{{ $pharmacie->code_pharmacie }}" />
            <x-input-error :messages="$errors->get('code_pharmacie')" class="mt-2" />
        </div>

        <!-- Nom Pharmacie -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="nom_pharmacie" :value="__('Nom')" />
            <x-text-input id="nom_pharmacie" class="block mt-1 w-full" type="text" name="nom_pharmacie"
                value="{{ $pharmacie->nom_pharmacie }}" />
            <x-input-error :messages="$errors->get('nom_pharmacie')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ml-4">
                {{ __('Modifier') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
