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
    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tr").filter(function() {
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

<div class="flex items-center justify-end mt-4">

</div>
<div>
    <x-text-input id="search" type="text" placeholder="Recherche..."/>
</div>

<div class="flex items-center justify-end mt-4">

  <table class="table table-striped">

    <thead>
        <tr>
          <td class="table-primary">Assuré</td>
          <td class="table-primary">Date, Examen Et Renseignement</td>
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
        @foreach ($resultats as $resultat)
        <tr>
            <td rowspan="10" align="center" class="table-primary">
                @foreach ($assures as $assure)
                @if ($assure->numero_assurer === $resultat->assure)
                {{ $assure->prenom }} {{ $assure->nom }} <Br></Br>
                {{ $assure->numero_assurer }} <Br></Br>
                {{ $assure->ogd }} <Br></Br>
                {{ $assure->tel }} <Br></Br>
                @endif @endforeach
            </td>
    <td rowspan="10" class="table-primary">
        {{ $resultat->date }} <Br></Br>
        N° Feuille : {{ $resultat->feuille }} <Br></Br>
        Nature Examen : {{ $resultat->nature_examen }} <Br></Br>
        Renseignement Clinique : {{ $resultat->renseignement }} <Br></Br>
    </td>
    <td rowspan="10" class="table-primary">
        @foreach ($prescripteurs as $prescripteur)
            @if ($prescripteur->code_prescripteur === $resultat->prescripteur)
                {{ $prescripteur->prenom_prescripteur }} {{ $prescripteur->nom_prescripteur }}
            @endif
        @endforeach
    </td>
    <td rowspan="10" class="table-primary">
        @foreach ($hopitals as $hopital)
            @if ($hopital->code_hopital === $resultat->provenance)
                {{ $hopital->nom_hopital }}
            @endif
        @endforeach
    </td>
    <td rowspan="10" class="table-primary">
        @foreach ($users as $user)
            @if ($user->email === $resultat->controleur)
                {{ $user->name }}
            @endif
        @endforeach
    </td>
    <td class="table-primary">
        @foreach ($aps as $ap)
            @if ($ap->code_ap === $resultat->ap1)
                {{ $ap->designation_ap }}
            @endif
        @endforeach
    </td>
    <td class="table-primary">{{ $resultat->quantite1 }}</td>
    <td class="table-primary">{{ $resultat->type1 }}</td>
    <td class="table-primary">
        @foreach ($aps as $ap)
            @if ($ap->code_ap === $resultat->ap1)
                {{ $ap->prix_public_ap }}
            @endif
        @endforeach
    </td>
    <td class="table-primary">
        @foreach ($aps as $ap)
            @if ($ap->code_ap === $resultat->ap1)
                {{ $ap->prix_public_ap * $resultat->quantite1 }}
            @endif
        @endforeach
    </td>

    <td rowspan="10"><a href="{{ route('prestations.edit', $resultat->id) }}" class="btn btn-primary">Modifier</a></td>

    @if (Auth::user()->name === 'Admin')
        <td rowspan="10">
            <form action="{{ route('prestations.destroy', $resultat->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Supprimer</button>
            </form>
        </td>
    @endif

    </tr>


    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap2)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $resultat->quantite2 }}</td>
        <td class="table-primary">{{ $resultat->type2 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap2)
                    {{ $ap->prix_public_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap2)
                {{ $ap->prix_public_ap * $resultat->quantite2 }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap3)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $resultat->quantite3 }}</td>
        <td class="table-primary">{{ $resultat->type3 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap3)
                    {{ $ap->prix_public_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap3)
                {{ $ap->prix_public_ap * $resultat->quantite3 }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap4)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $resultat->quantite4 }}</td>
        <td class="table-primary">{{ $resultat->type4 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap4)
                    {{ $ap->prix_public_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap4)
                {{ $ap->prix_public_ap * $resultat->quantite4 }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap5)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $resultat->quantite5 }}</td>
        <td class="table-primary">{{ $resultat->type5 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap5)
                    {{ $ap->prix_public_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap5)
                {{ $ap->prix_public_ap * $resultat->quantite5 }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap6)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $resultat->quantite6 }}</td>
        <td class="table-primary">{{ $resultat->type6 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap6)
                    {{ $ap->prix_public_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap6)
                {{ $ap->prix_public_ap * $resultat->quantite6 }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap7)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $resultat->quantite7 }}</td>
        <td class="table-primary">{{ $resultat->type7 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap7)
                    {{ $ap->prix_public_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap7)
                {{ $ap->prix_public_ap * $resultat->quantite7 }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap8)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $resultat->quantite8 }}</td>
        <td class="table-primary">{{ $resultat->type8 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap8)
                    {{ $ap->prix_public_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap8)
                {{ $ap->prix_public_ap * $resultat->quantite8 }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap9)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $resultat->quantite9 }}</td>
        <td class="table-primary">{{ $resultat->type9 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap9)
                    {{ $ap->prix_public_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap9)
                {{ $ap->prix_public_ap * $resultat->quantite9 }}
                @endif
            @endforeach
        </td>

    </tr>

    <tr>

        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap10)
                    {{ $ap->designation_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">{{ $resultat->quantite10 }}</td>
        <td class="table-primary">{{ $resultat->type10 }}</td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap10)
                    {{ $ap->prix_public_ap }}
                @endif
            @endforeach
        </td>
        <td class="table-primary">
            @foreach ($aps as $ap)
                @if ($ap->code_ap === $resultat->ap10)
                {{ $ap->prix_public_ap * $resultat->quantite10 }}
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
