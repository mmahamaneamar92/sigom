<!DOCTYPE html>
<html>

<head>
    <title>Fichier PDF de la gestion des materiels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <div>
        <img class="w-20 h-20 fill-current text-gray-500" src="{{ public_path('img/canam.jpg') }}">
    </div>
</head>

<body>
    <div class="flex items-center justify-end mt-4">
        <h1 align="center">{{ $title }}</h1>
        <h5 align="center">Du {{ $start_date }} Au {{ $end_date }}</h5>
        <p>

        </p>
    </div>
    <div class="flex items-center justify-end mt-4">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <td class="table-primary">Type</td>
                    <td class="table-primary">Nom</td>
                    <td class="table-primary">Matériel</td>
                    <td class="table-primary">Quantité</td>
                    <td class="table-primary">Commentaire</td>
                    <td class="table-primary">Date</td>
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
                                @endif
                            @endforeach
                        </td>
                        <td class="table-primary">
                            {{ $resultat->materiels_designation }}</td>
                        <td class="table-primary">{{ $resultat->quantite }}</td>
                        <td class="table-primary">{{ $resultat->commentaire }}</td>
                        <td class="table-primary">{{ $resultat->created_at }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="flex items-center justify-end mt-4">
            <p align="right">Bamako, le {{ $date }}</p>
            <p>

            </p>
        </div>
    </div>

    <div class="flex items-center justify-end mt-4">

    </div>
</body>

</html>
