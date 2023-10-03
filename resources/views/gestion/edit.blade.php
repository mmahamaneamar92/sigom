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

    <form method="post" action="{{ route('gestions.update', $usermateriel->id) }}">

        <!-- Type -->
        <div>
            <x-input-label for="type" :value="__('Type')" />
            <select name="type" class="block mt-1 w-full">
                <option value="{{ $usermateriel->type }}">{{ $usermateriel->type }}</option>
                <option value="Entrer">ENTRER</option>
                <option value="Sortie">SORTIE</option>
            </select>
            <x-input-error :messages="$errors->get('type')" class="mt-2" />
        </div>

        <!-- Utilisateur -->
        <div>
            <x-input-label for="users_email" :value="__('Nom')" />

            <select name="users_email" class="block mt-1 w-full">
                @foreach ($users as $user)
                    @if ($user->email === $usermateriel->users_email)
                        <option value="{{ $user->email }}">{{ $user->name }}</option>
                    @endif
                @endforeach

                @foreach ($users as $user)
                    <option value="{{ $user->email }}">{{ $user->name }}</option>
                @endforeach
            </select>

            <x-input-error :messages="$errors->get('users_email')" class="mt-2" />
        </div>

        <!-- Matériel -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="materiels_designation" :value="__('Matériel')" />
            <select name="materiels_designation" class="block mt-1 w-full">
                @foreach ($materiels as $materiel)
                    @if ($materiel->designation === $usermateriel->materiels_designation)
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
                value="{{ $usermateriel->quantite }}" />
            <x-input-error :messages="$errors->get('quantite')" class="mt-2" />
        </div>

        <!-- Commentaire -->
        <div>
            @csrf
            @method('PATCH')
            <x-input-label for="commentaire" :value="__('Commentaire')" />
            <x-text-input id="commentaire" class="block mt-1 w-full" type="text" name="commentaire"
                value="{{ $usermateriel->commentaire }}" />
            <x-input-error :messages="$errors->get('commentaire')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ml-4">
                {{ __('Modifier') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
