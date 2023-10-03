<!-- index.blade.php -->

<x-app-layout>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <link rel="canonical"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css">

<div>
    @include('reparation.create')
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
          <td class="table-primary">Matériel</td>
          <td class="table-primary">Quantité</td>
          <td class="table-primary">Commentaire</td>
          <td class="table-primary">Date</td>
          <td class="table-primary" colspan="2" align="center"></td>
        </tr>
    </thead>

    <tbody>
        @foreach ($reparations as $reparation)
        <tr>
            <td class="table-primary">
    {{ $reparation->materiels_designation }}</td>
    <td class="table-primary">{{ $reparation->quantite }}</td>
    <td class="table-primary">{{ $reparation->commentaire }}</td>
    <td class="table-primary">{{ $reparation->created_at }}</td>

    <td><a href="{{ route('reparations.edit', $reparation->id) }}" class="btn btn-primary">Modifier</a></td>

    @if (Auth::user()->name === 'Admin')

        <td>
            <form action="{{ route('reparations.destroy', $reparation->id) }}" method="post">
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
        {{ $reparations->links() }}
    </div>
    <div class="flex items-center justify-end mt-4">

    </div>
</x-app-layout>
