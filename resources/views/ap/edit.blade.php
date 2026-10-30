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

    <form method="post" action="{{ route('aps.update', $ap->id) }}">

        <!-- Code -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="code_ap" :value="__('Code')" />
            <x-text-input id="code_ap" class="block mt-1 w-full" type="text" name="code_ap"
                value="{{ $ap->code_ap }}" />
            <x-input-error :messages="$errors->get('code_ap')" class="mt-2" />
        </div>

        <!-- Désignation -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="designation_ap" :value="__('Désignation')" />
            <x-text-input id="designation_ap" class="block mt-1 w-full" type="text" name="designation_ap"
                value="{{ $ap->designation_ap }}" />
            <x-input-error :messages="$errors->get('designation_ap')" class="mt-2" />
        </div>

        <!-- Prix Rembousement -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="prix_rembousement_ap" :value="__('Prix Rembousement')" />
            <x-text-input id="prix_rembousement_ap" class="block mt-1 w-full" type="text" name="prix_rembousement_ap"
                value="{{ $ap->prix_rembousement_ap }}" />
            <x-input-error :messages="$errors->get('prix_rembousement_ap')" class="mt-2" />
        </div>

        <!-- Prix Publique -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="prix_public_ap" :value="__('Prix Publique')" />
            <x-text-input id="prix_public_ap" class="block mt-1 w-full" type="text" name="prix_public_ap"
                value="{{ $ap->prix_public_ap }}" />
            <x-input-error :messages="$errors->get('prix_public_ap')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ml-4">
                {{ __('Modifier') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
