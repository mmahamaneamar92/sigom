<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    /**
     * Dashboard principal
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Profil connecté
        |--------------------------------------------------------------------------
        */

        $profil = strtolower(trim($user->profil ?? ''));

        $isAdmin = $profil === 'admin';

        /*
        |--------------------------------------------------------------------------
        | Période
        |--------------------------------------------------------------------------
        |
        | Par défaut : année courante
        |
        */

        $dateDebut = $request->filled('date_debut')
            ? Carbon::parse($request->date_debut)->startOfDay()
            : Carbon::now()->startOfYear();

        $dateFin = $request->filled('date_fin')
            ? Carbon::parse($request->date_fin)->endOfDay()
            : Carbon::now()->endOfYear();

        /*
        |--------------------------------------------------------------------------
        | Sécurité période
        |--------------------------------------------------------------------------
        */

        if ($dateDebut->greaterThan($dateFin)) {
            [$dateDebut, $dateFin] = [$dateFin, $dateDebut];
        }

        /*
        |--------------------------------------------------------------------------
        | Requête de base
        |--------------------------------------------------------------------------
        */

        $baseQuery = Mission::query()
            ->whereBetween('date_depart', [
                $dateDebut->toDateString(),
                $dateFin->toDateString()
            ]);

        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES GÉNÉRALES
        |--------------------------------------------------------------------------
        */

        $missionsTotal = (clone $baseQuery)->count();

        $missionsBrouillon = (clone $baseQuery)
            ->where('statut', 'brouillon')
            ->count();

        $missionsAttenteRH = (clone $baseQuery)
            ->where('statut', 'en_attente_rh')
            ->count();

        $missionsAttenteMG = (clone $baseQuery)
            ->where('statut', 'en_attente_moyens_generaux')
            ->count();

        $missionsAttenteRHApresMG = (clone $baseQuery)
            ->where('statut', 'en_attente_rh_apres_mg')
            ->count();

        $missionsAttenteBudget = (clone $baseQuery)
            ->where('statut', 'en_attente_budget')
            ->count();

        $missionsAttenteComptabilite = (clone $baseQuery)
            ->where('statut', 'en_attente_comptabilite')
            ->count();

        $missionsPayees = (clone $baseQuery)
            ->where('statut', 'payee')
            ->count();

        $missionsAnnulees = (clone $baseQuery)
            ->where('statut', 'annulee')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | NATIONAL / INTERNATIONAL
        |--------------------------------------------------------------------------
        */

        $missionsNationales = (clone $baseQuery)
            ->where('type', 'nationale')
            ->count();

        $missionsInternationales = (clone $baseQuery)
            ->where('type', 'internationale')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | FINANCES
        |--------------------------------------------------------------------------
        */

        $montantTotal = (clone $baseQuery)
            ->sum('montant_total');

        $montantComplementaireInternational = (clone $baseQuery)
            ->sum('montant_complementaire_international');

        $montantCarburant = (clone $baseQuery)
            ->sum('montant_carburant');

        $montantTotalGeneral = (clone $baseQuery)
            ->sum('montant_total_general');

        /*
        |--------------------------------------------------------------------------
        | Montant payé
        |--------------------------------------------------------------------------
        */

        $montantPaye = (clone $baseQuery)
            ->where('statut', 'payee')
            ->sum('montant_total_general');

        /*
        |--------------------------------------------------------------------------
        | Montant restant à payer
        |--------------------------------------------------------------------------
        */

        $montantRestant = (clone $baseQuery)
            ->whereNotIn('statut', [
                'payee',
                'annulee'
            ])
            ->sum('montant_total_general');

        /*
        |--------------------------------------------------------------------------
        | MISSIONS PAR STATUT
        |--------------------------------------------------------------------------
        */

        $missionsParStatut = (clone $baseQuery)
            ->select(
                'statut',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('statut')
            ->orderBy('statut')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ÉVOLUTION MENSUELLE
        |--------------------------------------------------------------------------
        */

        $missionsParMois = (clone $baseQuery)
            ->select(
                DB::raw('MONTH(date_depart) as mois'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(DB::raw('MONTH(date_depart)'))
            ->orderBy(DB::raw('MONTH(date_depart)'))
            ->pluck('total', 'mois');

        /*
        |--------------------------------------------------------------------------
        | Toujours afficher les 12 mois
        |--------------------------------------------------------------------------
        */

        $labelsMois = [];
        $donneesMois = [];

        $nomsMois = [
            1 => 'Jan',
            2 => 'Fév',
            3 => 'Mar',
            4 => 'Avr',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juil',
            8 => 'Août',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Déc',
        ];

        for ($i = 1; $i <= 12; $i++) {
            $labelsMois[] = $nomsMois[$i];
            $donneesMois[] = (int) ($missionsParMois[$i] ?? 0);
        }

        /*
        |--------------------------------------------------------------------------
        | MISSIONS RÉCENTES
        |--------------------------------------------------------------------------
        */

        $missionsRecentes = Mission::with([
            'directionRelation',
            'pays',
            'region',
        ])
            ->whereBetween('date_depart', [
                $dateDebut->toDateString(),
                $dateFin->toDateString()
            ])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MISSIONS À TRAITER SELON LE PROFIL
        |--------------------------------------------------------------------------
        */

        $missionsATraiter = collect();

        switch ($profil) {

            case 'direction':

                $missionsATraiter = Mission::with([
                    'directionRelation'
                ])
                    ->where('statut', 'brouillon')
                    ->orderByDesc('created_at')
                    ->limit(8)
                    ->get();

                break;

            case 'rh':

                $missionsATraiter = Mission::with([
                    'directionRelation'
                ])
                    ->whereIn('statut', [
                        'en_attente_rh',
                        'en_attente_rh_apres_mg'
                    ])
                    ->orderByDesc('created_at')
                    ->limit(8)
                    ->get();

                break;

            case 'moyens_generaux':

                $missionsATraiter = Mission::with([
                    'directionRelation'
                ])
                    ->where('statut', 'en_attente_moyens_generaux')
                    ->orderByDesc('created_at')
                    ->limit(8)
                    ->get();

                break;

            case 'budget':

                $missionsATraiter = Mission::with([
                    'directionRelation'
                ])
                    ->where('statut', 'en_attente_budget')
                    ->orderByDesc('created_at')
                    ->limit(8)
                    ->get();

                break;

            case 'comptabilite':

                $missionsATraiter = Mission::with([
                    'directionRelation'
                ])
                    ->where('statut', 'en_attente_comptabilite')
                    ->orderByDesc('created_at')
                    ->limit(8)
                    ->get();

                break;

            case 'admin':

                $missionsATraiter = Mission::with([
                    'directionRelation'
                ])
                    ->whereNotIn('statut', [
                        'payee',
                        'annulee'
                    ])
                    ->orderByDesc('created_at')
                    ->limit(8)
                    ->get();

                break;
        }

        /*
        |--------------------------------------------------------------------------
        | Libellés des statuts
        |--------------------------------------------------------------------------
        */

        $libellesStatuts = [
            'brouillon' => 'Brouillon',
            'en_attente_rh' => 'En attente RH',
            'en_attente_moyens_generaux' => 'En attente moyens généraux',
            'en_attente_rh_apres_mg' => 'Retour RH',
            'en_attente_budget' => 'En attente budget',
            'en_attente_comptabilite' => 'En attente comptabilité',
            'payee' => 'Payée',
            'annulee' => 'Annulée',
        ];

        /*
        |--------------------------------------------------------------------------
        | Données graphiques
        |--------------------------------------------------------------------------
        */

        $statutsLabels = [];
        $statutsData = [];

        foreach ($missionsParStatut as $statut) {
            $statutsLabels[] =
                $libellesStatuts[$statut->statut]
                ?? ucfirst(str_replace('_', ' ', $statut->statut));

            $statutsData[] = (int) $statut->total;
        }

        /*
        |--------------------------------------------------------------------------
        | Pourcentage paiement
        |--------------------------------------------------------------------------
        */

        $tauxPaiement = $montantTotalGeneral > 0
            ? round(
                ($montantPaye / $montantTotalGeneral) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Retour vue
        |--------------------------------------------------------------------------
        */

        return view('dashboard.index', compact(
            'profil',
            'isAdmin',

            'dateDebut',
            'dateFin',

            'missionsTotal',
            'missionsBrouillon',
            'missionsAttenteRH',
            'missionsAttenteMG',
            'missionsAttenteRHApresMG',
            'missionsAttenteBudget',
            'missionsAttenteComptabilite',
            'missionsPayees',
            'missionsAnnulees',

            'missionsNationales',
            'missionsInternationales',

            'montantTotal',
            'montantComplementaireInternational',
            'montantCarburant',
            'montantTotalGeneral',
            'montantPaye',
            'montantRestant',
            'tauxPaiement',

            'labelsMois',
            'donneesMois',

            'statutsLabels',
            'statutsData',

            'missionsRecentes',
            'missionsATraiter',

            'libellesStatuts'
        ));
    }

    public function rapportAgentsPdf(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | PÉRIODE
    |--------------------------------------------------------------------------
    */

    $dateDebut = $request->filled('date_debut')
        ? Carbon::parse($request->date_debut)->startOfDay()
        : Carbon::now()->startOfYear();

    $dateFin = $request->filled('date_fin')
        ? Carbon::parse($request->date_fin)->endOfDay()
        : Carbon::now()->endOfDay();


    /*
    |--------------------------------------------------------------------------
    | AGENTS — CLASSEMENT PAR MONTANT TOTAL
    |--------------------------------------------------------------------------
    |
    | Le montant est pris dans mission_agents.montant_total.
    |
    */

    $agents = DB::table('mission_agents')

        ->join(
            'missions',
            'missions.id',
            '=',
            'mission_agents.mission_id'
        )

        ->join(
            'agents',
            'agents.id',
            '=',
            'mission_agents.agent_id'
        )

        ->leftJoin(
            'services',
            'services.nom_service',
            '=',
            'agents.service'
        )

        ->leftJoin(
            'directions',
            'directions.nom_direction',
            '=',
            'services.direction'
        )

        ->whereBetween(
            'missions.date_depart',
            [
                $dateDebut->toDateString(),
                $dateFin->toDateString()
            ]
        )

        ->select(

            'agents.id',

            'agents.matricule',

            'agents.prenom',

            'agents.nom',

            'agents.poste',

            'services.nom_service',

            'directions.nom_direction',

            DB::raw(
                'COUNT(DISTINCT mission_agents.mission_id) AS nombre_missions'
            ),

            DB::raw(
                'COALESCE(SUM(mission_agents.montant_total), 0) AS montant_total'
            )
        )

        ->groupBy(

            'agents.id',

            'agents.matricule',

            'agents.prenom',

            'agents.nom',

            'agents.poste',

            'services.nom_service',

            'directions.nom_direction'
        )

        /*
        |--------------------------------------------------------------------------
        | PLUS GROS MONTANTS EN PREMIER
        |--------------------------------------------------------------------------
        */

        ->orderByDesc('montant_total')

        ->get();


    /*
    |--------------------------------------------------------------------------
    | TOTAL GÉNÉRAL
    |--------------------------------------------------------------------------
    */

    $montantTotal = $agents->sum(
        fn ($agent) => (float) $agent->montant_total
    );

    $nombreMissions = $agents->sum(
        fn ($agent) => (int) $agent->nombre_missions
    );


    /*
|--------------------------------------------------------------------------
| RÉPARTITION PAR DIRECTION
|--------------------------------------------------------------------------
|
| Une participation = un agent affecté à une mission.
| Le nombre de missions représente donc le nombre de
| participations des agents de la direction.
|
*/

$directions = DB::table('mission_agents')

    ->join(
        'missions',
        'missions.id',
        '=',
        'mission_agents.mission_id'
    )

    ->join(
        'agents',
        'agents.id',
        '=',
        'mission_agents.agent_id'
    )

    ->leftJoin(
        'services',
        'services.nom_service',
        '=',
        'agents.service'
    )

    ->leftJoin(
        'directions',
        'directions.nom_direction',
        '=',
        'services.direction'
    )

    ->whereBetween(
        'missions.date_depart',
        [
            $dateDebut->toDateString(),
            $dateFin->toDateString()
        ]
    )

    ->select(

        DB::raw("
            COALESCE(
                directions.nom_direction,
                'Direction non renseignée'
            ) AS nom_direction
        "),

        /*
        |--------------------------------------------------------------------------
        | Nombre d'agents distincts
        |--------------------------------------------------------------------------
        */

        DB::raw("
            COUNT(DISTINCT mission_agents.agent_id)
            AS nombre_agents
        "),

        /*
        |--------------------------------------------------------------------------
        | Nombre de participations aux missions
        |--------------------------------------------------------------------------
        */

        DB::raw("
            COUNT(mission_agents.id)
            AS nombre_missions
        "),

        /*
        |--------------------------------------------------------------------------
        | Total réellement enregistré pour les agents
        |--------------------------------------------------------------------------
        */

        DB::raw("
            COALESCE(
                SUM(mission_agents.montant_total),
                0
            ) AS montant_total
        ")
    )

    ->groupBy(
        'directions.nom_direction'
    )

    ->orderByDesc('montant_total')

    ->get();


/*
|--------------------------------------------------------------------------
| TOTAUX DU TABLEAU DES DIRECTIONS
|--------------------------------------------------------------------------
*/

$nombreAgentsDirections = $directions->sum(
    fn ($direction) => (int) $direction->nombre_agents
);

$nombreParticipationsDirections = $directions->sum(
    fn ($direction) => (int) $direction->nombre_missions
);

$montantTotalDirections = $directions->sum(
    fn ($direction) => (float) $direction->montant_total
);


    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

    $pdf = Pdf::loadView(
        'dashboard.rapport-agents-pdf',
        compact(
            'agents',
            'directions',
            'dateDebut',
            'dateFin',
            'montantTotal',
            'nombreMissions',
            'nombreAgentsDirections',
            'nombreParticipationsDirections',
            'montantTotalDirections'
        )
    );

    return $pdf
        ->setPaper('a4', 'landscape')
        ->stream(
            'rapport-agents-missions-' .
            $dateDebut->format('Ymd') .
            '-' .
            $dateFin->format('Ymd') .
            '.pdf'
        );
}
}