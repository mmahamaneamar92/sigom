<x-guest-layout>

    <form method="post" action="{{ route('agents.update', $agent->id) }}">
        <div class="input-group mb-3">
            @csrf
            @method('PATCH')
            <x-input-label class="input-group-text" for="matricule" :value="__('Matricule')" />
            <x-text-input id="matricule" class="block mt-1 w-full" type="text" name="matricule"
                value="{{ $agent->matricule }}" />
            <x-input-error :messages="$errors->get('matricule')" class="mt-2" />

            <x-input-label class="input-group-text" for="prenom" :value="__('Prénom')" />
            <x-text-input id="prenom" class="block mt-1 w-full" type="text" name="prenom"
                value="{{ $agent->prenom }}" />
            <x-input-error :messages="$errors->get('prenom')" class="mt-2" />

            <x-input-label class="input-group-text" for="nom" :value="__('Nom')" />
            <x-text-input id="nom" class="block mt-1 w-full" type="text" name="nom"
                value="{{ $agent->nom }}" />
            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
        </div>

        <div class="input-group mb-3">
            @csrf
            @method('PATCH')
            <x-input-label class="input-group-text" for="direction" :value="__('Direction')" />
            <select id='direction' name='direction' class="block mt-1 w-full">
                @foreach ($services as $service)
                    @foreach ($directions as $direction)
                        @if ($direction->nom_direction === $service->direction && $service->nom_service === $agent->service)
                            <option value="{{ $direction->nom_direction }}">{{ $direction->nom_direction }} :
                                {{ $direction->description }}</option>
                        @endif
                    @endforeach
                @endforeach
                <option value=""></option>
                @foreach ($directions as $direction)
                    <option value="{{ $direction->nom_direction }}">{{ $direction->nom_direction }} :
                        {{ $direction->description }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('direction')" class="mt-2" />

            <x-input-label class="input-group-text" for="service" :value="__('Service')" />
            <select id='service' name='service' class="block mt-1 w-full">
                @foreach ($services as $service)
                    @if ($service->nom_service === $agent->service)
                        <option value="{{ $service->nom_service }}">{{ $service->nom_service }} :
                            {{ $service->description }}</option>
                    @endif
                @endforeach
                <option value=""></option>
                @foreach ($services as $service)
                    <option value="{{ $service->nom_service }}">{{ $service->nom_service }} :
                        {{ $service->description }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('service')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ml-4">
                {{ __('Modifier') }}
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>
