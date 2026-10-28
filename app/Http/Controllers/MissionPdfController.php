<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MissionPdfController extends Controller
{
    /**
     * Générer le budget PDF d'une mission internationale.
     */
public function budgetInternational(Mission $mission)
{
    /*
    |--------------------------------------------------------------------------
    | Sécurité : uniquement les missions internationales
    |--------------------------------------------------------------------------
    */
    abort_unless(
        $mission->type === 'internationale',
        404,
        'Cette mission n\'est pas une mission internationale.'
    );

    /*
    |--------------------------------------------------------------------------
    | Chargement de la mission
    |--------------------------------------------------------------------------
    */
    $mission->load([
        'directionRelation',
        'pays',
        'region',
        'missionAgents.agent',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Dates
    |--------------------------------------------------------------------------
    */
    $dateDepart = $mission->date_depart
        ? Carbon::parse($mission->date_depart)
        : null;

    $dateRetour = $mission->date_retour
        ? Carbon::parse($mission->date_retour)
        : null;

    /*
    |--------------------------------------------------------------------------
    | Durée
    |--------------------------------------------------------------------------
    */
    $duree = $mission->duree;

    if (!$duree && $dateDepart && $dateRetour) {
        $duree = $dateDepart->diffInDays($dateRetour) + 1;
    }

    $duree = max((int) $duree, 1);

    /*
    |--------------------------------------------------------------------------
    | Nombre de nuitées
    |--------------------------------------------------------------------------
    */
    $nombreNuitees = max($duree - 1, 0);

    /*
    |--------------------------------------------------------------------------
    | Participants
    |--------------------------------------------------------------------------
    */
    $participants = $mission->missionAgents;

    /*
    |--------------------------------------------------------------------------
    | Nombre réel de participants
    |--------------------------------------------------------------------------
    */
    $nombreParticipants = $participants->count();

    /*
    |--------------------------------------------------------------------------
    | Calcul des montants - SECTION I
    |--------------------------------------------------------------------------
    */
    $totalFrais = 0;
    $totalIndemnites = 0;
    $totalMajorations = 0;
    $totalParticipants = 0;

    foreach ($participants as $missionAgent) {

        /*
        |--------------------------------------------------------------------------
        | Nombre de jours
        |--------------------------------------------------------------------------
        */
        $nombreJours = (int) (
            $missionAgent->nombre_jours ?: $duree
        );

        /*
        |--------------------------------------------------------------------------
        | Nombre de nuitées
        |--------------------------------------------------------------------------
        */
        $nombreNuiteesAgent = max(
            $nombreJours - 1,
            0
        );

        /*
        |--------------------------------------------------------------------------
        | Montant frais journalier
        |--------------------------------------------------------------------------
        */
        $fraisJournalier = (float) (
            $missionAgent->frais_journalier ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Montant indemnité journalière
        |--------------------------------------------------------------------------
        */
        $indemniteJournaliere = (float) (
            $missionAgent->indemnite_journaliere ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Taux de majoration
        |--------------------------------------------------------------------------
        */
        $tauxMajoration = (float) (
            $missionAgent->taux_majoration ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Frais de mission
        |--------------------------------------------------------------------------
        */
        $fraisMission =
            $fraisJournalier * $nombreJours;

        /*
        |--------------------------------------------------------------------------
        | Indemnités de mission
        |--------------------------------------------------------------------------
        */
        $indemniteMission =
            $indemniteJournaliere * $nombreNuiteesAgent;

        /*
        |--------------------------------------------------------------------------
        | Montant de majoration
        |--------------------------------------------------------------------------
        */
        $montantMajoration = (float) (
            $missionAgent->montant_majoration ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Total du participant
        |--------------------------------------------------------------------------
        */
        $montantTotal = (float) (
            $missionAgent->montant_total
            ?? (
                $fraisMission
                + $indemniteMission
                + $montantMajoration
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Variables destinées à la vue PDF
        |--------------------------------------------------------------------------
        */
        $missionAgent->pdf_nombre_jours =
            $nombreJours;

        $missionAgent->pdf_nombre_nuitees =
            $nombreNuiteesAgent;

        $missionAgent->pdf_frais_mission =
            $fraisMission;

        $missionAgent->pdf_indemnite_mission =
            $indemniteMission;

        $missionAgent->pdf_taux_majoration =
            $tauxMajoration;

        $missionAgent->pdf_montant_majoration =
            $montantMajoration;

        $missionAgent->pdf_total =
            $montantTotal;

        /*
        |--------------------------------------------------------------------------
        | Totaux
        |--------------------------------------------------------------------------
        */
        $totalFrais += $fraisMission;

        $totalIndemnites += $indemniteMission;

        $totalMajorations += $montantMajoration;

        $totalParticipants += $montantTotal;
    }

    /*
    |--------------------------------------------------------------------------
    | BUDGET INTERNATIONAL PAR AGENT
    |--------------------------------------------------------------------------
    |
    | Table :
    | mission_budgets_internationaux
    |
    |--------------------------------------------------------------------------
    */
    $budgetsInternationaux = DB::table(
        'mission_budgets_internationaux'
    )
        ->where('mission_id', $mission->id)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | FRAIS DE PARTICIPATION
    |--------------------------------------------------------------------------
    */
    $totalFraisParticipation = (float)
        $budgetsInternationaux->sum(
            'frais_participation'
        );

    /*
    |--------------------------------------------------------------------------
    | FRAIS DE VISA
    |--------------------------------------------------------------------------
    */
    $totalFraisVisa = (float)
        $budgetsInternationaux->sum(
            'frais_visa'
        );

    /*
    |--------------------------------------------------------------------------
    | BILLETS D'AVION
    |--------------------------------------------------------------------------
    */
    $totalBillets = (float)
        $budgetsInternationaux->sum(
            'billet_avion'
        );

    /*
    |--------------------------------------------------------------------------
    | Compatibilité avec la Blade existante
    |--------------------------------------------------------------------------
    */
    $montantBillets = $totalBillets;

    /*
    |--------------------------------------------------------------------------
    | Frais de participation par personne
    |--------------------------------------------------------------------------
    */
    $fraisParticipationParPersonne =
        $nombreParticipants > 0
            ? $totalFraisParticipation / $nombreParticipants
            : 0;

    /*
    |--------------------------------------------------------------------------
    | Prix moyen du billet par personne
    |--------------------------------------------------------------------------
    */
    $billetParPersonne =
        $nombreParticipants > 0
            ? $totalBillets / $nombreParticipants
            : 0;

    /*
    |--------------------------------------------------------------------------
    | Classe des billets
    |--------------------------------------------------------------------------
    */
    $classesBillets = $budgetsInternationaux
        ->pluck('classe_billet')
        ->filter()
        ->unique()
        ->values();

    if ($classesBillets->count() === 1) {

        $classeBillet = $classesBillets->first();

    } elseif ($classesBillets->count() > 1) {

        $classeBillet = 'mixte';

    } else {

        $classeBillet = 'economique';
    }

    /*
    |--------------------------------------------------------------------------
    | Libellé de la classe
    |--------------------------------------------------------------------------
    */
    $classeBilletLibelle = match ($classeBillet) {

        'affaires' =>
            'CLASSE AFFAIRES',

        'mixte' =>
            'CLASSES ÉCONOMIQUE / AFFAIRES',

        default =>
            'CLASSE ÉCONOMIQUE',
    };

    /*
    |--------------------------------------------------------------------------
    | Montant complémentaire
    |--------------------------------------------------------------------------
    */
    $montantComplementaire = (float) (
        $mission->montant_complementaire_international ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | TOTAL SECTION II
    |--------------------------------------------------------------------------
    |
    | Frais de participation
    | + frais de visa
    | + autres frais complémentaires
    |
    */
    $totalAutresFrais =
        $totalFraisParticipation
        + $totalFraisVisa;

    /*
    |--------------------------------------------------------------------------
    | TOTAL GÉNÉRAL
    |--------------------------------------------------------------------------
    |
    | Section I
    | + Section II
    | + Section III
    |
    */
    $totalGeneral =
        $totalParticipants
        + $totalAutresFrais
        + $totalBillets;

    /*
    |--------------------------------------------------------------------------
    | Génération du PDF
    |--------------------------------------------------------------------------
    */
    return Pdf::loadView(
        'missions.budget-international',
        compact(
            'mission',
            'dateDepart',
            'dateRetour',
            'duree',
            'nombreNuitees',

            // Participants
            'participants',
            'nombreParticipants',

            // Section I
            'totalFrais',
            'totalIndemnites',
            'totalMajorations',
            'totalParticipants',

            // Section II
            'budgetsInternationaux',
            'totalFraisParticipation',
            'fraisParticipationParPersonne',
            'totalFraisVisa',
            'montantComplementaire',
            'totalAutresFrais',

            // Section III
            'montantBillets',
            'totalBillets',
            'billetParPersonne',
            'classeBillet',
            'classeBilletLibelle',

            // Total général
            'totalGeneral'
        )
    )
    ->setPaper('a4', 'landscape')
    ->stream(
        'budget-international-' . $mission->numero . '.pdf'
    );
}

public function budgetNational(Mission $mission)
{
    abort_unless(
        $mission->type === 'nationale',
        404,
        'Cette mission n\'est pas une mission nationale.'
    );

    /*
    |--------------------------------------------------------------------------
    | Chargement des relations
    |--------------------------------------------------------------------------
    */
    $mission->load([
        'directionRelation',
        'pays',
        'region',
        'missionAgents.agent',
        'carburant',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Dates
    |--------------------------------------------------------------------------
    */
    $dateDepart = $mission->date_depart
        ? \Carbon\Carbon::parse($mission->date_depart)
        : null;

    $dateRetour = $mission->date_retour
        ? \Carbon\Carbon::parse($mission->date_retour)
        : null;

    /*
    |--------------------------------------------------------------------------
    | Durée de la mission
    |--------------------------------------------------------------------------
    */
    $duree = (int) $mission->duree;

    if ($duree <= 0 && $dateDepart && $dateRetour) {
        $duree = $dateDepart->diffInDays($dateRetour) + 1;
    }

    $duree = max($duree, 1);

    /*
    |--------------------------------------------------------------------------
    | PARTICIPANTS
    |--------------------------------------------------------------------------
    */
    $participants = $mission->missionAgents;

    /*
    |--------------------------------------------------------------------------
    | TOTAUX FRAIS / INDEMNITÉS
    |--------------------------------------------------------------------------
    */
    $totalFraisMission = 0;
    $totalIndemnites = 0;
    $totalParticipants = 0;

    foreach ($participants as $missionAgent) {

        /*
        |--------------------------------------------------------------------------
        | Nombre de jours
        |--------------------------------------------------------------------------
        */
        $nombreJours = (int) (
            $missionAgent->nombre_jours ?: $duree
        );

        $nombreJours = max($nombreJours, 1);

        /*
        |--------------------------------------------------------------------------
        | Nombre de nuitées
        |--------------------------------------------------------------------------
        | Une mission de 10 jours = 9 nuitées
        |--------------------------------------------------------------------------
        */
        $nombreNuitees = max(
            $nombreJours - 1,
            0
        );

        /*
        |--------------------------------------------------------------------------
        | Frais journaliers
        |--------------------------------------------------------------------------
        */
        $fraisJournalier = (float) (
            $missionAgent->frais_journalier ?? 0
        );

        $fraisMission =
            $fraisJournalier * $nombreJours;

        /*
        |--------------------------------------------------------------------------
        | Indemnité journalière
        |--------------------------------------------------------------------------
        */
        $indemniteJournaliere = (float) (
            $missionAgent->indemnite_journaliere ?? 0
        );

        $indemniteMission =
            $indemniteJournaliere * $nombreNuitees;

        /*
        |--------------------------------------------------------------------------
        | Montant total enregistré
        |--------------------------------------------------------------------------
        |
        | Si montant_total existe et est différent de NULL,
        | on le considère comme le montant officiel enregistré.
        |
        | Sinon, on calcule :
        | frais + indemnités
        |
        */
        if ($missionAgent->montant_total !== null) {

            $totalAgent = (float) $missionAgent->montant_total;

        } else {

            $totalAgent =
                $fraisMission +
                $indemniteMission;
        }

        /*
        |--------------------------------------------------------------------------
        | Données destinées au PDF
        |--------------------------------------------------------------------------
        */
        $missionAgent->pdf_nombre_jours =
            $nombreJours;

        $missionAgent->pdf_nombre_nuitees =
            $nombreNuitees;

        $missionAgent->pdf_frais_journalier =
            $fraisJournalier;

        $missionAgent->pdf_frais_mission =
            $fraisMission;

        $missionAgent->pdf_indemnite_journaliere =
            $indemniteJournaliere;

        $missionAgent->pdf_indemnite_mission =
            $indemniteMission;

        $missionAgent->pdf_total =
            $totalAgent;

        /*
        |--------------------------------------------------------------------------
        | Cumuls
        |--------------------------------------------------------------------------
        */
        $totalFraisMission += $fraisMission;

        $totalIndemnites += $indemniteMission;

        $totalParticipants += $totalAgent;
    }

    /*
    |--------------------------------------------------------------------------
    | CARBURANT
    |--------------------------------------------------------------------------
    |
    | Une seule ligne dans mission_carburants.
    |
    | Structure :
    |
    | nombre_voitures
    | quantite_carburant
    | quantite_carburant_ville
    | prix_litre
    | montant_carburant
    | montant_carburant_ville
    | sous_total
    |
    */
    $carburant = $mission->carburant;

    /*
    |--------------------------------------------------------------------------
    | Valeurs par défaut
    |--------------------------------------------------------------------------
    */
    $nombreVehicules = 0;

    $quantiteCarburant = 0;
    $quantiteCarburantVille = 0;

    $prixLitre = 0;

    $montantCarburant = 0;
    $montantCarburantVille = 0;

    $totalCarburant = 0;

    /*
    |--------------------------------------------------------------------------
    | Récupération des données carburant
    |--------------------------------------------------------------------------
    */
    if ($carburant) {

        $nombreVehicules =
            (int) $carburant->nombre_voitures;

        $quantiteCarburant =
            (float) $carburant->quantite_carburant;

        $quantiteCarburantVille =
            (float) $carburant->quantite_carburant_ville;

        $prixLitre =
            (float) $carburant->prix_litre;

        $montantCarburant =
            (float) $carburant->montant_carburant;

        $montantCarburantVille =
            (float) $carburant->montant_carburant_ville;

        /*
        |--------------------------------------------------------------------------
        | Sous-total carburant
        |--------------------------------------------------------------------------
        */
        $totalCarburant =
            (float) $carburant->sous_total;
    }

    /*
    |--------------------------------------------------------------------------
    | Sécurité :
    | si le sous-total n'est pas renseigné correctement,
    | on peut le recalculer.
    |--------------------------------------------------------------------------
    */
    if ($totalCarburant <= 0 && $carburant) {

        $totalCarburant =
            $montantCarburant +
            $montantCarburantVille;
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL GENERAL
    |--------------------------------------------------------------------------
    */
    $totalGeneral =
        $totalParticipants +
        $totalCarburant;

    /*
    |--------------------------------------------------------------------------
    | Mise à jour des valeurs PDF sur le modèle carburant
    |--------------------------------------------------------------------------
    |
    | Cela permet de simplifier le Blade.
    |--------------------------------------------------------------------------
    */
    if ($carburant) {

        $carburant->pdf_nombre_voitures =
            $nombreVehicules;

        $carburant->pdf_quantite_carburant =
            $quantiteCarburant;

        $carburant->pdf_quantite_carburant_ville =
            $quantiteCarburantVille;

        $carburant->pdf_prix_litre =
            $prixLitre;

        $carburant->pdf_montant_carburant =
            $montantCarburant;

        $carburant->pdf_montant_carburant_ville =
            $montantCarburantVille;

        $carburant->pdf_sous_total =
            $totalCarburant;
    }

    /*
    |--------------------------------------------------------------------------
    | Génération du PDF
    |--------------------------------------------------------------------------
    */
    return \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'missions.budget-national',
        compact(
            'mission',
            'dateDepart',
            'dateRetour',
            'duree',
            'participants',

            // Participants
            'totalFraisMission',
            'totalIndemnites',
            'totalParticipants',

            // Carburant
            'carburant',
            'nombreVehicules',
            'quantiteCarburant',
            'quantiteCarburantVille',
            'prixLitre',
            'montantCarburant',
            'montantCarburantVille',
            'totalCarburant',

            // Total
            'totalGeneral'
        )
    )
    ->setPaper('a4', 'landscape')
    ->stream(
        'budget-national-' .
        $mission->numero .
        '.pdf'
    );
}

public function dechargeAgent(Mission $mission, $agent)
{
    /*
    |--------------------------------------------------------------------------
    | Vérification de la mission
    |--------------------------------------------------------------------------
    */
    $mission->load([
        'directionRelation',
        'pays',
        'region',
        'missionAgents.agent',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Récupération du participant
    |--------------------------------------------------------------------------
    */
    $missionAgent = $mission->missionAgents()
        ->with('agent')
        ->where('agent_id', $agent)
        ->firstOrFail();

    /*
    |--------------------------------------------------------------------------
    | Vérification du paiement
    |--------------------------------------------------------------------------
    |
    | La décharge ne doit être éditée qu'après paiement.
    |--------------------------------------------------------------------------
    */
    abort_unless(
        $mission->statut === 'payee',
        403,
        'La décharge ne peut être générée qu\'après le paiement de la mission.'
    );

    /*
    |--------------------------------------------------------------------------
    | Agent
    |--------------------------------------------------------------------------
    */
    $agentModel = $missionAgent->agent;

    abort_unless(
        $agentModel,
        404,
        'Le missionnaire est introuvable.'
    );

    /*
    |--------------------------------------------------------------------------
    | Dates de mission
    |--------------------------------------------------------------------------
    */
    $dateDepart = $mission->date_depart
        ? \Carbon\Carbon::parse($mission->date_depart)
        : null;

    $dateRetour = $mission->date_retour
        ? \Carbon\Carbon::parse($mission->date_retour)
        : null;

    /*
    |--------------------------------------------------------------------------
    | Nombre de jours
    |--------------------------------------------------------------------------
    */
    $nombreJours = (int) (
        $missionAgent->nombre_jours
        ?: $mission->duree
        ?: (
            $dateDepart && $dateRetour
                ? $dateDepart->diffInDays($dateRetour) + 1
                : 1
        )
    );

    $nombreJours = max($nombreJours, 1);

    /*
    |--------------------------------------------------------------------------
    | Nombre de nuitées
    |--------------------------------------------------------------------------
    */
    $nombreNuitees = max(
        $nombreJours - 1,
        0
    );

    /*
    |--------------------------------------------------------------------------
    | Frais journaliers
    |--------------------------------------------------------------------------
    */
    $fraisJournalier = (float) (
        $missionAgent->frais_journalier ?? 0
    );

    $fraisMission =
        $fraisJournalier * $nombreJours;

    /*
    |--------------------------------------------------------------------------
    | Indemnité journalière
    |--------------------------------------------------------------------------
    */
    $indemniteJournaliere = (float) (
        $missionAgent->indemnite_journaliere ?? 0
    );

    $indemniteMission =
        $indemniteJournaliere * $nombreNuitees;

    /*
    |--------------------------------------------------------------------------
    | Majoration
    |--------------------------------------------------------------------------
    */
    $montantMajoration = (float) (
        $missionAgent->montant_majoration ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | Montant officiel du missionnaire
    |--------------------------------------------------------------------------
    |
    | IMPORTANT :
    | montant_total de mission_agents reste prioritaire.
    |--------------------------------------------------------------------------
    */
    if ($missionAgent->montant_total !== null) {

        $montantMissionnaire =
            (float) $missionAgent->montant_total;

    } else {

        $montantMissionnaire =
            $fraisMission
            + $indemniteMission
            + $montantMajoration;
    }

    /*
    |--------------------------------------------------------------------------
    | Montant payé
    |--------------------------------------------------------------------------
    |
    | Si la comptabilité a enregistré un montant payé global dans
    | missions.montant_paye, il ne faut PAS automatiquement l'utiliser
    | comme montant individuel.
    |
    | Pour l'instant, la décharge reprend donc le montant calculé/enregistré
    | dans mission_agents.
    |--------------------------------------------------------------------------
    */
    $montantPaye = $montantMissionnaire;

    /*
    |--------------------------------------------------------------------------
    | Montant en lettres
    |--------------------------------------------------------------------------
    */
    $montantEnLettres = $this->montantEnLettres(
        $montantPaye
    );

    /*
    |--------------------------------------------------------------------------
    | Numéro de l'ordre
    |--------------------------------------------------------------------------
    */
    $numeroOrdre = $mission->numero_ordre
        ?: $mission->numero
        ?: '-';

    /*
    |--------------------------------------------------------------------------
    | Date de l'ordre
    |--------------------------------------------------------------------------
    |
    | Si aucune colonne spécifique n'existe pour la date de l'ordre,
    | on utilise la date de départ.
    |--------------------------------------------------------------------------
    */
    $dateOrdre = $dateDepart;

    /*
    |--------------------------------------------------------------------------
    | Date de paiement
    |--------------------------------------------------------------------------
    */
    $datePaiement = $mission->date_paiement
        ? \Carbon\Carbon::parse($mission->date_paiement)
        : now();

    /*
    |--------------------------------------------------------------------------
    | Libellé de la mission
    |--------------------------------------------------------------------------
    */
    $objetMission = $mission->objet ?: '-';

    /*
    |--------------------------------------------------------------------------
    | Référence paiement
    |--------------------------------------------------------------------------
    */
    $referencePaiement =
        $mission->reference_paiement ?: '-';

    /*
    |--------------------------------------------------------------------------
    | Direction
    |--------------------------------------------------------------------------
    */
    $direction = optional(
        $mission->directionRelation
    )->nom_direction;

    /*
    |--------------------------------------------------------------------------
    | Destination
    |--------------------------------------------------------------------------
    */
    $destination = $mission->destination ?: '-';

    /*
    |--------------------------------------------------------------------------
    | Type de mission
    |--------------------------------------------------------------------------
    */
    $typeMission = $mission->type === 'nationale'
        ? 'Nationale'
        : 'Internationale';

    /*
    |--------------------------------------------------------------------------
    | Données supplémentaires pour Blade
    |--------------------------------------------------------------------------
    */
    $missionAgent->pdf_nombre_jours =
        $nombreJours;

    $missionAgent->pdf_nombre_nuitees =
        $nombreNuitees;

    $missionAgent->pdf_frais_mission =
        $fraisMission;

    $missionAgent->pdf_indemnite_mission =
        $indemniteMission;

    $missionAgent->pdf_montant_majoration =
        $montantMajoration;

    $missionAgent->pdf_total =
        $montantMissionnaire;

    /*
    |--------------------------------------------------------------------------
    | Génération PDF
    |--------------------------------------------------------------------------
    */
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'missions.decharge-agent',
        compact(
            'mission',
            'missionAgent',
            'agentModel',
            'dateDepart',
            'dateRetour',
            'dateOrdre',
            'datePaiement',
            'nombreJours',
            'nombreNuitees',
            'fraisJournalier',
            'fraisMission',
            'indemniteJournaliere',
            'indemniteMission',
            'montantMajoration',
            'montantMissionnaire',
            'montantPaye',
            'montantEnLettres',
            'numeroOrdre',
            'objetMission',
            'referencePaiement',
            'direction',
            'destination',
            'typeMission'
        )
    );

    /*
    |--------------------------------------------------------------------------
    | Format
    |--------------------------------------------------------------------------
    */
    $pdf->setPaper(
        'a4',
        'portrait'
    );

    /*
    |--------------------------------------------------------------------------
    | Nom du fichier
    |--------------------------------------------------------------------------
    */
    $nomAgent = trim(
        ($agentModel->nom ?? '') .
        '-' .
        ($agentModel->prenom ?? '')
    );

    $nomAgent = preg_replace(
        '/[^A-Za-z0-9_-]/',
        '-',
        $nomAgent
    );

    return $pdf->stream(
        'decharge-' .
        $mission->numero .
        '-' .
        $nomAgent .
        '.pdf'
    );
}

    /**
     * Conversion simple du montant en lettres.
     */
    private function montantEnLettres(float $montant): string
    {
        $montant = (int) round($montant);

        if ($montant === 0) {
            return 'zéro franc CFA';
        }

        if (class_exists(\NumberFormatter::class)) {

            $formatter = new \NumberFormatter(
                'fr_FR',
                \NumberFormatter::SPELLOUT
            );

            return ucfirst(
                $formatter->format($montant)
            ) . ' francs CFA';
        }

        return number_format(
            $montant,
            0,
            ',',
            ' '
        ) . ' francs CFA';
    }

    /**
 * Générer l'ordre officiel de mission.
 */

public function ordreMission(Mission $mission)
{
    $mission->load([
        'directionRelation',
        'pays',
        'region',
        'voiture',
        'chauffeur',
        'missionAgents.agent',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Vérification des droits et du statut
    |--------------------------------------------------------------------------
    */
    $user = auth()->user();

    $profilAutorise = $user
        && in_array($user->profil, ['rh', 'admin'], true);

    $statutAutorise = in_array(
        $mission->statut,
        [
            'en_attente_budget',
            'en_attente_comptabilite',
            'payee',
            'terminee',
        ],
        true
    );

    abort_unless(
        $profilAutorise || $statutAutorise,
        403,
        'Vous n’êtes pas autorisé à imprimer cet ordre de mission.'
    );

    /*
    |--------------------------------------------------------------------------
    | Vérification du numéro officiel
    |--------------------------------------------------------------------------
    */
    abort_unless(
        !empty($mission->numero)
        && !empty($mission->numero_ordre)
        && $mission->numero === $mission->numero_ordre,
        422,
        'Le numéro officiel de la mission est absent ou incohérent.'
    );

    /*
    |--------------------------------------------------------------------------
    | Dates et durée
    |--------------------------------------------------------------------------
    */
    $dateDepart = $mission->date_depart
        ? Carbon::parse($mission->date_depart)
        : null;

    $dateRetour = $mission->date_retour
        ? Carbon::parse($mission->date_retour)
        : null;

    $duree = (int) ($mission->duree ?? 0);

    if ($duree <= 0 && $dateDepart && $dateRetour) {
        $duree = $dateDepart->diffInDays($dateRetour) + 1;
    }

    /*
    |--------------------------------------------------------------------------
    | Données de présentation
    |--------------------------------------------------------------------------
    */
    $numeroOrdre = $mission->numero_ordre;

    $direction = optional($mission->directionRelation)->nom_direction
        ?? $mission->direction
        ?? '-';

    $destination = $mission->destination
        ?: optional($mission->region)->nom
        ?: optional($mission->pays)->nom
        ?: '-';

    $typeMission = $mission->type === 'internationale'
        ? 'INTERNATIONALE'
        : 'NATIONALE';

    $participants = $mission->missionAgents;

    /*
    |--------------------------------------------------------------------------
    | Génération du PDF
    |--------------------------------------------------------------------------
    */
    $pdf = Pdf::loadView(
        'missions.ordre',
        compact(
            'mission',
            'numeroOrdre',
            'direction',
            'destination',
            'typeMission',
            'dateDepart',
            'dateRetour',
            'duree',
            'participants'
        )
    );

    $pdf->setPaper('a4', 'portrait');

    return $pdf->stream(
        'ordre-mission-' . $numeroOrdre . '.pdf'
    );
}
}
