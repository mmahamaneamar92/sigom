<header>
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Ajouter Des Préstations') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Enregistrer des nouvelles préstations dans votre base.') }}
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<script>
    $(document).ready(function() {
        $('#ap1').on('change', function() {
            var value = $(this).val();

            //Effectuez ici la logique pour obtenir les données à afficher dans le champ input
            var donnees = obtenir(value);

            //Mettre à jour le champ input
            $('#prix_public_ap1').val(donnees);
        });

        function obtenir(ap1) {
            foreach($aps as $ap)
            if (ap1 === {{ $ap->code_ap }})
                return {{ $ap->prix_public_ap }}
            else
                return {{ $ap->prix_public_ap }}
            endif
            endforeach

        }
    });
</script>

<form method="post" action="{{ route('prestations.store') }}">
    @csrf

    <!-- Assure -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="assure" :value="__('N° Assuré')" />
        <x-text-input id="assure" class="form-control" type="text" name="assure" :value="old('assure')" required
            autofocus autocomplete="assure" />
        <x-input-error :messages="$errors->get('assure')" class="mt-2" />

        <x-input-label class="input-group-text" for="date" :value="__('Date')" />
        <x-text-input id="date" class="form-control" type="date" name="date" :value="old('date')" required
            autofocus autocomplete="date" />
        <x-input-error :messages="$errors->get('date')" class="mt-2" />
    </div>

    <!-- AP 1 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="ap1" :value="__('Acte')" />
        <select id="ap1" name="ap1" class="form-control">
            <option value=""></option>
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap1')" class="mt-2" />

        <x-input-label class="input-group-text" for="type1" :value="__('Type Acte ')" />
        <select name="type1" class="form-control">
            <option value=""></option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type1')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite1" :value="__('Quantité')" />
        <x-text-input id="quantite1" class="form-control" type="text" name="quantite1" :value="old('quantite1')" required
            autofocus autocomplete="quantite1" />
        <x-input-error :messages="$errors->get('quantite1')" class="mt-2" />

        <x-input-label class="input-group-text" for="prix_public_ap1" :value="__('Prix Unitaire')" />
        <input type="text" class="form-control" id="prix_public_ap1" name="prix_public_ap1" readonly>
        <x-input-error :messages="$errors->get('prix_public_ap1')" class="mt-2" />

        <x-input-label class="input-group-text" for="total" :value="__('Total')" />
        <x-text-input id="total" class="form-control" type="text" name="total" :value="old('total')" required
            autofocus autocomplete="total" />
        <x-input-error :messages="$errors->get('total')" class="mt-2" />
    </div>

    <!-- AP 2 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="ap2" :value="__('Acte')" />
        <select name="ap2" class="form-control">
            <option value=""></option>
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap2')" class="mt-2" />

        <x-input-label class="input-group-text" for="type2" :value="__('Type Acte ')" />
        <select name="type2" class="form-control">
            <option value=""></option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type2')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite2" :value="__('Quantité')" />
        <x-text-input id="quantite2" class="form-control" type="text" name="quantite2" :value="old('quantite2')" autofocus
            autocomplete="quantite2" />
        <x-input-error :messages="$errors->get('quantite2')" class="mt-2" />
    </div>

    <!-- AP 3 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="ap3" :value="__('Acte')" />
        <select name="ap3" class="form-control">
            <option value=""></option>
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap3')" class="mt-2" />

        <x-input-label class="input-group-text" for="type3" :value="__('Type Acte ')" />
        <select name="type3" class="form-control">
            <option value=""></option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type3')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite3" :value="__('Quantité')" />
        <x-text-input id="quantite3" class="form-control" type="text" name="quantite3" :value="old('quantite3')"
            autofocus autocomplete="quantite3" />
        <x-input-error :messages="$errors->get('quantite3')" class="mt-2" />
    </div>

    <!-- AP 4 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="ap4" :value="__('Acte')" />
        <select name="ap4" class="form-control">
            <option value=""></option>
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap4')" class="mt-2" />

        <x-input-label class="input-group-text" for="type4" :value="__('Type Acte ')" />
        <select name="type4" class="form-control">
            <option value=""></option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type4')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite4" :value="__('Quantité')" />
        <x-text-input id="quantite4" class="form-control" type="text" name="quantite4" :value="old('quantite4')"
            autofocus autocomplete="quantite4" />
        <x-input-error :messages="$errors->get('quantite4')" class="mt-2" />
    </div>

    <!-- AP 5 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="ap5" :value="__('Acte')" />
        <select name="ap5" class="form-control">
            <option value=""></option>
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap5')" class="mt-2" />

        <x-input-label class="input-group-text" for="type5" :value="__('Type Acte ')" />
        <select name="type5" class="form-control">
            <option value=""></option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type5')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite5" :value="__('Quantité')" />
        <x-text-input id="quantite5" class="form-control" type="text" name="quantite5" :value="old('quantite5')"
            autofocus autocomplete="quantite5" />
        <x-input-error :messages="$errors->get('quantite5')" class="mt-2" />
    </div>

    <!-- AP 6 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="ap6" :value="__('Acte')" />
        <select name="ap6" class="form-control">
            <option value=""></option>
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap6')" class="mt-2" />

        <x-input-label class="input-group-text" for="type6" :value="__('Type Acte ')" />
        <select name="type6" class="form-control">
            <option value=""></option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type6')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite6" :value="__('Quantité')" />
        <x-text-input id="quantite6" class="form-control" type="text" name="quantite6" :value="old('quantite6')"
            autofocus autocomplete="quantite6" />
        <x-input-error :messages="$errors->get('quantite6')" class="mt-2" />
    </div>

    <!-- AP 7 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="ap7" :value="__('Acte')" />
        <select name="ap7" class="form-control">
            <option value=""></option>
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap7')" class="mt-2" />

        <x-input-label class="input-group-text" for="type7" :value="__('Type Acte ')" />
        <select name="type2" class="form-control">
            <option value=""></option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type7')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite7" :value="__('Quantité')" />
        <x-text-input id="quantite7" class="form-control" type="text" name="quantite7" :value="old('quantite7')"
            autofocus autocomplete="quantite7" />
        <x-input-error :messages="$errors->get('quantite7')" class="mt-2" />
    </div>

    <!-- AP 8 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="ap8" :value="__('Acte')" />
        <select name="ap8" class="form-control">
            <option value=""></option>
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap8')" class="mt-2" />

        <x-input-label class="input-group-text" for="type8" :value="__('Type Acte ')" />
        <select name="type8" class="form-control">
            <option value=""></option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type8')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite8" :value="__('Quantité')" />
        <x-text-input id="quantite8" class="form-control" type="text" name="quantite8" :value="old('quantite8')"
            autofocus autocomplete="quantite8" />
        <x-input-error :messages="$errors->get('quantite8')" class="mt-2" />
    </div>

    <!-- AP 9 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="ap4" :value="__('Acte')" />
        <select name="ap9" class="form-control">
            <option value=""></option>
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap9')" class="mt-2" />

        <x-input-label class="input-group-text" for="type9" :value="__('Type Acte ')" />
        <select name="type9" class="form-control">
            <option value=""></option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type9')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite9" :value="__('Quantité')" />
        <x-text-input id="quantite9" class="form-control" type="text" name="quantite9" :value="old('quantite9')"
            autofocus autocomplete="quantite9" />
        <x-input-error :messages="$errors->get('quantite9')" class="mt-2" />
    </div>

    <!-- AP 10 -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="ap10" :value="__('Acte')" />
        <select name="ap10" class="form-control">
            <option value=""></option>
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap10')" class="mt-2" />

        <x-input-label class="input-group-text" for="type10" :value="__('Type Acte ')" />
        <select name="type10" class="form-control">
            <option value=""></option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type10')" class="mt-2" />

        <x-input-label class="input-group-text" for="quantite10" :value="__('Quantité')" />
        <x-text-input id="quantite10" class="form-control" type="text" name="quantite10" :value="old('quantite10')"
            autofocus autocomplete="quantite5" />
        <x-input-error :messages="$errors->get('quantite10')" class="mt-2" />
    </div>

    <!-- Examen -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="nature_examen" :value="__('Nature Examen')" />
        <textarea class="form-control" id="nature_examen" aria-label="With textarea" name="nature_examen"
            :value="old('nature_examen')" autofocus autocomplete="nature_examen"></textarea>
        <x-input-error :messages="$errors->get('nature_examen')" class="mt-2" />

        <x-input-label class="input-group-text" for="renseignement" :value="__('Renseignement Clinique')" />
        <textarea class="form-control" id="renseignement" aria-label="With textarea" name="renseignement"
            :value="old('renseignement')" required autofocus autocomplete="renseignement"></textarea>
        <x-input-error :messages="$errors->get('renseignement')" class="mt-2" />
    </div>

    <!-- Prescripteur -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="prescripteur" :value="__('Préscripteur')" />
        <select name="prescripteur" class="form-control">
            <option value=""></option>
            @foreach ($prescripteurs as $prescripteur)
                <option value="{{ $prescripteur->code_prescripteur }}">{{ $prescripteur->code_prescripteur }} |
                    {{ $prescripteur->prenom_prescripteur }} {{ $prescripteur->nom_prescripteur }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('prescripteur')" class="mt-2" />

        <x-input-label class="input-group-text" for="technicien" :value="__('Technicien')" />
        <select name="technicien" class="form-control">
            <option value=""></option>
            @foreach ($techniciens as $technicien)
                <option value="{{ $technicien->code_technicien }}">{{ $technicien->code_technicien }} |
                    {{ $technicien->prenom_technicien }} {{ $technicien->nom_technicien }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('technicien')" class="mt-2" />
    </div>

    <!-- Provenance -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="provenance" :value="__('Provenance')" />
        <select name="provenance" class="form-control">
            <option value=""></option>
            @foreach ($hopitals as $hopital)
                <option value="{{ $hopital->code_hopital }}">{{ $hopital->code_hopital }} |
                    {{ $hopital->nom_hopital }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('provenance')" class="mt-2" />

        <x-input-label class="input-group-text" for="pharmacie" :value="__('Pharmacie')" />
        <select name="pharmacie" class="form-control">
            <option value=""></option>
            @foreach ($pharmacies as $pharmacie)
                <option value="{{ $pharmacie->code_pharmacie }}">{{ $pharmacie->code_pharmacie }} |
                    {{ $pharmacie->nom_pharmacie }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('pharmacie')" class="mt-2" />
    </div>

    <!-- Entrer et Nom -->
    <div class="input-group mb-3">
        <x-input-label class="input-group-text" for="typeFeuille" :value="__('Type Feuille')" />
        <select name="typeFeuille" class="form-control">
            <option value=""></option>
            <option value="23 ">23</option>
            <option value="24 ">24</option>
        </select>
        <x-input-error :messages="$errors->get('typeFeuille')" class="mt-2" />

        <x-input-label class="input-group-text" for="feuille" :value="__('N° Feuille de soin')" />
        <x-text-input id="feuille" class="form-control" type="text" name="feuille" :value="old('feuille')" autofocus
            autocomplete="feuille" />
        <x-input-error :messages="$errors->get('feuille')" class="mt-2" />

        <x-input-label class="input-group-text" for="controleur" :value="__('Controleur')" />
        <select name="controleur" class="form-control">
            <option value={{ Auth::user()->email }}>{{ Auth::user()->name }}</option>
        </select>
        <x-input-error :messages="$errors->get('controleur')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('Enregistrer') }}
        </x-primary-button>
    </div>
</form>
