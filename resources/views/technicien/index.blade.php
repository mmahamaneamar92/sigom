<!-- index.blade.php -->

<x-app-layout>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <link rel="canonical"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css">

<div>
    @include('technicien.create')
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

<div>
    <x-text-input id="search" type="text" placeholder="Recherche..."/>
</div>

<div class="flex items-center justify-end mt-4">

  <table id="tableau" class="table table-striped">

    <thead>
        <tr>
          <td class="table-primary">Code</td>
          <td class="table-primary">Prénom</td>
          <td class="table-primary">Nom</td>

          <td class="table-primary" colspan="2" align="center"></td>

        </tr>
    </thead>

    <tbody>
        @php
        $totalQuantite = 0;
        @endphp
        @foreach ($techniciens as $technicien)
        <tr>
            <td class="table-primary">{{ $technicien->code_technicien }}</td>
            <td class="table-primary">{{ $technicien->prenom_technicien }}</td>
            <td class="table-primary">{{ $technicien->nom_technicien }}</td>


<td><a href="{{ route('techniciens.edit', $technicien->id) }}"
        class="btn btn-primary">Modifier</a></td>

    <td>
        <form action="{{ route('techniciens.destroy', $technicien->id) }}" method="post">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit">Supprimer</button>
        </form>
    </td>

    </tr> @endforeach
    </tbody>
    </table>
    </div>
    <div class="flex
        items-center justify-end mt-4">
    {{ $techniciens->links() }}
    </div>
    <div class="flex items-center justify-end mt-4">

    </div>
</x-app-layout>
