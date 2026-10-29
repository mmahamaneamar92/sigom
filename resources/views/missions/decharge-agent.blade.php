<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <title>Décharge - {{ $agentModel->nom }} {{ $agentModel->prenom }}</title>

    <style>
        @page {
            margin: 35px 45px 40px 45px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.45;
        }

        .page {
            width: 100%;
        }

        /* ---------------------------------------------------------
           EN-TÊTE
        --------------------------------------------------------- */

        .header {
            width: 100%;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-left {
            width: 72%;
            text-align: center;
            vertical-align: top;
        }

        .header-right {
            width: 28%;
            text-align: right;
            vertical-align: top;
        }

        .republique {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .devise {
            font-size: 9px;
            font-style: italic;
            margin-top: 2px;
        }

        .ministere {
            font-size: 10px;
            font-weight: bold;
            margin-top: 8px;
            text-transform: uppercase;
        }

        .canam {
            font-size: 12px;
            font-weight: bold;
            margin-top: 3px;
            text-transform: uppercase;
        }

        .agence {
            font-size: 10px;
            font-weight: bold;
            margin-top: 3px;
            text-transform: uppercase;
        }

        .reference-box {
            font-size: 9px;
            line-height: 1.5;
            text-align: center;
        }

        .reference-box .ligne {
            border-bottom: 1px solid #000;
            padding: 2px 5px;
            min-height: 14px;
        }

        /* ---------------------------------------------------------
           TITRE
        --------------------------------------------------------- */

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 25px;
            margin-bottom: 35px;
        }

        /* ---------------------------------------------------------
           CORPS
        --------------------------------------------------------- */

        .content {
            width: 100%;
            text-align: justify;
        }

        .intro {
            font-size: 12px;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .intro strong {
            font-weight: bold;
        }

        .identity {
            font-weight: bold;
            text-transform: uppercase;
        }

        .amount-box {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 12px;
            border: 1px solid #000;
        }

        .amount-label {
            font-weight: bold;
        }

        .amount-words {
            margin-top: 6px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .amount-number {
            margin-top: 5px;
            font-size: 14px;
            font-weight: bold;
        }

        /* ---------------------------------------------------------
           INFORMATIONS MISSION
        --------------------------------------------------------- */

        .mission-info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .mission-info td {
            padding: 5px 6px;
            border: 1px solid #000;
        }

        .mission-info .label {
            width: 25%;
            font-weight: bold;
            background: #f2f2f2;
        }

        .mission-info .value {
            width: 75%;
        }

        /* ---------------------------------------------------------
           TEXTE FINAL
        --------------------------------------------------------- */

        .final-text {
            margin-top: 25px;
            text-align: justify;
            line-height: 1.8;
        }

        /* ---------------------------------------------------------
           DATE
        --------------------------------------------------------- */

        .date {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }

        /* ---------------------------------------------------------
           SIGNATURES
        --------------------------------------------------------- */

        .signatures {
            width: 100%;
            margin-top: 60px;
            border-collapse: collapse;
        }

        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 5px;
        }

        .signature-title {
            font-weight: bold;
            text-transform: uppercase;
        }

        .signature-space {
            height: 75px;
        }

        .signature-name {
            font-weight: bold;
            text-transform: uppercase;
        }

        /* ---------------------------------------------------------
           PIED DE PAGE
        --------------------------------------------------------- */

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #555;
        }

        .small {
            font-size: 9px;
        }
    </style>
</head>

<body>

<div class="page">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}

    <div class="header">

        <table class="header-table">

            <tr>

                <td class="header-left">

                    <div class="republique">
                        RÉPUBLIQUE DU MALI
                    </div>

                    <div class="devise">
                        Un Peuple - Un But - Une Foi
                    </div>

                    <div class="ministere">
                        MINISTÈRE DE LA SANTÉ
                        ET DU DÉVELOPPEMENT SOCIAL
                    </div>

                    <div class="canam">
                        CAISSE NATIONALE
                        D'ASSURANCE MALADIE
                    </div>

                    <div class="agence">
                        AGENCE COMPTABLE
                    </div>

                </td>

                <td class="header-right">

                    <div class="reference-box">

                        <div class="ligne">
                            F11
                        </div>

                        <div class="ligne">
                            F12
                        </div>

                        <div class="ligne">
                            +
                        </div>

                        <div class="ligne">
                            T
                        </div>

                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- =========================================================
         TITRE
    ========================================================== --}}

    <div class="title">
        DÉCHARGE
    </div>


    {{-- =========================================================
         CORPS
    ========================================================== --}}

    <div class="content">

        <div class="intro">

            Je soussigné(e),
            <span class="identity">
                {{ $agentModel->prenom }}
                {{ $agentModel->nom }}
            </span>,

            @if(!empty($agentModel->fonction))
                {{ $agentModel->fonction }},
            @else
                missionnaire,
            @endif

            atteste avoir reçu du Régisseur de la
            <strong>
                Caisse Nationale d'Assurance Maladie
            </strong>
            la somme de :

        </div>


        {{-- =====================================================
             MONTANT
        ====================================================== --}}

        <div class="amount-box">

            <div class="amount-label">
                La somme de :
            </div>

            <div class="amount-words">
                {{ $montantEnLettres }}
            </div>

            <div class="amount-number">
                (
                {{ number_format(
                    $montantPaye,
                    0,
                    ',',
                    ' '
                ) }}
                )
                FRANCS CFA
            </div>

        </div>


        {{-- =====================================================
             OBJET DU PAIEMENT
        ====================================================== --}}

        <div class="intro">

            <strong>Objet :</strong>

            Paiement des frais de mission
            (frais de mission et indemnités)

            @if($mission->type === 'nationale')
                pour la mission effectuée à
                <strong>
                    {{ $destination }}
                </strong>
            @else
                pour la mission effectuée à
                <strong>
                    {{ $destination }}
                </strong>
            @endif

            suivant l'ordre de mission
            <strong>
                N° {{ $numeroOrdre }}
            </strong>

            @if($dateOrdre)
                du
                <strong>
                    {{ $dateOrdre->format('d/m/Y') }}
                </strong>
            @endif

            .

        </div>


        {{-- =====================================================
             INFORMATIONS DE LA MISSION
        ====================================================== --}}

        <table class="mission-info">

            <tr>

                <td class="label">
                    Missionnaire
                </td>

                <td class="value">
                    {{ $agentModel->prenom }}
                    {{ $agentModel->nom }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Matricule
                </td>

                <td class="value">
                    {{ $agentModel->matricule ?? '-' }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Fonction
                </td>

                <td class="value">
                    {{ $agentModel->fonction ?? '-' }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Direction
                </td>

                <td class="value">
                    {{ $direction ?? '-' }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Objet de la mission
                </td>

                <td class="value">
                    {{ $objetMission }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Destination
                </td>

                <td class="value">
                    {{ $destination }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Période
                </td>

                <td class="value">

                    @if($dateDepart)
                        du
                        {{ $dateDepart->format('d/m/Y') }}
                    @endif

                    @if($dateRetour)
                        au
                        {{ $dateRetour->format('d/m/Y') }}
                    @endif

                </td>

            </tr>

            <tr>

                <td class="label">
                    Durée
                </td>

                <td class="value">
                    {{ $nombreJours }}
                    jour{{ $nombreJours > 1 ? 's' : '' }}

                    @if($nombreNuitees > 0)
                        /
                        {{ $nombreNuitees }}
                        nuit{{ $nombreNuitees > 1 ? 's' : '' }}
                    @endif
                </td>

            </tr>

            <tr>

                <td class="label">
                    Référence du paiement
                </td>

                <td class="value">
                    {{ $referencePaiement }}
                </td>

            </tr>

        </table>


        {{-- =====================================================
             DÉTAIL DU MONTANT
        ====================================================== --}}

        <table class="mission-info">

            <tr>

                <td class="label">
                    Frais de mission
                </td>

                <td class="value">
                    {{ number_format(
                        $fraisMission,
                        0,
                        ',',
                        ' '
                    ) }}
                    FCFA
                </td>

            </tr>

            <tr>

                <td class="label">
                    Indemnités
                </td>

                <td class="value">
                    {{ number_format(
                        $indemniteMission,
                        0,
                        ',',
                        ' '
                    ) }}
                    FCFA
                </td>

            </tr>

            @if($montantMajoration > 0)

                <tr>

                    <td class="label">
                        Majoration
                    </td>

                    <td class="value">
                        {{ number_format(
                            $montantMajoration,
                            0,
                            ',',
                            ' '
                        ) }}
                        FCFA
                    </td>

                </tr>

            @endif

            <tr>

                <td class="label">
                    <strong>
                        TOTAL PAYÉ
                    </strong>
                </td>

                <td class="value">

                    <strong>
                        {{ number_format(
                            $montantPaye,
                            0,
                            ',',
                            ' '
                        ) }}
                        FCFA
                    </strong>

                </td>

            </tr>

        </table>


        {{-- =====================================================
             TEXTE FINAL
        ====================================================== --}}

        <div class="final-text">

            En foi de quoi, la présente décharge est signée
            pour servir et valoir ce que de droit.

        </div>


        {{-- =====================================================
             DATE
        ====================================================== --}}

        <div class="date">

            Bamako, le
            {{ $datePaiement->format('d/m/Y') }}

        </div>


        {{-- =====================================================
             SIGNATURES
        ====================================================== --}}

        <table class="signatures">

            <tr>

                <td>

                    <div class="signature-title">
                        Le missionnaire
                    </div>

                    <div class="signature-space"></div>

                    <div class="signature-name">
                        {{ $agentModel->prenom }}
                        {{ $agentModel->nom }}
                    </div>

                </td>


                <td>

                    <div class="signature-title">
                        Le Régisseur
                    </div>

                    <div class="signature-space"></div>

                    <div>
                        Signature et cachet
                    </div>

                </td>

            </tr>

        </table>

    </div>

</div>


{{-- =============================================================
     PIED DE PAGE
============================================================= --}}

<div class="footer">

    CANAM — Agence Comptable —
    Décharge individuelle de mission

</div>

</body>
</html>