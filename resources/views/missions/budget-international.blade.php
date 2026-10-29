<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <title>
        Projet de budget - {{ $mission->numero }}
    </title>

    <style>

        @page {
            margin: 15px 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        /*
        |--------------------------------------------------------------------------
        | EN-TÊTE
        |--------------------------------------------------------------------------
        */

        .header {
            text-align: center;
            margin-bottom: 8px;
        }

        .header-title {
            font-size: 11px;
            font-weight: bold;
        }

        .document-title {
            font-size: 11px;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 8px;
        }

        /*
        |--------------------------------------------------------------------------
        | INFORMATIONS
        |--------------------------------------------------------------------------
        */

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 7px;
        }

        .info-table td {
            padding: 2px;
            vertical-align: top;
        }

        /*
        |--------------------------------------------------------------------------
        | TITRES SECTIONS
        |--------------------------------------------------------------------------
        */

        .section-title {
            font-weight: bold;
            font-size: 9px;
            margin-top: 6px;
            margin-bottom: 3px;
        }

        /*
        |--------------------------------------------------------------------------
        | TABLEAUX
        |--------------------------------------------------------------------------
        */

        table.budget {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        table.budget th,
        table.budget td {
            border: 1px solid #000;
            padding: 3px 2px;
            vertical-align: middle;
        }

        table.budget th {
            text-align: center;
            font-weight: bold;
            background: #f2f2f2;
        }

        .subtotal {
            font-weight: bold;
        }

        .total {
            font-weight: bold;
            font-size: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | SIGNATURES
        |--------------------------------------------------------------------------
        */

        .signatures {
            width: 100%;
            margin-top: 20px;
        }

        .signature {
            width: 25%;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            height: 45px;
        }

        .small {
            font-size: 7px;
        }

    </style>

</head>

<body>

    {{-- ================================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ================================================================= --}}

    <div class="header">

        <div class="header-title">
            MINISTÈRE DE LA SANTÉ ET DU DÉVELOPPEMENT SOCIAL
        </div>

        <div class="header-title">
            CAISSE NATIONALE D'ASSURANCE MALADIE
        </div>

        <div class="document-title">
            PROJET DE BUDGET RELATIF À LA LEVÉE D'ORDRE DE MISSION
            N° {{ $mission->numero }}
        </div>

    </div>


    {{-- ================================================================= --}}
    {{-- INFORMATIONS MISSION --}}
    {{-- ================================================================= --}}

    <table class="info-table">

        <tr>

            <td width="18%" class="bold">
                OBJET DE LA MISSION :
            </td>

            <td>
                {{ $mission->objet }}
            </td>

        </tr>


        <tr>

            <td class="bold">
                DESTINATION :
            </td>

            <td>

                {{ optional($mission->pays)->nom }}

                @if(optional($mission->region)->nom)
                    — {{ optional($mission->region)->nom }}
                @endif

                @if($mission->destination)
                    — {{ $mission->destination }}
                @endif

            </td>

        </tr>


        <tr>

            <td class="bold">
                DURÉE :
            </td>

            <td>
                {{ $duree }} jours
                @if($nombreNuitees > 0)
                    ({{ $nombreNuitees }} nuitées)
                @endif
            </td>

        </tr>


        <tr>

            <td class="bold">
                PÉRIODE :
            </td>

            <td>

                @if($dateDepart)
                    {{ $dateDepart->format('d-m-Y') }}
                @endif

                AU

                @if($dateRetour)
                    {{ $dateRetour->format('d-m-Y') }}
                @endif

            </td>

        </tr>


        @if($mission->ce ?? false)

            <tr>

                <td class="bold">
                    CE :
                </td>

                <td>
                    {{ $mission->ce }}
                </td>

            </tr>

        @endif

    </table>


    {{-- ================================================================= --}}
    {{-- I - FRAIS ET INDEMNITÉS --}}
    {{-- ================================================================= --}}

    <div class="section-title">
        I — FRAIS ET INDEMNITÉS
    </div>


    <table class="budget">

        <thead>

            <tr>

                <th rowspan="2">
                    N°
                </th>

                <th rowspan="2">
                    PRÉNOMS ET NOM
                </th>

                <th rowspan="2">
                    Nbre<br>
                    pers.
                </th>

                <th colspan="3">
                    FRAIS DE MISSION
                </th>

                <th colspan="3">
                    INDEMNITÉS DE MISSION
                </th>

                <th rowspan="2">
                    TAUX<br>
                    MAJORATION
                </th>

                <th rowspan="2">
                    MONTANT<br>
                    MAJORATION
                </th>

                <th rowspan="2">
                    TOTAL
                </th>

            </tr>


            <tr>

                <th>
                    Montant<br>
                    /jour
                </th>

                <th>
                    Nbre<br>
                    jours
                </th>

                <th>
                    Montant
                </th>

                <th>
                    Montant<br>
                    /nuitée
                </th>

                <th>
                    Nbre<br>
                    nuitées
                </th>

                <th>
                    Montant
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($participants as $index => $participant)

                <tr>

                    <td class="text-center">
                        {{ $index + 1 }}
                    </td>


                    <td>

                        {{ $participant->agent->prenom ?? '' }}

                        {{ $participant->agent->nom ?? '' }}

                    </td>


                    <td class="text-center">
                        1
                    </td>


                    <td class="text-right">

                        {{ number_format(
                            (float) ($participant->frais_journalier ?? 0),
                            0,
                            ',',
                            ' '
                        ) }}

                    </td>


                    <td class="text-center">
                        {{ $participant->pdf_nombre_jours }}
                    </td>


                    <td class="text-right">

                        {{ number_format(
                            $participant->pdf_frais_mission,
                            0,
                            ',',
                            ' '
                        ) }}

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            (float) ($participant->indemnite_journaliere ?? 0),
                            0,
                            ',',
                            ' '
                        ) }}

                    </td>


                    <td class="text-center">
                        {{ $participant->pdf_nombre_nuitees }}
                    </td>


                    <td class="text-right">

                        {{ number_format(
                            $participant->pdf_indemnite_mission,
                            0,
                            ',',
                            ' '
                        ) }}

                    </td>


                    <td class="text-center">

                        {{ number_format(
                            $participant->pdf_taux_majoration,
                            2,
                            ',',
                            ' '
                        ) }}

                        %

                    </td>


                    <td class="text-right">

                        {{ number_format(
                            $participant->pdf_montant_majoration,
                            0,
                            ',',
                            ' '
                        ) }}

                    </td>


                    <td class="text-right bold">

                        {{ number_format(
                            $participant->pdf_total,
                            0,
                            ',',
                            ' '
                        ) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="12" class="text-center">
                        Aucun participant enregistré.
                    </td>

                </tr>

            @endforelse


            {{-- SOUS-TOTAL I --}}

            <tr class="subtotal">

                <td colspan="5" class="text-right">
                    SOUS-TOTAL I
                </td>


                <td class="text-right">

                    {{ number_format(
                        $totalFrais,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>


                <td colspan="2"></td>


                <td class="text-right">

                    {{ number_format(
                        $totalIndemnites,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>


                <td></td>


                <td class="text-right">

                    {{ number_format(
                        $totalMajorations,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>


                <td class="text-right">

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


    {{-- ================================================================= --}}
    {{-- II - AUTRES FRAIS --}}
    {{-- ================================================================= --}}

    <div class="section-title">
        II — AUTRES FRAIS
    </div>


    <table class="budget">

        <thead>

            <tr>

                <th width="5%">
                    N°
                </th>

                <th>
                    LIBELLÉ
                </th>

                <th width="12%">
                    NBRE DE<br>
                    PERSONNES
                </th>

                <th width="18%">
                    MONTANT PAR<br>
                    PERSONNE
                </th>

                <th width="20%">
                    MONTANT TOTAL
                </th>

            </tr>

        </thead>


        <tbody>

            {{-- FRAIS DE PARTICIPATION --}}

            <tr>

                <td class="text-center">
                    1
                </td>

                <td>
                    FRAIS DE PARTICIPATION
                </td>

                <td class="text-center">
                    {{ $nombreParticipants }}
                </td>

                <td class="text-right">

                    {{ number_format(
                        $fraisParticipationParPersonne,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>

                <td class="text-right">

                    {{ number_format(
                        $totalFraisParticipation,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>

            </tr>


            {{-- FRAIS DE VISA --}}

            <tr>

                <td class="text-center">
                    2
                </td>

                <td>
                    FRAIS DE VISA
                </td>

                <td class="text-center">
                    {{ $nombreParticipants }}
                </td>

                <td class="text-right">

                    @if($nombreParticipants > 0)

                        {{ number_format(
                            $totalFraisVisa / $nombreParticipants,
                            0,
                            ',',
                            ' '
                        ) }}

                    @else

                        0

                    @endif

                </td>

                <td class="text-right">

                    {{ number_format(
                        $totalFraisVisa,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>

            </tr>


            {{-- TEST COVID --}}

            <tr>

                <td class="text-center">
                    3
                </td>

                <td>
                    FRAIS TEST COVID ALLER-RETOUR
                </td>

                <td class="text-center">
                    {{ $nombreParticipants }}
                </td>

                <td class="text-right">
                    0
                </td>

                <td class="text-right">
                    0
                </td>

            </tr>


            {{-- ASSURANCE VOYAGE --}}

            <tr>

                <td class="text-center">
                    4
                </td>

                <td>
                    ASSURANCE VOYAGE
                </td>

                <td class="text-center">
                    {{ $nombreParticipants }}
                </td>

                <td class="text-right">
                    0
                </td>

                <td class="text-right">
                    0
                </td>

            </tr>


            {{-- HÉBERGEMENT --}}

            <tr>

                <td class="text-center">
                    5
                </td>

                <td>
                    HÉBERGEMENT
                </td>

                <td class="text-center">
                    —
                </td>

                <td class="text-right">
                    —
                </td>

                <td class="text-right">
                    0
                </td>

            </tr>


            {{-- TRANSPORT LOCAL --}}

            <tr>

                <td class="text-center">
                    6
                </td>

                <td>
                    TRANSPORT LOCAL
                </td>

                <td class="text-center">
                    —
                </td>

                <td class="text-right">
                    —
                </td>

                <td class="text-right">
                    0
                </td>

            </tr>


            {{-- AUTRES FRAIS --}}

            <tr>

                <td class="text-center">
                    7
                </td>

                <td>
                    AUTRES FRAIS
                </td>

                <td class="text-center">
                    —
                </td>

                <td class="text-right">
                    —
                </td>

                <td class="text-right">

                    —

                </td>

            </tr>


            {{-- SOUS-TOTAL II --}}

            <tr class="subtotal">

                <td colspan="4" class="text-right">
                    SOUS-TOTAL II
                </td>

                <td class="text-right">

                    {{ number_format(
                        $totalAutresFrais,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>

            </tr>

        </tbody>

    </table>


    {{-- ================================================================= --}}
    {{-- III - BILLETS D'AVION --}}
    {{-- ================================================================= --}}

    <div class="section-title">
        III — BILLETS D'AVION
    </div>


    <table class="budget">

        <thead>

            <tr>

                <th>
                    NBRE DE<br>
                    PERSONNES
                </th>

                <th>
                    PRIX D'UN BILLET
                </th>

                <th>
                    CLASSE
                </th>

                <th>
                    MONTANT TOTAL
                </th>

            </tr>

        </thead>


        <tbody>

            <tr>

                <td class="text-center">
                    {{ $nombreParticipants }}
                </td>


                <td class="text-right">

                    {{ number_format(
                        $billetParPersonne,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>


                <td class="text-center">
                    {{ $classeBilletLibelle }}
                </td>


                <td class="text-right">

                    {{ number_format(
                        $montantBillets,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>

            </tr>


            {{-- SOUS-TOTAL III --}}

            <tr class="subtotal">

                <td colspan="3" class="text-right">
                    SOUS-TOTAL III
                </td>

                <td class="text-right">

                    {{ number_format(
                        $totalBillets,
                        0,
                        ',',
                        ' '
                    ) }}

                </td>

            </tr>

        </tbody>

    </table>


    {{-- ================================================================= --}}
    {{-- TOTAL GÉNÉRAL --}}
    {{-- ================================================================= --}}

    <table class="budget">

        <tr class="total">

            <td width="70%" class="text-right">
                TOTAL GÉNÉRAL
            </td>

            <td width="30%" class="text-right">

                {{ number_format(
                    $totalGeneral,
                    0,
                    ',',
                    ' '
                ) }}

                FCFA

            </td>

        </tr>

    </table>


    <p class="bold">

        ARRÊTÉ À LA SOMME DE :

        {{ number_format(
            $totalGeneral,
            0,
            ',',
            ' '
        ) }}

        FCFA

    </p>


    {{-- ================================================================= --}}
    {{-- SIGNATURES --}}
    {{-- ================================================================= --}}

    <table class="signatures">

        <tr>

            <td class="signature">

                <strong>
                    L'AGENT COMPTABLE
                </strong>

                <div class="signature-space"></div>

                ........................................

            </td>


            <td class="signature">

                <strong>
                    CONTRÔLE DE GESTION
                </strong>

                <div class="signature-space"></div>

                ........................................

            </td>


            <td class="signature">

                <strong>
                    LE DIRECTEUR
                </strong>

                <div class="signature-space"></div>

                ........................................

            </td>


            <td class="signature">

                <strong>
                    LE DIRECTEUR GÉNÉRAL
                </strong>

                <div class="signature-space"></div>

                ........................................

            </td>

        </tr>

    </table>

</body>

</html>