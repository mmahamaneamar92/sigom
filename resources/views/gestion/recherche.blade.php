<!-- index.blade.php -->

<x-app-layout>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <link rel="canonical"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css">

<div>
    @include('gestion.create')
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
        @foreach ($resultats as $resultat)
        <tr>
            <td class="table-primary">{{ $resultat->type }}</td>
            <td class="table-primary">
                @foreach ($users as $user)
                @if ($user->email === $resultat->users_email)
                {{ $user->name }}
                @endif @endforeach
            </td>
            <td class="table-primary">
    {{ $resultat->materiels_designation }}</td>
    <td class="table-primary">{{ $resultat->quantite }}</td>
    <td class="table-primary">{{ $resultat->commentaire }}</td>
    <td class="table-primary">{{ $resultat->created_at }}</td>

    @if (Auth::user()->name === 'Admin')
        <td><a href="{{ route('gestions.edit', $resultat->id) }}" class="btn btn-primary">Modifier</a></td>
        <td>
            <form action="{{ route('gestions.destroy', $resultat->id) }}" method="post">
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

    </div>
</x-app-layout>
