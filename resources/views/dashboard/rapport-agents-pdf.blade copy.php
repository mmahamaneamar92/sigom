<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>
        Rapport des missions par agent
    </title>

    <style>

        @page {
            margin: 35px 30px 40px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 13px;
            margin-top: 25px;
            margin-bottom: 8px;
            background: #eeeeee;
            padding: 7px;
        }

        .entete {
            text-align: center;
            margin-bottom: 20px;
        }

        .entete .titre {
            font-size: 16px;
            font-weight: bold;
        }

        .entete .sous-titre {
            font-size: 11px;
            margin-top: 5px;
        }

        .periode {
            text-align: center;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .resume {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .resume td {
            border: 1px solid #cccccc;
            padding: 8px;
            text-align: center;
        }

        .resume .valeur {
            font-size: 14px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #198754;
            color: white;
            font-weight: bold;
            padding: 6px;
            border: 1px solid #157347;
        }

        td {
            padding: 5px;
            border: 1px solid #cccccc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total {
            font-weight: bold;
            background: #eeeeee;
        }

        .rang {
            text-align: center;
            font-weight: bold;
            width: 35px;
        }

        .montant {
            text-align: right;
            white-space: nowrap;
        }

        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }

    </style>

</head>

<body>

    {{-- =========================================================
         EN-TÊTE
         ========================================================= --}}

    <div class="entete">

        <div class="titre">
            CAISSE NATIONALE D'ASSURANCE MALADIE
        </div>

        <div class="sous-titre">
            CANAM
        </div>

        <br>

        <h1>
            RAPPORT DES MISSIONS DES AGENTS
        </h1>

        <div class="periode">

            Période du
            <strong>
                {{ $dateDebut->format('d/m/Y') }}
            </strong>

            au

            <strong>
                {{ $dateFin->format('d/m/Y') }}
            </strong>

        </div>

    </div>


    {{-- =========================================================
         RÉSUMÉ
         ========================================================= --}}

    <table class="resume">

        <tr>

            <td>

                <div>
                    Nombre d'agents
                </div>

                <div class="valeur">
                    {{ $agents->count() }}
                </div>

            </td>

            <td>

                <div>
                    Nombre de missions
                </div>

                <div class="valeur">
                    {{ number_format($nombreMissions, 0, ',', ' ') }}
                </div>

            </td>

            <td>

                <div>
                    Montant total versé
                </div>

                <div class="valeur">
                    {{ number_format($montantTotal, 0, ',', ' ') }}
                    FCFA
                </div>

            </td>

        </tr>

    </table>


    {{-- =========================================================
         TABLEAU DES AGENTS
         ========================================================= --}}

    <h2>
        I. CLASSEMENT DES AGENTS PAR MONTANT TOTAL PERÇU
    </h2>

    <table>

        <thead>

            <tr>

                <th class="rang">
                    N°
                </th>

                <th>
                    Matricule
                </th>

                <th>
                    Agent
                </th>

                <th>
                    Fonction
                </th>

                <th>
                    Service
                </th>

                <th>
                    Direction
                </th>

                <th>
                    Nombre de missions
                </th>

                <th>
                    Montant total perçu
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($agents as $index => $agent)

                <tr>

                    <td class="rang">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $agent->matricule ?? '-' }}
                    </td>

                    <td>
                        {{ $agent->prenom }}
                        {{ $agent->nom }}
                    </td>

                    <td>
                        {{ $agent->fonction ?? '-' }}
                    </td>

                    <td>
                        {{ $agent->nom_service ?? '-' }}
                    </td>

                    <td>
                        {{ $agent->nom_direction ?? 'Direction non renseignée' }}
                    </td>

                    <td class="text-center">
                        {{ number_format($agent->nombre_missions, 0, ',', ' ') }}
                    </td>

                    <td class="montant">

                        {{ number_format(
                            (float) $agent->montant_total,
                            0,
                            ',',
                            ' '
                        ) }}

                        FCFA

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="8"
                        class="text-center"
                    >
                        Aucun agent n'a effectué de mission
                        sur cette période.
                    </td>

                </tr>

            @endforelse


            @if($agents->count() > 0)

                <tr class="total">

                    <td colspan="6" class="text-right">
                        TOTAL
                    </td>

                    <td class="text-center">
                        {{ number_format(
                            $nombreMissions,
                            0,
                            ',',
                            ' '
                        ) }}
                    </td>

                    <td class="montant">

                        {{ number_format(
                            $montantTotal,
                            0,
                            ',',
                            ' '
                        ) }}

                        FCFA

                    </td>

                </tr>

            @endif

        </tbody>

    </table>


    {{-- =========================================================
         TABLEAU PAR DIRECTION
         ========================================================= --}}

    <h2>
        II. RÉPARTITION DES MISSIONS ET DES MONTANTS PAR DIRECTION
    </h2>

    <table>

        <thead>

            <tr>

                <th class="rang">
                    N°
                </th>

                <th>
                    Direction
                </th>

                <th>
                    Nombre d'agents
                </th>

                <th>
                    Nombre de missions
                </th>

                <th>
                    Montant total versé
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($directions as $index => $direction)

                <tr>

                    <td class="rang">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $direction->nom_direction }}
                    </td>

                    <td class="text-center">
                        {{ number_format(
                            $direction->nombre_agents,
                            0,
                            ',',
                            ' '
                        ) }}
                    </td>

                    <td class="text-center">
                        {{ number_format(
                            $direction->nombre_missions,
                            0,
                            ',',
                            ' '
                        ) }}
                    </td>

                    <td class="montant">

                        {{ number_format(
                            (float) $direction->montant_total,
                            0,
                            ',',
                            ' '
                        ) }}

                        FCFA

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="5"
                        class="text-center"
                    >
                        Aucune donnée disponible
                        pour cette période.
                    </td>

                </tr>

            @endforelse


            @if($directions->count() > 0)

                <tr class="total">

    <td colspan="2" class="text-right">
        TOTAL
    </td>

    <td class="text-center">

        {{ number_format(
            $nombreAgentsDirections,
            0,
            ',',
            ' '
        ) }}

    </td>

    <td class="text-center">

        {{ number_format(
            $nombreParticipationsDirections,
            0,
            ',',
            ' '
        ) }}

    </td>

    <td class="montant">

        {{ number_format(
            $montantTotalDirections,
            0,
            ',',
            ' '
        ) }}

        FCFA

    </td>

</tr>

            @endif

        </tbody>

    </table>


    <div class="footer">

        Rapport généré le
        {{ now()->format('d/m/Y à H:i') }}

    </div>

</body>

</html>