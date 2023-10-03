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

    <form method="post" action="{{ route('hopitals.update', $hopital->id) }}">

        <!-- Code Hopital -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="code_hopital" :value="__('Code')" />
            <x-text-input id="code_hopital" class="block mt-1 w-full" type="text" name="code_hopital"
                value="{{ $hopital->code_hopital }}" />
            <x-input-error :messages="$errors->get('code_hopital')" class="mt-2" />
        </div>

        <!-- Nom Hopital -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="nom_hopital" :value="__('Nom')" />
            <x-text-input id="nom_hopital" class="block mt-1 w-full" type="text" name="nom_hopital"
                value="{{ $hopital->nom_hopital }}" />
            <x-input-error :messages="$errors->get('nom_hopital')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ml-4">
                {{ __('Modifier') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
