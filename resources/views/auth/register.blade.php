<header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Ajouter Des Utilisateurs') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Enregistrer des nouveaux utilisateurs dans votre base de donnée.') }}
    </p>
</header>
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

<form method="post" action="{{ route('utilisateurs.store') }}">
    @csrf

    <!-- Profil -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="profil" :value="__('Profil')" />
        <select name="profil" class="form-control">
            <option value=""></option>
            @foreach ($profils as $profil)
                <option value="{{ $profil->nom_profil }}">{{ $profil->nom_profil }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('profil')" class="mt-2" />

        <x-input-label class="input-group-text" for="name" :value="__('Name')" />
        <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required
            autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />

        <x-input-label class="input-group-text" for="email" :value="__('Email')" />
        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required
            autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Direction -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="direct" :value="__('Direction')" />
        <select name="direct" class="form-control">
            <option value=""></option>
            @foreach ($directs as $direct)
                <option value="{{ $direct->nom_direction }}">{{ $direct->nom_direction }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('direct')" class="mt-2" />

        <x-input-label class="input-group-text" for="password" :value="__('Password')" />
        <x-text-input id="password" class="form-control" type="password" name="password" required
            autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />

        <x-input-label class="input-group-text" for="password_confirmation" :value="__('Confirm Password')" />
        <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation"
            required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('ENREGISTRER') }}
        </x-primary-button>
    </div>
</form>
