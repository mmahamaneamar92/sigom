<!DOCTYPE html>
<html>

<head>

    <title>Fichier PDF de la gestion des materiels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <div>
        <img class="w-20 h-20 fill-current text-gray-500" src="{{ public_path('img/canam.jpg') }}">
    </div>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @page {
            size: landscape;
        }
    </style>

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
                    <td class="table-primary">Assuré</td>
                    <td class="table-primary">Information</td>
                    <td class="table-primary">Provenance / Préscripteur</td>
                    <td class="table-primary">Contrôleur</td>
                    <td class="table-primary">Acte ou prestation</td>
                    <td class="table-primary">Quantité</td>
                    <td class="table-primary">Type</td>
                    <td class="table-primary">Prix publique unitaire</td>
                    <td class="table-primary">Prix publique total</td>
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
                                @endif
                            @endforeach
                        </td>
                        <td rowspan="10" class="table-primary">
                            {{ $prestation->date }} <Br></Br>
                            N° Feuille : {{ $prestation->feuille }} <Br></Br>
                            Nature Examen : {{ $prestation->nature_examen }} <Br></Br>
                            Renseignement Clinique : {{ $prestation->renseignement }} <Br></Br>
                        </td>
                        <td rowspan="10" class="table-primary">
                            @foreach ($hopitals as $hopital)
                                @if ($hopital->code_hopital === $prestation->provenance)
                                    Provénance : {{ $hopital->nom_hopital }} <Br></Br>
                                @endif
                            @endforeach
                            @foreach ($prescripteurs as $prescripteur)
                                @if ($prescripteur->code_prescripteur === $prestation->prescripteur)
                                    Préscripteur : {{ $prescripteur->prenom_prescripteur }}
                                    {{ $prescripteur->nom_prescripteur }} <Br></Br>
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
