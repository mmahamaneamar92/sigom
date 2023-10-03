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

    <form method="post" action="{{ route('types.update', $profil->id) }}">

        <!-- Nom Profil -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="nom_profil" :value="__('Nom Profil')" />
            <x-text-input id="nom_profil" class="block mt-1 w-full" type="text" name="nom_profil"
                value="{{ $profil->nom_profil }}" />
            <x-input-error :messages="$errors->get('nom_profil')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ml-4">
                {{ __('Modifier') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
