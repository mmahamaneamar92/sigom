<!-- index.blade.php -->

<x-app-layout>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <link rel="canonical"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css">

<div>
    @include('prestation.creation')
</div>
<div class="flex items-center justify-end mt-4">
@if (session()->get('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div><br />
  @endif
</div>

<script>
    $(document).ready(function() {
    $('#search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#tableau tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    });
</script>

<div class="input-group">
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Inserez le numéro AMO dont vous voullez extraire les prestations.') }}
    </p>
</div>

<div class="input-group">
    <form action="{{ route('prestRech') }}" method="GET">

        <div class="input-group mb-3">
            <x-input-label class="input-group-text" for="assure" :value="__('N° Assuré')" />
                        <input type="text" class="form-control" name="assure" id="assure">
            <x-input-error :messages="$errors->get('assure')" class="mt-2" />
        </div>

        <button type="submit" class="btn btn-primary mt-2">Rechercher</button>
    </form>
</div>

<div class="input-group">
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Inserez la date de prestation que vous voullez extraire.') }}
    </p>
</div>

<!--
<div class="input-group">
<form action="{{ route('generate-pdf') }}" method="GET">
    <div class="input-group mb-3">

    <x-input-label class="input-group-text" for="start_date" :value="__('Date de début')" />
                <input type="date" class="form-control" name="start_date" id="start_date">
    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />

<x-input-label class="input-group-text" for="end_date" :value="__('Date de fin')" />
                <input type="date" class="form-control" name="end_date" id="end_date">
<x-input-error :messages="$errors->get('end_date')" class="mt-2" />

</div>
<button type="submit" class="btn btn-primary mt-2">Exporter En PDF</button>
</form>
</div>

-->

<div class="flex items-center justify-end mt-4">

</div>
<div>
    <x-text-input id="search" type="text" placeholder="Recherche..."/>
</div>

<div class="flex items-center justify-end mt-4">

  <table id="tableau" class="table table-striped">

    <thead>
        <tr>
          <td class="table-primary">Assuré</td>
          <td class="table-primary">Information</td>
          <td class="table-primary">Préscripteur</td>
          <td class="table-primary">Provenance</td>
          <td class="table-primary">Contrôleur</td>
          <td class="table-primary">Acte ou prestation</td>
          <td class="table-primary">Quantité</td>
          <td class="table-primary">Type</td>
          <td class="table-primary">Prix publique unitaire</td>
          <td class="table-primary">Prix publique total</td>


          <td class="table-primary" colspan="2" align="center"></td>

        </tr>
    </thead>

    <tbody>
        @foreach ($prestations as $prestation)
        <tr>
            <td rowspan="10" align="center" class="table-primary">
                @foreach ($assures as $assure)
                @if ($assure->numero_assurer === $prestation->assure)
                    @php
                        $telephone = $assure->tel;
                        $groupes = str_split($telephone, 2);
                        $tel = implode(' ', $groupes);
                    @endphp
                {{ $assure->prenom }} {{ $assure->nom }} <Br></Br>
                {{ $assure->numero_assurer }} <Br></Br>
                {{ $assure->ogd }} <Br></Br>
                {{ $tel }} <Br></Br>
                @endif @endforeach
            </td>
    <td rowspan="10"
        class="table-primary">
    {{ $prestation->date }} <Br></Br>
    N° Feuille : {{ $prestation->feuille }} <Br></Br>
    Nature Examen : {{ $prestation->nature_examen }} <Br></Br>
    Renseignement Clinique : {{ $prestation->renseignement }} <Br></Br>
    </td>
    <td rowspan="10" class="table-primary">
        @foreach ($prescripteurs as $prescripteur)
            @if ($prescripteur->code_prescripteur === $prestation->prescripteur)
                {{ $prescripteur->prenom_prescripteur }} {{ $prescripteur->nom_prescripteur }}
            @endif
        @endforeach
    </td>
    <td rowspan="10" class="table-primary">
        @foreach ($hopitals as $hopital)
            @if ($hopital->code_hopital === $prestation->provenance)
                {{ $hopital->nom_hopital }}
            @endif
        @endforeach
    </td>
    <td rowspan="10" class="table-primary">
        @foreach ($users as $user)
            @if ($user->email === $prestation->controleur)
                {{ $user->name }}
            @endif
        @endforeach
    </td>
    <td class="table-primary">
        @foreach ($aps as $ap)
            @if ($ap->code_ap === $prestation->ap1)
                {{ $ap->designation_ap }}
            @endif
        @endforeach
    </td>
    <td class="table-primary">{{ $prestation->quantite1 }}</td>
    <td class="table-primary">{{ $prestation->type1 }}</td>
    <td class="table-primary">
        @foreach ($aps as $ap)
            @if ($ap->code_ap === $prestation->ap1)
                @php
                    $nombre = $ap->prix_public_ap;
                    $nbre = number_format($nombre, 0, '', ' ');
                @endphp
                {{ $nbre }}
            @endif
        @endforeach
    </td>
    <td class="table-primary">
        @foreach ($aps as $ap)
            @if ($ap->code_ap === $prestation->ap1)
                @php
                    $nombre = $ap->prix_public_ap * $prestation->quantite1;
                    $nbre = number_format($nombre, 0, '', ' ');
                @endphp
                {{ $nbre }}
            @endif
        @endforeach
    </td>

    <td rowspan="10"><a href="{{ route('prestations.edit', $prestation->id) }}" class="btn btn-primary">Modifier</a>
    </td>

    <td rowspan="10">
        <form action="{{ route('prestations.destroy', $prestation->id) }}" method="post">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit">Supprimer</button>
        </form>
    </td>

    </tr>


    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap2)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $prestation->quantite2 }}</td>
        <td class="table-primary">{{ $prestation->type2 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap2)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap2)
                    @php
                        $nombre = $ap->prix_public_ap * $prestation->quantite2;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap3)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $prestation->quantite3 }}</td>
        <td class="table-primary">{{ $prestation->type3 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap3)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap3)
                    @php
                        $nombre = $ap->prix_public_ap * $prestation->quantite3;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap4)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $prestation->quantite4 }}</td>
        <td class="table-primary">{{ $prestation->type4 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap4)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap4)
                    @php
                        $nombre = $ap->prix_public_ap * $prestation->quantite4;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap5)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $prestation->quantite5 }}</td>
        <td class="table-primary">{{ $prestation->type5 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap5)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap5)
                    @php
                        $nombre = $ap->prix_public_ap * $prestation->quantite5;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap6)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $prestation->quantite6 }}</td>
        <td class="table-primary">{{ $prestation->type6 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap6)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap6)
                    @php
                        $nombre = $ap->prix_public_ap * $prestation->quantite6;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap7)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $prestation->quantite7 }}</td>
        <td class="table-primary">{{ $prestation->type7 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap7)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap7)
                    @php
                        $nombre = $ap->prix_public_ap * $prestation->quantite7;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap8)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $prestation->quantite8 }}</td>
        <td class="table-primary">{{ $prestation->type8 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap8)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap8)
                    @php
                        $nombre = $ap->prix_public_ap * $prestation->quantite8;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap9)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $prestation->quantite9 }}</td>
        <td class="table-primary">{{ $prestation->type9 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap9)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap9)
                    @php
                        $nombre = $ap->prix_public_ap * $prestation->quantite9;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap10)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $prestation->quantite10 }}</td>
        <td class="table-primary">{{ $prestation->type10 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap10)
                    @php
                        $nombre = $ap->prix_public_ap;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $prestation->ap10)
                    @php
                        $nombre = $ap->prix_public_ap * $prestation->quantite10;
                        $nbre = number_format($nombre, 0, '', ' ');
                    @endphp
                    {{ $nbre }}
                @endif
            @endforeach
        </td>

    </tr>

    @endforeach
    </tbody>
    </table>
    </div>
    <div class="flex items-center justify-end mt-4">
        {{ $prestations->links() }}
    </div>
    <div class="flex items-center justify-end mt-4">

    </div>
</x-app-layout>
