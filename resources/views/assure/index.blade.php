<!-- index.blade.php -->

<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <link rel="canonical"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css">

<div>
    @include('assure.create')
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
          <td class="table-primary">N° AMO</td>
          <td class="table-primary">Prénom</td>
          <td class="table-primary">Nom</td>
          <td class="table-primary">Téléphone</td>
          <td class="table-primary">OGD</td>

          <td class="table-primary" colspan="2" align="center"></td>

        </tr>
    </thead>

    <tbody>

        @foreach ($assures as $assure)
        <tr>
            <td class="table-primary">{{ $assure->numero_assurer }}</td>
            <td class="table-primary">{{ $assure->prenom }}</td>
            <td class="table-primary">{{ $assure->nom }}</td>
            <td class="table-primary">{{ $assure->tel }}</td>
            <td class="table-primary">{{ $assure->ogd }}</td>


<td><a href="{{ route('assures.edit', $assure->id) }}"
        class="btn btn-primary">Modifier</a></td>

    <td>
        <form action="{{ route('assures.destroy', $assure->id) }}" method="post">
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
    {{ $assures->links() }}
    </div>
    <div class="flex items-center justify-end mt-4">

    </div>
</x-app-layout>
