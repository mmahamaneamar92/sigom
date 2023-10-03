<!-- index.blade.php -->

<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <link rel="canonical"
        href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.1/css/fontawesome.min.css">

<div>
    @include('materiel.create')
</div>

<div class="flex items-center justify-end mt-4">
    @if (session()->get('success'))
      <div class="alert alert-success">
        {{ session()->get('success') }}
      </div><br />
    @endif
  </div>

  <script>
    $(document).ready(function()) {
    $("#search").on("keyup", function) {
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
          <td class="table-primary">Désignation</td>
          <td class="table-primary">Quantité</td>
          @if (Auth::user()->name === 'Admin')
          <td class="table-primary" colspan="2" align="center"></td>
          @endif
        </tr>
    </thead>

    <tbody>
        @php
        $totalQuantite = 0;
        @endphp
        @foreach ($materiels as $materiel)
        <tr>
            <td class="table-primary">{{ $materiel->designation }}</td>

            <td class="table-primary">

                @foreach ($usersMateriel as $userMateriels)
@if ($materiel->designation === $userMateriels->materiels_designation)
{{ $userMateriels->total_quantite }}
@endif @endforeach


                </td>
                @if (Auth::user()->name === 'Admin')
<td><a href="{{ route('materiels.edit', $materiel->id) }}"
        class="btn btn-primary">Modifier</a></td>

    <td>
        <form action="{{ route('materiels.destroy', $materiel->id) }}" method="post">
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
        {{ $materiels->links() }}
    </div>
    <div class="flex items-center justify-end mt-4">

    </div>
</x-app-layout>
