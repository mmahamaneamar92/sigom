<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('utilisateurs.update', $user->id) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                value="{{ $user->name }}" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="profil" :value="__('Profil')" />
            <select name="profil" class="block mt-1 w-full">
                @foreach ($profils as $profil)
                    @if ($profil->nom_profil === $user->profil)
                        <option value="{{ $profil->nom_profil }}">{{ $profil->nom_profil }}</option>
                    @endif
                @endforeach

                @foreach ($profils as $profil)
                    <option value="{{ $profil->nom_profil }}">{{ $profil->nom_profil }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('profil')" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                value="$user->password" />
            <x-input-error class="mt-2" :messages="$errors->get('password')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            <a href="{{ route('prestations.index') }}"
                class="btn btn-danger">Annuler</a>
        </div>
    </form>
</section>
