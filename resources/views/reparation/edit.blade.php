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

    <form method="post" action="{{ route('reparations.update', $reparation->id) }}">

        <!-- Matériel -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="materiels_designation" :value="__('Matériel')" />
            <select name="materiels_designation" class="block mt-1 w-full">
                @foreach ($materiels as $materiel)
                    @if ($materiel->designation === $reparation->materiels_designation)
                        <option value="{{ $materiel->designation }}">{{ $materiel->designation }}</option>
                    @endif
                @endforeach
                @foreach ($materiels as $materiel)
                    <option value="{{ $materiel->designation }}">{{ $materiel->designation }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('materiels_designation')" class="mt-2" />
        </div>

        <!-- Quantité -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="quantite" :value="__('Quantité')" />
            <x-text-input id="quantite" class="block mt-1 w-full" type="text" name="quantite"
                value="{{ $reparation->quantite }}" />
            <x-input-error :messages="$errors->get('quantite')" class="mt-2" />
        </div>

        <!-- Commentaire -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="commentaire" :value="__('Commentaire')" />
            <x-text-input id="commentaire" class="block mt-1 w-full" type="text" name="commentaire"
                value="{{ $reparation->commentaire }}" />
            <x-input-error :messages="$errors->get('commentaire')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ml-4">
                {{ __('Modifier') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
