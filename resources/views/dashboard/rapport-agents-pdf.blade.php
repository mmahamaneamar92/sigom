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

        /*
        |--------------------------------------------------------------------------
        | TITRES
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | EN-TÊTE
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | PÉRIODE
        |--------------------------------------------------------------------------
        */

        .periode {
            text-align: center;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .periode .type-mission {
            margin-top: 5px;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | TABLEAU RÉSUMÉ
        |--------------------------------------------------------------------------
        */

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

        .resume .libelle {
            font-size: 10px;
        }

        .resume .valeur {
            font-size: 14px;
            font-weight: bold;
            margin-top: 3px;
        }

        /*
        |--------------------------------------------------------------------------
        | TABLEAUX
        |--------------------------------------------------------------------------
        */

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
            text-align: center;
            vertical-align: middle;
        }

        td {
            padding: 5px;
            border: 1px solid #cccccc;
            vertical-align: middle;
        }

        /*
        |--------------------------------------------------------------------------
        | ALIGNEMENTS
        |--------------------------------------------------------------------------
        */

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        .total {
            font-weight: bold;
            background: #eeeeee;
        }

        /*
        |--------------------------------------------------------------------------
        | RANG
        |--------------------------------------------------------------------------
        */

        .rang {
            text-align: center;
            font-weight: bold;
            width: 35px;
        }

        /*
        |--------------------------------------------------------------------------
        | MONTANTS
        |--------------------------------------------------------------------------
        */

        .montant {
            text-align: right;
            white-space: nowrap;
        }

        /*
        |--------------------------------------------------------------------------
        | BADGE TYPE
        |--------------------------------------------------------------------------
        */

        .type-badge {
            display: inline-block;
            padding: 4px 8px;
            font-weight: bold;
            border: 1px solid #198754;
            color: #198754;
        }

        /*
        |--------------------------------------------------------------------------
        | PIED DE PAGE
        |--------------------------------------------------------------------------
        */

        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }

        /*
        |--------------------------------------------------------------------------
        | INFORMATIONS COMPLÉMENTAIRES
        |--------------------------------------------------------------------------
        */

        .note {
            margin-top: 10px;
            font-size: 8px;
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

            <div class="type-mission">

                Type de mission :

                <strong>
                    {{ $libelleTypeMission }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =========================================================
         RÉSUMÉ
         ========================================================= --}}

    <table class="resume">

        <tr>

            {{-- NOMBRE D'AGENTS --}}

            <td>

                <div class="libelle">
                    Nombre d'agents
                </div>

                <div class="valeur">
                    {{ number_format(
                        $agents->count(),
                        0,
                        ',',
                        ' '
                    ) }}
                </div>

            </td>


            {{-- NOMBRE DE MISSIONS / PARTICIPATIONS --}}

            <td>

                <div class="libelle">
                    Nombre de missions
                </div>

                <div class="valeur">
                    {{ number_format(
                        $nombreMissions,
                        0,
                        ',',
                        ' '
                    ) }}
                </div>

            </td>


            {{-- MONTANT TOTAL --}}

            <td>

                <div class="libelle">
                    Montant total versé
                </div>

                <div class="valeur">

                    {{ number_format(
                        (float) $montantTotal,
                        0,
                        ',',
                        ' '
                    ) }}

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

                <th style="width: 11%;">
                    Matricule
                </th>

                <th style="width: 18%;">
                    Agent
                </th>

                <th style="width: 14%;">
                    Fonction
                </th>

                <th style="width: 14%;">
                    Service
                </th>

                <th style="width: 14%;">
                    Direction
                </th>

                <th style="width: 9%;">
                    Nombre de missions
                </th>

                <th style="width: 15%;">
                    Montant total perçu
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($agents as $index => $agent)

                <tr>

                    {{-- N° --}}

                    <td class="rang">
                        {{ $index + 1 }}
                    </td>


                    {{-- MATRICULE --}}

                    <td>
                        {{ $agent->matricule ?? '-' }}
                    </td>


                    {{-- AGENT --}}

                    <td>

                        {{ $agent->prenom ?? '' }}

                        {{ $agent->nom ?? '' }}

                    </td>


                    {{-- FONCTION --}}

                    <td>
                        {{ $agent->poste ?? '-' }}
                    </td>


                    {{-- SERVICE --}}

                    <td>
                        {{ $agent->nom_service ?? '-' }}
                    </td>


                    {{-- DIRECTION --}}

                    <td>
                        {{ $agent->nom_direction
                            ?? 'Direction non renseignée' }}
                    </td>


                    {{-- NOMBRE DE MISSIONS --}}

                    <td class="text-center">

                        {{ number_format(
                            (int) $agent->nombre_missions,
                            0,
                            ',',
                            ' '
                        ) }}

                    </td>


                    {{-- MONTANT --}}

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


            {{-- =================================================
                 TOTAL AGENTS
                 ================================================= --}}

            @if($agents->count() > 0)

                <tr class="total">

                    <td
                        colspan="6"
                        class="text-right"
                    >
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
                            (float) $montantTotal,
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
        II. RÉPARTITION DES PARTICIPATIONS ET DES MONTANTS PAR DIRECTION
    </h2>

    <table>

        <thead>

            <tr>

                <th class="rang">
                    N°
                </th>

                <th style="width: 40%;">
                    Direction
                </th>

                <th style="width: 18%;">
                    Nombre d'agents
                </th>

                <th style="width: 18%;">
                    Participations aux missions
                </th>

                <th style="width: 18%;">
                    Montant total versé
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($directions as $index => $direction)

                <tr>

                    {{-- N° --}}

                    <td class="rang">
                        {{ $index + 1 }}
                    </td>


                    {{-- DIRECTION --}}

                    <td>
                        {{ $direction->nom_direction
                            ?? 'Direction non renseignée' }}
                    </td>


                    {{-- AGENTS --}}

                    <td class="text-center">

                        {{ number_format(
                            (int) $direction->nombre_agents,
                            0,
                            ',',
                            ' '
                        ) }}

                    </td>


                    {{-- PARTICIPATIONS --}}

                    <td class="text-center">

                        {{ number_format(
                            (int) $direction->nombre_missions,
                            0,
                            ',',
                            ' '
                        ) }}

                    </td>


                    {{-- MONTANT --}}

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


            {{-- =================================================
                 TOTAL DIRECTIONS
                 ================================================= --}}

            @if($directions->count() > 0)

                <tr class="total">

                    <td
                        colspan="2"
                        class="text-right"
                    >
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
                            (float) $montantTotalDirections,
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
         NOTE
         ========================================================= --}}

    <div class="note">

        <strong>Note :</strong>

        Le nombre de participations correspond au nombre
        d'affectations d'agents aux missions sur la période
        sélectionnée.

    </div>


    {{-- =========================================================
         PIED DE PAGE
         ========================================================= --}}

    <div class="footer">

        Rapport généré le

        {{ now()->format('d/m/Y à H:i') }}

    </div>


</body>

</html>