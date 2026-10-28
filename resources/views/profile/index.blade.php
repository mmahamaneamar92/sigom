<!-- index.blade.php -->
@extends('layouts.ap')
@section('content')
<div>
    @include('auth.register')
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
    <x-text-input id="search" type="text" placeholder="Recherche..." />
</div>

<div class="flex items-center justify-end mt-4">
    @if (session()->get('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div><br />
    @endif
</div>

<div class="flex items-center justify-end mt-4">

    <table id="tableau" class="table table-striped">

        <thead>
            <tr>
                <td class="table-primary">Nom</td>
                <td class="table-primary">Email</td>
                <td class="table-primary">Profil</td>
                <td class="table-primary">Direction</td>

                <td class="table-primary" colspan="2" align="center"></td>

            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="table-primary">{{ $user->name }}</td>
                    <td class="table-primary">{{ $user->email }}</td>
                    <td class="table-primary">{{ $user->profil }}</td>

                    
                        <td class="table-primary">
                            @if ($user->direction_id)
                            {{ $user->direction->nom_direction }}
                    @endif
                    </td>

                    <td><a href="{{ route('utilisateurs.edit', $user->id) }}" class="btn btn-primary">Modifier</a></td>
                    <td>
                        <form action="{{ route('profiles.destroy', $user->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Supprimer</button>
                        </form>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="flex
    items-center justify-end mt-4">
    {{ $users->links() }}
</div>
<div class="flex items-center justify-end mt-4">

</div>

@endsection
