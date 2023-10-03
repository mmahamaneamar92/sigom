<!-- index.blade.php -->

<x-app-layout>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <link rel="canonical"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css">

<div>
    @include('prestation.create')
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
<!--
<div>
    <form action="{{ route('prestRech') }}" method="GET">
        <div>
            <div>
                <label for="assure">N° Assure</label>
                <input type="text" class="form-control" name="assure" id="assure">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Rechercher</button>
    </form>
</div>

<div>
    <form action="{{ route('generate-pdf') }}" method="GET">
        <div>
            <div>
                <x-input-label for="start_date" :value="__('Date de début')" />
                <input type="date" class="form-control" name="start_date" id="start_date">
            </div>
            <div>
                <x-input-label for="end_date" :value="__('Date de fin')" />
                <input type="date" class="form-control" name="end_date" id="end_date">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Exporter En PDF</button>
    </form>
</div>
-->
<div class="input-group">
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Inserez la date de matériel que vous voulez extraire.') }}
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

    <x-input-label class="input-group-text" for="type" :value="__('Type')" />
        <select name="type" class="form-control">
            <option value="Entrer">ENTRER</option>
            <option value="Sortie">SORTIE</option>
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />

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
          <td class="table-primary">Type</td>
          <td class="table-primary">Nom</td>
          <td class="table-primary">Matériel</td>
          <td class="table-primary">Quantité</td>
          <td class="table-primary">Commentaire</td>
          <td class="table-primary">Date</td>
          @if (Auth::user()->name === 'Admin')
          <td class="table-primary" colspan="2" align="center"></td>
          @endif
        </tr>
    </thead>

    <tbody>
        @foreach ($usersMateriels as $userMateriel)
        <tr>
            <td class="table-primary">{{ $userMateriel->type }}</td>
            <td class="table-primary">
                @foreach ($users as $user)
                @if ($user->email === $userMateriel->users_email)
                {{ $user->name }}
                @endif @endforeach
            </td>
            <td class="table-primary">
    {{ $userMateriel->materiels_designation }}</td>
    <td class="table-primary">{{ $userMateriel->quantite }}</td>
    <td class="table-primary">{{ $userMateriel->commentaire }}</td>
    <td class="table-primary">{{ $userMateriel->created_at }}</td>

    <td><a href="{{ route('gestions.edit', $userMateriel->id) }}" class="btn btn-primary">Modifier</a></td>

    @if (Auth::user()->name === 'Admin')
        <td>
            <form action="{{ route('gestions.destroy', $userMateriel->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Supprimer</button>
            </form>
        </td>
    @endif

    </tr>
    @endforeach
    </tbody>
    </table>
    </div>
    <div class="flex items-center justify-end mt-4">
        {{ $usersMateriels->links() }}
    </div>
    <div class="flex items-center justify-end mt-4">

    </div>
</x-app-layout>
