<!-- Direction/Service -->

<form method="post" action="{{ route('agents.store') }}">
    @csrf
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="matricule" :value="__('Matricule')" />
        <x-text-input id="matricule" class="form-control" type="text" name="matricule" :value="old('matricule')" required
            autofocus autocomplete="matricule" />
        <x-input-error :messages="$errors->get('matricule')" class="mt-2" />

        <x-input-label class="input-group-text" for="prenom" :value="__('Prénom')" />
        <x-text-input id="prenom" class="form-control" type="text" name="prenom" :value="old('prenom')" required
            autofocus autocomplete="prenom" />
        <x-input-error :messages="$errors->get('prenom')" class="mt-2" />

        <x-input-label class="input-group-text" for="nom" :value="__('Nom')" />
        <x-text-input id="nom" class="form-control" type="text" name="nom" :value="old('nom')" required
            autofocus autocomplete="nom" />
        <x-input-error :messages="$errors->get('nom')" class="mt-2" />
    </div>

    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="direction" :value="__('Direction')" />
        <select id="direction" name="direction" class="form-control">
            <option value=""></option>
            @foreach ($directions as $direction)
                <option value="{{ $direction->nom_direction }}">{{ $direction->nom_direction }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('direction')" class="mt-2" />

        <x-input-label class="input-group-text" for="service" :value="__('Service')" />
        <select id='service' name='service' class="form-select">
            <option value='0'>-- Select Service --</option>
        </select>
        <x-input-error :messages="$errors->get('service')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('Enregistrer') }}
        </x-primary-button>
    </div>
</form>

<script type='text/javascript'>
    $(document).ready(function() {

        $('#direction').change(function() {
            // Department id
            var id = $(this).val();

            // Empty the dropdown
            $('#service').find('option').not(':first').remove();

            // AJAX request
            $.ajax({
                url: 'getServices/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }

                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var name = response['data'][i].nom_service;
                            var option = "<option value='" + name + "'>" + name + "</option>";
                            $("#service").append(option);
                        }
                    }
                }
            });
        });
    });
</script>
