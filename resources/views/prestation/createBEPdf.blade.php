<!DOCTYPE html>
<html>

<head>
    <title>Fichier PDF de la gestion des materiels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <p>

    </p>
    <p>

    </p>
    <p>

    </p>
    <p>

    </p>
    <p>

    </p>
    <p>

    </p>
    <p>

    </p>
    <p>

    </p>
</head>

<body>
    <div class="flex items-center justify-end mt-4">
        <p align="right">Bamako, le {{ $date }}</p>
        <p>

        </p>
        <h5 align="right" class="custom-font"> {{ $expediteur }}</h5>
        <p>

        </p>
        <h5 align="center">A</h5>
        <p>

        </p>
        <h5 align="left">N°2023/{{$numero_bordereau}}/MDSDS/CANAM-DSI</h5>
        <p>

        </p>
        <h5 align="right"> {{ $destination }}</h5>
        <p>

        </p>
        <p>

        </p>
        <h3 align="center">{{ $title }} {{ $type_bordereau }}</h3>
        <p>

        </p>
    </div>
    <div class="flex items-center justify-end mt-4">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <td class="table-primary"><h3 align="center">Matériel</h3></td>
                    <td class="table-primary"><h3 align="center">Quantité</h3></td>
                    <td class="table-primary"><h3 align="center">Observation</h3></td>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td class="table-primary"><h4 align="center">{{ $materiels_designation }}</h4></td>
                    <td class="table-primary"><h4 align="center">{{ $quantite }}</h4></td>
                    <td align="center" rowspan="5" class="table-primary"><h3 align="center">{{ $commentaire }}</h3></td>
                </tr>

                <tr>
                    <td class="table-primary"><h4 align="center">{{ $materiels_designation2 }}</h4></td>
                    <td class="table-primary"><h4 align="center">{{ $quantite2 }}</h4></td>
                </tr>

                <tr>
                    <td class="table-primary"><h4 align="center">{{ $materiels_designation3 }}</h4></td>
                    <td class="table-primary"><h4 align="center">{{ $quantite3 }}</h4></td>
                </tr>

                <tr>
                    <td class="table-primary"><h4 align="center">{{ $materiels_designation4 }}</h4></td>
                    <td class="table-primary"><h4 align="center">{{ $quantite4 }}</h4></td>
                </tr>

                <tr>
                    <td class="table-primary"><h4 align="center">{{ $materiels_designation5 }}</h4></td>
                    <td class="table-primary"><h4 align="center">{{ $quantite5 }}</h4></td>
                </tr>

                <tr>
                    <td class="table-primary"><h4 align="center">Total</h4></td>
                    <td colspan="2" class="table-primary"><h4 align="center">{{ $total }}</h4></td>
                </tr>

            </tbody>
        </table>
        <div class="flex items-center justify-end mt-4">
            <p>

            </p>
            <p>

            </p>
            <h4 align="right">LE DIRECTEUR</h4>
            <p>

            </p>
            <p>

            </p>
            <p>

            </p>
            <h5 align="right">Amadou COULIBALY</h5>
        </div>
    </div>

    <div class="flex items-center justify-end mt-4">

    </div>
</body>

</html>
