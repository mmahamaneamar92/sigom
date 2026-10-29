<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>
        Budget {{ $mission->numero }}
    </title>

    <style>

        @page {
            margin: 18px 22px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header td {
            text-align: center;
            vertical-align: top;
            font-weight: bold;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin: 15px 0 8px 0;
            text-transform: uppercase;
        }

        .objet {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .info {
            margin-bottom: 10px;
        }

        .budget th,
        .budget td {
            border: 1px solid #000;
            padding: 4px;
        }

        .budget th {
            text-align: center;
            font-weight: bold;
        }

        .section {
            font-weight: bold;
            font-size: 11px;
            margin: 7px 0 4px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .total {
            font-weight: bold;
        }

        .grand-total {
            border: 2px solid #000;
            padding: 6px;
            font-weight: bold;
            font-size: 11px;
            margin-top: 8px;
        }

        .signatures {
            margin-top: 35px;
        }

        .signatures td {
            width: 25%;
            text-align: center;
            vertical-align: top;
            height: 70px;
            font-weight: bold;
        }

    </style>

</head>

<body>

<table class="header">

    <tr>

        <td style="width: 50%;">

            REPUBLIQUE DU MALI<br>

            Un Peuple - Un But - Une Foi<br><br>

            MINISTERE DE LA SANTE<br>
            ET DU DEVELOPPEMENT SOCIAL<br><br>

            CAISSE NATIONALE D'ASSURANCE MALADIE<br>
            CANAM

        </td>

        <td style="width: 50%;">

            DIRECTION GENERALE<br><br>

            DIRECTION DE L'ADMINISTRATION<br>
            ET DES RESSOURCES HUMAINES

        </td>

    </tr>

</table>


<div class="title">

    BUDGET RELATIF A L'ORDRE DE MISSION
    N° {{ $mission->numero }}

</div>


<div class="objet">

    OBJET DE LA MISSION :
    {{ strtoupper($mission->objet) }}

</div>


<div class="info">

    <strong>DUREE :</strong>
    {{ $duree }} jours

    &nbsp;&nbsp;&nbsp;&nbsp;

    <strong>DU :</strong>
    {{ $dateDepart ? $dateDepart->format('d-m-Y') : '-' }}

    &nbsp;&nbsp;

    <strong>AU :</strong>
    {{ $dateRetour ? $dateRetour->format('d-m-Y') : '-' }}

    <br><br>

    <strong>CF :</strong>
    {{ $mission->ce ?? '-' }}

</div>


{{-- =========================================================
     I - FRAIS ET INDEMNITES
========================================================= --}}

<div class="section">
    I - FRAIS ET INDEMNITES
</div>

<table class="budget">

    <thead>

        <tr>

            <th rowspan="2">N°</th>

            <th rowspan="2">
                PRENOM ET NOM
            </th>

            <th colspan="2">
                FRAIS DE MISSION
            </th>

            <th colspan="2">
                INDEMNITES DE MISSION
            </th>

            <th rowspan="2">
                TOTAL
            </th>

        </tr>

        <tr>

            <th>
                MONTANT/JOUR
            </th>

            <th>
                NBRE JRS
            </th>

            <th>
                MONTANT/NUITEE
            </th>

            <th>
                NBRE NUITEES
            </th>

        </tr>

    </thead>

    <tbody>

        @forelse($participants as $index => $missionAgent)

            <tr>

                <td class="center">
                    {{ $index + 1 }}
                </td>

                <td>

                    @if($missionAgent->agent)

                        M.
                        {{ strtoupper($missionAgent->agent->prenom) }}
                        {{ strtoupper($missionAgent->agent->nom) }}

                    @else

                        Participant {{ $index + 1 }}

                    @endif

                </td>

                <td class="right">

                    {{ number_format(
                        $missionAgent->frais_journalier ?? 0,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>

                <td class="center">

                    {{ $missionAgent->pdf_nombre_jours }}

                </td>

                <td class="right">

                    {{ number_format(
                        $missionAgent->indemnite_journaliere ?? 0,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>

                <td class="center">

                    {{ $missionAgent->pdf_nombre_nuitees }}

                </td>

                <td class="right bold">

                    {{ number_format(
                        $missionAgent->pdf_total,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7" class="center">
                    Aucun participant.
                </td>

            </tr>

        @endforelse


        <tr class="total">

            <td colspan="2" class="right">
                SOUS-TOTAL 1
            </td>

            <td class="right">
                {{ number_format(
                    $totalFraisMission,
                    0,
                    ',',
                    ' '
                ) }}
            </td>

            <td></td>

            <td class="right">
                {{ number_format(
                    $totalIndemnites,
                    0,
                    ',',
                    ' '
                ) }}
            </td>

            <td></td>

            <td class="right">
                {{ number_format(
                    $totalParticipants,
                    0,
                    ',',
                    ' '
                ) }}
            </td>

        </tr>

    </tbody>

</table>


<div class="section">
    II - CARBURANT
</div>

<table class="budget">

    <thead>
        <tr>
            <th>
                DESIGNATION
            </th>

            <th>
                NBRE DE VEHICULES
            </th>

            <th>
                QTE DE CARBURANT
            </th>

            <th>
                PRIX DU LITRE
            </th>

            <th>
                TOTAL
            </th>
        </tr>
    </thead>

    <tbody>

        @if($carburant)

            <tr>

                <td>
                    MONTANT CARBURANT
                </td>

                <td class="center">
                    {{ $nombreVehicules }}
                </td>

                <td class="center">
                    {{ number_format(
                        $quantiteCarburant,
                        0,
                        ',',
                        ' '
                    ) }}
                </td>

                <td class="right">
                    {{ number_format(
                        $prixLitre,
                        0,
                        ',',
                        ' '
                    ) }}
                </td>

                <td class="right bold">
                    {{ number_format(
                        $montantCarburant,
                        0,
                        ',',
                        ' '
                    ) }}
                </td>

            </tr>

            <tr>

                <td>
                    MONTANT CARBURANT VILLE
                </td>

                <td class="center">
                    {{ $nombreVehicules }}
                </td>

                <td class="center">
                    {{ number_format(
                        $quantiteCarburantVille,
                        0,
                        ',',
                        ' '
                    ) }}
                </td>

                <td class="right">
                    {{ number_format(
                        $prixLitre,
                        0,
                        ',',
                        ' '
                    ) }}
                </td>

                <td class="right bold">
                    {{ number_format(
                        $montantCarburantVille,
                        0,
                        ',',
                        ' '
                    ) }}
                </td>

            </tr>

        @else

            <tr>
                <td colspan="5" class="center">
                    Aucun budget carburant enregistré.
                </td>
            </tr>

        @endif

        <tr class="total">

            <td colspan="4" class="right">
                SOUS-TOTAL 2
            </td>

            <td class="right">
                {{ number_format(
                    $totalCarburant,
                    0,
                    ',',
                    ' '
                ) }}
            </td>

        </tr>

    </tbody>

</table>


{{-- =========================================================
     TOTAL GENERAL
========================================================= --}}

<div class="grand-total">

    TOTAL GENERAL :

    <span style="float:right;">

        {{ number_format(
            $totalGeneral,
            0,
            ',',
            ' '
        ) }}

        FCFA

    </span>

</div>


{{-- =========================================================
     MONTANT EN LETTRES
========================================================= --}}

<div style="margin-top: 10px;">

    <strong>
        ARRÊTÉ LE PRÉSENT BUDGET À LA SOMME DE :
    </strong>

    {{ $totalGeneral }} FCFA

</div>


{{-- =========================================================
     SIGNATURES
========================================================= --}}

<table class="signatures">

    <tr>

        <td>
            LA DIRECTION<br>
            EN BUDGET
        </td>

        <td>
            CHEF SERVICE<br>
            FINANCES ET TRÉSOR
        </td>

        <td>
            L'AGENT COMPTABLE
        </td>

        <td>
            LE DIRECTEUR GENERAL
        </td>

    </tr>

</table>

</body>

</html>