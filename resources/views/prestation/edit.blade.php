<!-- index.blade.php -->

<x-app-layout>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <link rel="canonical"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css">
    <div class="flex items-center justify-end mt-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li> @endforeach
                </ul>
            </div><br />
        @endif
    </div>

    <form method="post"
        action="{{ route('prestations.update', $prestation->id) }}">

    <!-- Assure -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="assure" :value="__('N° Assuré')" />
        <x-text-input id="assure" class="form-control" type="text" name="assure"
            value="{{ $prestation->assure }}" />
        <x-input-error :messages="$errors->get('assure')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="date" :value="__('Date')" />
        <x-text-input id="date" class="form-control" type="date" name="date"
            value="{{ $prestation->date }}" />
        <x-input-error :messages="$errors->get('date')" class="mt-2" />
    </div>

    <!-- AP 1 -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="ap1" :value="__('Acte')" />
        <select name="ap1" class="form-control">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap1)
                    <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                @endif
            @endforeach

            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap1')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="type1" :value="__('Type Acte ')" />
        <select name="type1" class="form-control">
            <option value="{{ $prestation->type1 }}">{{ $prestation->type1 }}</option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type1')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="quantite1" :value="__('Quantité')" />
        <x-text-input id="quantite1" class="form-control" type="text" name="quantite1"
            value="{{ $prestation->quantite1 }}" />
        <x-input-error :messages="$errors->get('quantite1')" class="mt-2" />
    </div>

    <!-- AP 2 -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="ap2" :value="__('Acte')" />
        <select name="ap2" class="form-control">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap2)
                    <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap2')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="type2" :value="__('Type Acte ')" />
        <select name="type2" class="form-control">
            <option value="{{ $prestation->type2 }}">{{ $prestation->type2 }}</option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type2')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="quantite2" :value="__('Quantité')" />
        <x-text-input id="quantite2" class="form-control" type="text" name="quantite2"
            value="{{ $prestation->quantite2 }}" />
        <x-input-error :messages="$errors->get('quantite2')" class="mt-2" />
    </div>

    <!-- AP 3 -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="ap3" :value="__('Acte')" />
        <select name="ap3" class="form-control">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap3)
                    <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap3')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="type3" :value="__('Type Acte ')" />
        <select name="type3" class="form-control">
            <option value="{{ $prestation->type3 }}">{{ $prestation->type3 }}</option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type3')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="quantite3" :value="__('Quantité')" />
        <x-text-input id="quantite3" class="form-control" type="text" name="quantite3"
            value="{{ $prestation->quantite3 }}" />
        <x-input-error :messages="$errors->get('quantite3')" class="mt-2" />
    </div>

    <!-- AP 4 -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="ap4" :value="__('Acte')" />
        <select name="ap4" class="form-control">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap4)
                    <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap4')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="type4" :value="__('Type Acte ')" />
        <select name="type4" class="form-control">
            <option value="{{ $prestation->type4 }}">{{ $prestation->type4 }}</option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type4')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="quantite4" :value="__('Quantité')" />
        <x-text-input id="quantite4" class="form-control" type="text" name="quantite4"
            value="{{ $prestation->quantite4 }}" />
        <x-input-error :messages="$errors->get('quantite4')" class="mt-2" />
    </div>

    <!-- AP 5 -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="ap5" :value="__('Acte')" />
        <select name="ap5" class="form-control">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap5)
                    <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap5')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="type5" :value="__('Type Acte ')" />
        <select name="type5" class="form-control">
            <option value="{{ $prestation->type5 }}">{{ $prestation->type5 }}</option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type5')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="quantite5" :value="__('Quantité')" />
        <x-text-input id="quantite5" class="form-control" type="text" name="quantite5"
            value="{{ $prestation->quantite5 }}" />
        <x-input-error :messages="$errors->get('quantite4')" class="mt-2" />
    </div>

    <!-- AP 6 -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="ap6" :value="__('Acte')" />
        <select name="ap6" class="form-control">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap6)
                    <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap6')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="type6" :value="__('Type Acte ')" />
        <select name="type6" class="form-control">
            <option value="{{ $prestation->type6 }}">{{ $prestation->type6 }}</option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type6')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="quantite6" :value="__('Quantité')" />
        <x-text-input id="quantite6" class="form-control" type="text" name="quantite6"
            value="{{ $prestation->quantite6 }}" />
        <x-input-error :messages="$errors->get('quantite6')" class="mt-2" />
    </div>

    <!-- AP 7 -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="ap7" :value="__('Acte')" />
        <select name="ap7" class="form-control">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap7)
                    <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap7')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="type7" :value="__('Type Acte ')" />
        <select name="type7" class="form-control">
            <option value="{{ $prestation->type7 }}">{{ $prestation->type7 }}</option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type7')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="quantite7" :value="__('Quantité')" />
        <x-text-input id="quantite7" class="form-control" type="text" name="quantite7"
            value="{{ $prestation->quantite7 }}" />
        <x-input-error :messages="$errors->get('quantite7')" class="mt-2" />
    </div>

    <!-- AP 8 -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="ap8" :value="__('Acte')" />
        <select name="ap8" class="form-control">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap8)
                    <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap8')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="type8" :value="__('Type Acte ')" />
        <select name="type8" class="form-control">
            <option value="{{ $prestation->type8 }}">{{ $prestation->type8 }}</option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type8')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="quantite8" :value="__('Quantité')" />
        <x-text-input id="quantite8" class="form-control" type="text" name="quantite8"
            value="{{ $prestation->quantite8 }}" />
        <x-input-error :messages="$errors->get('quantite8')" class="mt-2" />
    </div>

    <!-- AP 9 -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="ap9" :value="__('Acte')" />
        <select name="ap9" class="form-control">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap9)
                    <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap9')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="type9" :value="__('Type Acte ')" />
        <select name="type9" class="form-control">
            <option value="{{ $prestation->type9 }}">{{ $prestation->type9 }}</option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type9')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="quantite9" :value="__('Quantité')" />
        <x-text-input id="quantite9" class="form-control" type="text" name="quantite9"
            value="{{ $prestation->quantite9 }}" />
        <x-input-error :messages="$errors->get('quantite9')" class="mt-2" />
    </div>

    <!-- AP 10 -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="ap10" :value="__('Acte')" />
        <select name="ap10" class="form-control">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap10)
                    <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach
            @foreach ($aps as $ap)
                <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('ap10')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="type10" :value="__('Type Acte ')" />
        <select name="type10" class="form-control">
            <option value="{{ $prestation->type10 }}">{{ $prestation->type10 }}</option>
            <option value="Boite">Boite</option>
            <option value="Flacon">Flacon</option>
            <option value="Unité">Unité</option>
        </select>
        <x-input-error :messages="$errors->get('type10')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="quantite10" :value="__('Quantité')" />
        <x-text-input id="quantite10" class="form-control" type="text" name="quantite10"
            value="{{ $prestation->quantite10 }}" />
        <x-input-error :messages="$errors->get('quantite10')" class="mt-2" />
    </div>

    <!-- Examen -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="nature_examen" :value="__('Nature Examen')" />
        <x-text-input id="nature_examen" class="form-control" type="text" name="nature_examen"
            value="{{ $prestation->nature_examen }}" />
        <x-input-error :messages="$errors->get('nature_examen')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="renseignement" :value="__('Renseignement Clinique')" />
        <x-text-input id="renseignement" class="form-control" type="text" name="renseignement"
            value="{{ $prestation->renseignement }}" />
        <x-input-error :messages="$errors->get('renseignement')" class="mt-2" />
    </div>

    <!-- Prescripteur -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="prescripteur" :value="__('Préscripteur')" />
        <select name="prescripteur" class="form-control">
            @foreach ($prescripteurs as $prescripteur)
                @if ($prescripteur->code_prescripteur === $prestation->prescripteur)
                    <option value="{{ $prescripteur->code_prescripteur }}">{{ $prescripteur->code_prescripteur }} | {{ $prescripteur->prenom_prescripteur }}
                        {{ $prescripteur->nom_prescripteur }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach

            @foreach ($prescripteurs as $prescripteur)
                <option value="{{ $prescripteur->code_prescripteur }}">{{ $prescripteur->code_prescripteur }} | {{ $prescripteur->prenom_prescripteur }}
                    {{ $prescripteur->nom_prescripteur }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('prescripteur')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="technicien" :value="__('Technicien')" />
        <select name="technicien" class="form-control">
            @foreach ($techniciens as $technicien)
                @if ($technicien->code_technicien === $prestation->technicien)
                    <option value="{{ $technicien->code_technicien }}">{{ $technicien->code_technicien }} | {{ $technicien->prenom_technicien }}
                        {{ $technicien->nom_technicien }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach
            <option value="">Aucun</option>
            @foreach ($techniciens as $technicien)
                <option value="{{ $technicien->code_technicien }}">{{ $technicien->code_technicien }} | {{ $technicien->prenom_technicien }}
                    {{ $technicien->nom_technicien }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('technicien')" class="mt-2" />
    </div>

    <!-- Provenance -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="provenance" :value="__('Provenance')" />
        <select name="provenance" class="form-control">
            @foreach ($hopitals as $hopital)
                @if ($hopital->code_hopital === $prestation->provenance)
                    <option value="{{ $hopital->code_hopital }}">{{ $hopital->code_hopital }} | {{ $hopital->nom_hopital }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach

            @foreach ($hopitals as $hopital)
                <option value="{{ $hopital->code_hopital }}">{{ $hopital->code_hopital }} | {{ $hopital->nom_hopital }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('provenance')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="pharmacie" :value="__('Pharmacie')" />
        <select name="pharmacie" class="form-control">
            @foreach ($pharmacies as $pharmacie)
                @if ($pharmacie->code_pharmacie === $prestation->pharmacie)
                    <option value="{{ $pharmacie->code_pharmacie }}">{{ $pharmacie->code_pharmacie }} | {{ $pharmacie->nom_pharmacie }}</option>
                @else
                    <option value=""></option>
                @endif
            @endforeach
            <option value="">Aucune</option>
            @foreach ($pharmacies as $pharmacie)
                <option value="{{ $pharmacie->code_pharmacie }}">{{ $pharmacie->code_pharmacie }} | {{ $pharmacie->nom_pharmacie }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('pharmacie')" class="mt-2" />
    </div>

    <!-- Entrer et Nom -->
    <div class="input-group mb-3">
        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="feuille" :value="__('N° Feuille de soin')" />
        <x-text-input id="feuille" class="form-control" type="text" name="feuille"
            value="{{ $prestation->feuille }}" />
        <x-input-error :messages="$errors->get('feuille')" class="mt-2" />

        @csrf
        @method('PATCH')
        <x-input-label class="input-group-text" for="controleur" :value="__('Controleur')" />
        <select name="controleur" class="form-control">
            @foreach ($users as $user)
                @if ($user->email === $prestation->controleur)
                    <option value="{{ $user->email }}">{{ $user->name }}</option>
                @endif
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('controleur')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('Modifier') }}
        </x-primary-button>
    </div>

    <div class="flex items-center justify-end mt-4">

    </div>
    </form>
</x-app-layout>
