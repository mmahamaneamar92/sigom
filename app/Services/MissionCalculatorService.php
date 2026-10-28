<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\Mission;
use App\Models\BaremeMission;
use App\Models\Categorie;
use InvalidArgumentException;

class MissionCalculatorService
{
    /**
     * Calcul des frais et indemnités d'un agent
     * pour une mission.
     *
     * RÈGLES :
     *
     * 1. frais_mission = montant par jour
     *
     * 2. indemnite_forfaitaire = montant par nuit
     *
     * 3. nombre de nuits = nombre de jours - 1
     *
     * MISSION NATIONALE :
     *   Frais = frais_mission × jours
     *   Indemnité = indemnite_forfaitaire × nuits
     *   Sous-total = Frais + Indemnité
     *   Majoration = 0
     *   Total général = Sous-total
     *
     * MISSION INTERNATIONALE :
     *   Frais = frais_mission × jours
     *   Indemnité = indemnite_forfaitaire × nuits
     *   Sous-total = Frais + Indemnité
     *   Majoration = Sous-total × taux
     *   Total général = Sous-total + Majoration
     */
    public function calculer(
        Agent $agent,
        Mission $mission,
        int $jours
    ): array {

        /*
        |--------------------------------------------------------------------------
        | 1. VALIDATION DU NOMBRE DE JOURS
        |--------------------------------------------------------------------------
        */

        if ($jours <= 0) {
            throw new InvalidArgumentException(
                'Le nombre de jours doit être supérieur à zéro.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. VALIDATION DU TYPE DE MISSION
        |--------------------------------------------------------------------------
        */

        if (!in_array(
            $mission->type,
            ['nationale', 'internationale'],
            true
        )) {
            throw new InvalidArgumentException(
                "Type de mission invalide : {$mission->type}"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. NORMALISATION DE LA CATÉGORIE
        |--------------------------------------------------------------------------
        */

        $categorie = strtoupper(
            trim((string) $agent->categorie)
        );

        $categorieNormalisee = match ($categorie) {

            '1',
            'I',
            'CAT I',
            'CATEGORIE I',
            'CATÉGORIE I' => 'I',

            '2',
            'II',
            'CAT II',
            'CATEGORIE II',
            'CATÉGORIE II' => 'II',

            '3',
            'III',
            'CAT III',
            'CATEGORIE III',
            'CATÉGORIE III' => 'III',

            '4',
            'IV',
            'CAT IV',
            'CATEGORIE IV',
            'CATÉGORIE IV' => 'IV',

            '5',
            'V',
            'CAT V',
            'CATEGORIE V',
            'CATÉGORIE V' => 'V',

            '6',
            'VI',
            'CAT VI',
            'CATEGORIE VI',
            'CATÉGORIE VI' => 'VI',

            '7',
            'VII',
            'CAT VII',
            'CATEGORIE VII',
            'CATÉGORIE VI' => 'VII',

            '8',
            'VIII',
            'CAT VIII',
            'CATEGORIE VIII',
            'CATÉGORIE VI' => 'VIII',

            default => null,
        };

        if (!$categorieNormalisee) {
            throw new InvalidArgumentException(
                "La catégorie de l'agent {$agent->prenom} {$agent->nom} "
                . "n'est pas reconnue : {$agent->categorie}"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. RECHERCHE DE LA CATÉGORIE
        |--------------------------------------------------------------------------
        |
        | La table bareme_missions utilise categorie_id.
        |
        */

        $categorieModel = Categorie::whereRaw(
            'UPPER(nom_categorie) = ?',
            [$categorieNormalisee]
        )->first();

        /*
        |--------------------------------------------------------------------------
        | RECHERCHE PLUS SOUPLE
        |--------------------------------------------------------------------------
        |
        | Permet de gérer :
        | - Catégorie I
        | - CAT I
        | - Categorie I
        |
        */

        if (!$categorieModel) {

            $categorieModel = Categorie::where(
                'nom_categorie',
                'like',
                '%' . $categorieNormalisee . '%'
            )->first();
        }

        if (!$categorieModel) {
            throw new InvalidArgumentException(
                "La catégorie {$categorieNormalisee} "
                . "n'existe pas dans la table categories."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. RECHERCHE DU BARÈME
        |--------------------------------------------------------------------------
        */

        $bareme = BaremeMission::where(
            'type_mission',
            $mission->type
        )
        ->where(
            'categorie_id',
            $categorieModel->id
        )
        ->first();

        if (!$bareme) {
            throw new InvalidArgumentException(
                "Aucun barème trouvé pour la catégorie "
                . "{$categorieNormalisee} "
                . "et le type de mission {$mission->type}."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. NOMBRE DE NUITS
        |--------------------------------------------------------------------------
        |
        | 1 jour  = 0 nuit
        | 2 jours = 1 nuit
        | 3 jours = 2 nuits
        | 4 jours = 3 nuits
        |
        */

        $nombreNuits = max(
            0,
            $jours - 1
        );

        /*
        |--------------------------------------------------------------------------
        | 7. MONTANTS DU BARÈME
        |--------------------------------------------------------------------------
        */

        $fraisJournalier = (float) $bareme->frais_mission;

        $indemniteParNuit = (float)
            $bareme->indemnite_forfaitaire;

        /*
        |--------------------------------------------------------------------------
        | 8. CALCUL DES FRAIS
        |--------------------------------------------------------------------------
        |
        | frais_mission = montant par jour
        |
        */

        $frais = $fraisJournalier * $jours;

        /*
        |--------------------------------------------------------------------------
        | 9. CALCUL DE L'INDEMNITÉ
        |--------------------------------------------------------------------------
        |
        | indemnite_forfaitaire = montant par nuit
        |
        */

        $indemnite =
            $indemniteParNuit * $nombreNuits;

        /*
        |--------------------------------------------------------------------------
        | 10. SOUS-TOTAL
        |--------------------------------------------------------------------------
        |
        | Sous-total = Frais + Indemnité
        |
        */

        $sousTotal =
            $frais + $indemnite;

        /*
        |--------------------------------------------------------------------------
        | 11. TAUX DE MAJORATION
        |--------------------------------------------------------------------------
        |
        | La majoration concerne uniquement les missions
        | internationales.
        |
        */

        $tauxMajoration = 0;

        if (
            $mission->type === 'internationale'
            && $mission->pays
            && $mission->pays->zoneMajoration
        ) {

            $tauxMajoration = (float)
                $mission->pays
                    ->zoneMajoration
                    ->taux;
        }

        /*
        |--------------------------------------------------------------------------
        | 12. MONTANT DE LA MAJORATION
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        |
        | La majoration est calculée sur le SOUS-TOTAL.
        |
        | Majoration =
        | Sous-total × taux / 100
        |
        */

        $montantMajoration = 0;

        if (
            $mission->type === 'internationale'
            && $tauxMajoration > 0
        ) {

            $montantMajoration =
                $sousTotal *
                ($tauxMajoration / 100);
        }

        /*
        |--------------------------------------------------------------------------
        | 13. TOTAL GÉNÉRAL
        |--------------------------------------------------------------------------
        |
        | Nationale :
        |   Total = Sous-total
        |
        | Internationale :
        |   Total = Sous-total + Majoration
        |
        */

        $montantTotal =
            $sousTotal +
            $montantMajoration;

        /*
        |--------------------------------------------------------------------------
        | 14. ARRONDIS
        |--------------------------------------------------------------------------
        */

        $frais = round(
            $frais,
            2
        );

        $indemnite = round(
            $indemnite,
            2
        );

        $sousTotal = round(
            $sousTotal,
            2
        );

        $montantMajoration = round(
            $montantMajoration,
            2
        );

        $montantTotal = round(
            $montantTotal,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | 15. RETOUR
        |--------------------------------------------------------------------------
        */

        return [

            /*
            | Informations sur la durée
            */

            'nombre_jours' =>
                $jours,

            'nombre_nuits' =>
                $nombreNuits,

            /*
            | Barème
            */

            'frais_journalier' =>
                round(
                    $fraisJournalier,
                    2
                ),

            'indemnite_journaliere' =>
                round(
                    $indemniteParNuit,
                    2
                ),

            /*
            | Montants calculés
            */

            'montant_frais' =>
                $frais,

            'montant_indemnite' =>
                $indemnite,

            /*
            | Sous-total
            */

            'sous_total' =>
                $sousTotal,

            /*
            | Majoration
            */

            'taux_majoration' =>
                round(
                    $tauxMajoration,
                    2
                ),

            'montant_majoration' =>
                $montantMajoration,

            /*
            | Total général
            */

            'montant_total' =>
                $montantTotal,
        ];
    }
}