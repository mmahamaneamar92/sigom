<x-app-layout>

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

    <form method="post" action="{{ route('prestations.store') }}">
        @csrf

        <!-- Assure -->
        <div class="input-group mb-3">
            <x-input-label class="input-group-text" for="assure" :value="__('N° Assuré')" />
            <select name="assure" class="form-control">
                <option value="{{ $assure }}">{{ $assure }}</option>
            </select>
            <x-input-error :messages="$errors->get('assure')" class="mt-2" />

            <x-input-label class="input-group-text" for="nomprenomassure" :value="__('Prénom Et Nom')" />
            <select name="nomprenomassure" class="form-control">
                @foreach ($assures as $asure)
                    @if ($asure->numero_assurer === $assure)
                        <option value="">{{ $asure->prenom }} {{ $asure->nom }}</option>
                    @endif
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('assure')" class="mt-2" />

            <x-input-label class="input-group-text" for="date" :value="__('Date')" />
            <select name="date" class="form-control">
                <option value="{{ $date }}">{{ $date }}</option>
            </select>
            <x-input-error :messages="$errors->get('date')" class="mt-2" />
        </div>

        <!-- AP 1 -->
        <div class="input-group mb-3">
            <x-input-label class="input-group-text" for="ap1" :value="__('Acte')" />
            <select name="ap1" class="form-control">
                @foreach ($aps as $ap)
                    @if ($ap->code_ap === $code1)
                        <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                    @endif
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('ap1')" class="mt-2" />

            <x-input-label class="input-group-text" for="type1" :value="__('Type Acte ')" />
            <select name="type1" class="form-control">
                <option value="{{ $type1 }}">{{ $type1 }}</option>
            </select>
            <x-input-error :messages="$errors->get('type1')" class="mt-2" />

            <x-input-label class="input-group-text" for="quantite1" :value="__('Quantité')" />
            <select name="quantite1" class="form-control">
                <option value="{{ $quantite1 }}">{{ $quantite1 }}</option>
            </select>
            <x-input-error :messages="$errors->get('quantite1')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code1)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="prix_public_ap1" :value="__('Prix Unitaire')" />
                    <select name="prix_public_ap1" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap1')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code1)
                    @php
                        $nombre = $ap->prix_public_ap * $quantite1;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="total" :value="__('Total')" />
                    <select name="prix_public_ap1" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap1')" class="mt-2" />
        </div>

        <!-- AP 2 -->
        <div class="input-group mb-3">

            <x-input-label class="input-group-text" for="ap2" :value="__('Acte')" />
            <select name="ap2" class="form-control">
                @foreach ($aps as $ap)
                    @if ($ap->code_ap === $code2)
                        <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                    @endif
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('ap2')" class="mt-2" />

            <x-input-label class="input-group-text" for="type2" :value="__('Type Acte ')" />
            <select name="type2" class="form-control">
                <option value="{{ $type11 }}">{{ $type11 }}</option>
            </select>
            <x-input-error :messages="$errors->get('type2')" class="mt-2" />

            <x-input-label class="input-group-text" for="quantite2" :value="__('Quantité')" />
            <select name="quantite2" class="form-control">
                <option value="{{ $quantite2 }}">{{ $quantite2 }}</option>
            </select>
            <x-input-error :messages="$errors->get('quantite2')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code2)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="prix_public_ap2" :value="__('Prix Unitaire')" />
                    <select name="prix_public_ap2" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap2')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code2)
                    @php
                        $nombre = $ap->prix_public_ap * $quantite2;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="total" :value="__('Total')" />
                    <select name="prix_public_ap2" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap2')" class="mt-2" />
        </div>

        <!-- AP 3 -->
        <div class="input-group mb-3">

            <x-input-label class="input-group-text" for="ap3" :value="__('Acte')" />
            <select name="ap3" class="form-control">
                @foreach ($aps as $ap)
                    @if ($ap->code_ap === $code3)
                        <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                    @endif
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('ap3')" class="mt-2" />

            <x-input-label class="input-group-text" for="type3" :value="__('Type Acte ')" />
            <select name="type3" class="form-control">
                <option value="{{ $type3 }}">{{ $type3 }}</option>
            </select>
            <x-input-error :messages="$errors->get('type3')" class="mt-2" />

            <x-input-label class="input-group-text" for="quantite3" :value="__('Quantité')" />
            <select name="quantite3" class="form-control">
                <option value="{{ $quantite3 }}">{{ $quantite3 }}</option>
            </select>
            <x-input-error :messages="$errors->get('quantite3')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code3)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="prix_public_ap3" :value="__('Prix Unitaire')" />
                    <select name="prix_public_ap3" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap3')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code3)
                    @php
                        $nombre = $ap->prix_public_ap * $quantite3;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="total" :value="__('Total')" />
                    <select name="prix_public_ap3" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap3')" class="mt-2" />
        </div>

        <!-- AP 4 -->
        <div class="input-group mb-3">

            <x-input-label class="input-group-text" for="ap4" :value="__('Acte')" />
            <select name="ap4" class="form-control">
                @foreach ($aps as $ap)
                    @if ($ap->code_ap === $code4)
                        <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                    @endif
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('ap4')" class="mt-2" />

            <x-input-label class="input-group-text" for="type4" :value="__('Type Acte ')" />
            <select name="type3" class="form-control">
                <option value="{{ $type4 }}">{{ $type4 }}</option>
            </select>
            <x-input-error :messages="$errors->get('type4')" class="mt-2" />

            <x-input-label class="input-group-text" for="quantite4" :value="__('Quantité')" />
            <select name="quantite4" class="form-control">
                <option value="{{ $quantite4 }}">{{ $quantite4 }}</option>
            </select>
            <x-input-error :messages="$errors->get('quantite4')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code4)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="prix_public_ap4" :value="__('Prix Unitaire')" />
                    <select name="prix_public_ap4" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap4')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code4)
                    @php
                        $nombre = $ap->prix_public_ap * $quantite4;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="total" :value="__('Total')" />
                    <select name="prix_public_ap4" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap4')" class="mt-2" />
        </div>

        <!-- AP 5 -->
        <div class="input-group mb-3">

            <x-input-label class="input-group-text" for="ap5" :value="__('Acte')" />
            <select name="ap5" class="form-control">
                @foreach ($aps as $ap)
                    @if ($ap->code_ap === $code5)
                        <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                    @endif
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('ap5')" class="mt-2" />

            <x-input-label class="input-group-text" for="type4" :value="__('Type Acte ')" />
            <select name="type3" class="form-control">
                <option value="{{ $type5 }}">{{ $type5 }}</option>
            </select>
            <x-input-error :messages="$errors->get('type5')" class="mt-2" />

            <x-input-label class="input-group-text" for="quantite5" :value="__('Quantité')" />
            <select name="quantite5" class="form-control">
                <option value="{{ $quantite5 }}">{{ $quantite5 }}</option>
            </select>
            <x-input-error :messages="$errors->get('quantite5')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code5)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="prix_public_ap5" :value="__('Prix Unitaire')" />
                    <select name="prix_public_ap5" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap5')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code5)
                    @php
                        $nombre = $ap->prix_public_ap * $quantite5;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="total" :value="__('Total')" />
                    <select name="prix_public_ap5" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap5')" class="mt-2" />
        </div>

        <!-- AP 6 -->
        <div class="input-group mb-3">

            <x-input-label class="input-group-text" for="ap6" :value="__('Acte')" />
            <select name="ap6" class="form-control">
                @foreach ($aps as $ap)
                    @if ($ap->code_ap === $code6)
                        <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                    @endif
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('ap6')" class="mt-2" />

            <x-input-label class="input-group-text" for="type6" :value="__('Type Acte ')" />
            <select name="type6" class="form-control">
                <option value="{{ $type6 }}">{{ $type6 }}</option>
            </select>
            <x-input-error :messages="$errors->get('type6')" class="mt-2" />

            <x-input-label class="input-group-text" for="quantite6" :value="__('Quantité')" />
            <select name="quantite6" class="form-control">
                <option value="{{ $quantite6 }}">{{ $quantite6 }}</option>
            </select>
            <x-input-error :messages="$errors->get('quantite6')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code6)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="prix_public_ap6" :value="__('Prix Unitaire')" />
                    <select name="prix_public_ap6" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap6')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code6)
                    @php
                        $nombre = $ap->prix_public_ap * $quantite6;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="total" :value="__('Total')" />
                    <select name="prix_public_ap6" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap6')" class="mt-2" />
        </div>

        <!-- AP 7 -->
        <div class="input-group mb-3">

            <x-input-label class="input-group-text" for="ap7" :value="__('Acte')" />
            <select name="ap7" class="form-control">
                @foreach ($aps as $ap)
                    @if ($ap->code_ap === $code7)
                        <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                    @endif
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('ap7')" class="mt-2" />

            <x-input-label class="input-group-text" for="type7" :value="__('Type Acte ')" />
            <select name="type7" class="form-control">
                <option value="{{ $type7 }}">{{ $type7 }}</option>
            </select>
            <x-input-error :messages="$errors->get('type7')" class="mt-2" />

            <x-input-label class="input-group-text" for="quantite7" :value="__('Quantité')" />
            <select name="quantite7" class="form-control">
                <option value="{{ $quantite7 }}">{{ $quantite7 }}</option>
            </select>
            <x-input-error :messages="$errors->get('quantite7')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code7)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="prix_public_ap7" :value="__('Prix Unitaire')" />
                    <select name="prix_public_ap7" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap7')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code7)
                    @php
                        $nombre = $ap->prix_public_ap * $quantite7;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="total" :value="__('Total')" />
                    <select name="prix_public_ap7" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap7')" class="mt-2" />
        </div>

        <!-- AP 8 -->
        <div class="input-group mb-3">

            <x-input-label class="input-group-text" for="ap8" :value="__('Acte')" />
            <select name="ap8" class="form-control">
                @foreach ($aps as $ap)
                    @if ($ap->code_ap === $code8)
                        <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                    @endif
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('ap8')" class="mt-2" />

            <x-input-label class="input-group-text" for="type8" :value="__('Type Acte ')" />
            <select name="type8" class="form-control">
                <option value="{{ $type8 }}">{{ $type8 }}</option>
            </select>
            <x-input-error :messages="$errors->get('type8')" class="mt-2" />

            <x-input-label class="input-group-text" for="quantite8" :value="__('Quantité')" />
            <select name="quantite8" class="form-control">
                <option value="{{ $quantite8 }}">{{ $quantite8 }}</option>
            </select>
            <x-input-error :messages="$errors->get('quantite8')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code3)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="prix_public_ap8" :value="__('Prix Unitaire')" />
                    <select name="prix_public_ap8" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap8')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code8)
                    @php
                        $nombre = $ap->prix_public_ap * $quantite8;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="total" :value="__('Total')" />
                    <select name="prix_public_ap8" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap8')" class="mt-2" />
        </div>

        <!-- AP 9 -->
        <div class="input-group mb-3">

            <x-input-label class="input-group-text" for="ap9" :value="__('Acte')" />
            <select name="ap9" class="form-control">
                @foreach ($aps as $ap)
                    @if ($ap->code_ap === $code9)
                        <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                    @endif
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('ap9')" class="mt-2" />

            <x-input-label class="input-group-text" for="type9" :value="__('Type Acte ')" />
            <select name="type9" class="form-control">
                <option value="{{ $type9 }}">{{ $type9 }}</option>
            </select>
            <x-input-error :messages="$errors->get('type9')" class="mt-2" />

            <x-input-label class="input-group-text" for="quantite9" :value="__('Quantité')" />
            <select name="quantite9" class="form-control">
                <option value="{{ $quantite9 }}">{{ $quantite9 }}</option>
            </select>
            <x-input-error :messages="$errors->get('quantite9')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code9)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="prix_public_ap9" :value="__('Prix Unitaire')" />
                    <select name="prix_public_ap9" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap9')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code9)
                    @php
                        $nombre = $ap->prix_public_ap * $quantite4;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="total" :value="__('Total')" />
                    <select name="prix_public_ap9" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap9')" class="mt-2" />
        </div>

        <!-- AP 10 -->
        <div class="input-group mb-3">

            <x-input-label class="input-group-text" for="ap10" :value="__('Acte')" />
            <select name="ap10" class="form-control">
                @foreach ($aps as $ap)
                    @if ($ap->code_ap === $code10)
                        <option value="{{ $ap->code_ap }}">{{ $ap->designation_ap }}</option>
                    @endif
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('ap10')" class="mt-2" />

            <x-input-label class="input-group-text" for="type10" :value="__('Type Acte ')" />
            <select name="type10" class="form-control">
                <option value="{{ $type10 }}">{{ $type10 }}</option>
            </select>
            <x-input-error :messages="$errors->get('type10')" class="mt-2" />

            <x-input-label class="input-group-text" for="quantite10" :value="__('Quantité')" />
            <select name="quantite10" class="form-control">
                <option value="{{ $quantite10 }}">{{ $quantite10 }}</option>
            </select>
            <x-input-error :messages="$errors->get('quantite10')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code10)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="prix_public_ap10" :value="__('Prix Unitaire')" />
                    <select name="prix_public_ap10" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap10')" class="mt-2" />

            @foreach ($aps as $ap)
                @if ($ap->code_ap === $code10)
                    @php
                        $nombre = $ap->prix_public_ap * $quantite10;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    <x-input-label class="input-group-text" for="total" :value="__('Total')" />
                    <select name="prix_public_ap10" class="form-control">
                        <option value="{{ $nbre }}">{{ $nbre }}</option>
                    </select>
                @endif
            @endforeach
            <x-input-error :messages="$errors->get('prix_public_ap10')" class="mt-2" />
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
            <x-input-label class="input-group-text" for="feuille" :value="__('N° Feuille de soin')" />
            <x-text-input id="feuille" class="form-control" type="text" name="feuille" :value="old('feuille')"
                autofocus autocomplete="feuille" />
            <x-input-error :messages="$errors->get('feuille')" class="mt-2" />

            <x-input-label class="input-group-text" for="controleur" :value="__('Controleur')" />
            <select name="controleur" class="form-control">
                <option value={{ Auth::user()->email }}>{{ Auth::user()->name }}</option>
            </select>
            <x-input-error :messages="$errors->get('controleur')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="btn btn-primary mt-2">
                {{ __('Valider') }}
            </x-primary-button>

            <<x-nav-link :href="route('prestations.index')" :active="request()->routeIs('prestations.index')">
                {{ __('Non Valider') }}
                </x-nav-link>
        </div>

        <div class="flex items-center justify-end mt-4">

        </div>
    </form>

</x-app-layout>
