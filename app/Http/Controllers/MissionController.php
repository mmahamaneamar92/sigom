<?php
namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Direction;
use App\Models\Mission;
use App\Models\MissionAgent;
use App\Models\MissionBudgetInternational;
use App\Models\Pays;
use App\Models\Region;
use App\Models\Voiture;
use App\Services\MissionCalculatorService;
use App\Models\MissionInitiation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;

class MissionController extends Controller
{
    protected MissionCalculatorService $calculator;

    public function __construct(
        MissionCalculatorService $calculator
    ) {
        $this->calculator = $calculator;
    }


/*
|--------------------------------------------------------------------------
| CREATE
|--------------------------------------------------------------------------
*/


public function create(Request $request)
{
    
    $user = auth()->user();

    abort_unless($user, 401, 'Veuillez vous connecter.');

    // Définir le profil avant toute utilisation.
    $profil = strtolower(trim($user->profil ?? ''));

    // Déterminer les profils autorisés selon le contexte.
    if ($request->filled('initiation_id')) {
        abort_unless(
            in_array($profil, ['controle_gestion', 'admin'], true),
            403,
            'Profil non autorisé à traiter une initiation : ' . $profil
        );

        $initiation = \App\Models\MissionInitiation::findOrFail(
            $request->integer('initiation_id')
        );

        abort_unless(
            $initiation->statut === 'en_attente_cg',
            403,
            'Cette initiation n’est plus en attente du Contrôle de gestion.'
        );

        abort_if(
            $initiation->mission()->exists(),
            409,
            'Une mission est déjà associée à cette initiation.'
        );
    } else {
        // Création classique d'une mission.
        abort_unless(
            in_array($profil, ['direction', 'admin'], true),
            403,
            'Vous n’êtes pas autorisé à créer une mission.'
        );

        $initiation = null;
    }

    $directions = \App\Models\Direction::orderBy(
        'nom_direction'
    )->get();

    $pays = \App\Models\Pays::with([
        'regions',
        'zoneMajoration',
    ])->orderBy('nom')->get();

    $agents = \App\Models\Agent::with('serviceRelation')
        ->orderBy('nom')
        ->orderBy('prenom')
        ->get();

    // Préparer les pays et leurs régions pour le formulaire.
$paysRegions = $pays->map(function ($pay) {
    return [
        'id' => $pay->id,
        'regions' => $pay->regions->map(function ($region) {
            return [
                'id' => $region->id,
                'nom' => $region->nom_region ?? $region->nom ?? '',
            ];
        })->values()->all(),
    ];
})->values()->all();

    return view('missions.create', compact(
        'directions',
        'pays',
        'agents',
        'paysRegions',
        'initiation'
    ));
}



    
/*
|--------------------------------------------------------------------------
| INDEX
|--------------------------------------------------------------------------
*/

public function index(Request $request)
{
    $user = auth()->user();

    if (!$user) {
        abort(401);
    }

    $profil = strtolower(trim($user->profil ?? ''));

    /*
    |--------------------------------------------------------------------------
    | 1. REQUÊTE DES MISSIONS OPÉRATIONNELLES
    |--------------------------------------------------------------------------
    */

    $query = Mission::with([
        'pays',
        'region',
        'voiture',
        'chauffeur',
        'missionAgents.agent',
        'directionRelation',
        'commentaires.user',
        'initiation',
    ]);

    /*
    |--------------------------------------------------------------------------
    | 2. RESTRICTION PAR PROFIL ET STATUT
    |--------------------------------------------------------------------------
    */

    switch ($profil) {
        case 'admin':
            // L'administrateur peut consulter toutes les missions.
            break;

        case 'direction':

    $nomDirection = $user->direction->nom_direction ?? null;

    if (!$nomDirection) {
        $query->whereRaw('1 = 0');
    } else {
        $query->where('direction', $nomDirection)
              ->whereIn('statut', [
                  'brouillon',
                  'en_attente_service_demandeur',
              ]);
    }

    break;

        case 'cg':
case 'controle_gestion':
    $query->where('statut', 'en_attente_cg');
    break;

        case 'dga':
            $query->where('statut', 'en_attente_visa_dga');
            break;

        case 'dagrh':
            $query->where('statut', 'en_attente_visa_dagrh');
            break;

        case 'moyens_generaux':
            $query->where('statut', 'en_attente_mg');
            break;

        case 'rh':
            // Statuts du circuit historique et du nouveau circuit.
            $query->whereIn('statut', [
                'en_attente_rh',
                'en_attente_rh_apres_mg',
                'en_attente_rh_nouveau_circuit',
            ]);
            break;

        case 'budget':
            $query->where('statut', 'en_attente_budget');
            break;

        case 'comptabilite':
            $query->where('statut', 'en_attente_comptabilite');
            break;

        default:
            // Profil non reconnu : aucune mission affichée.
            $query->whereRaw('1 = 0');
            break;
    }

    /*
    |--------------------------------------------------------------------------
    | 3. FILTRE STATUT
    |--------------------------------------------------------------------------
    |
    | Seul l'administrateur peut choisir librement le statut.
    | Les autres profils restent limités par les règles ci-dessus.
    */

    if ($profil === 'admin' && $request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    /*
    |--------------------------------------------------------------------------
    | 4. RECHERCHE GÉNÉRALE
    |--------------------------------------------------------------------------
    |
    | La colonne numero remplace l'ancienne colonne numero_ordre.
    */

    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('numero', 'like', "%{$search}%")
              ->orWhere('objet', 'like', "%{$search}%")
              ->orWhere('destination', 'like', "%{$search}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | 5. FILTRE TYPE
    |--------------------------------------------------------------------------
    */

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    /*
    |--------------------------------------------------------------------------
    | 6. FILTRE DIRECTION
    |--------------------------------------------------------------------------
    |
    | Pour un utilisateur de direction, la restriction de sa direction
    | reste prioritaire sur le filtre transmis dans l'URL.
    */

    if ($profil === 'admin' && $request->filled('direction')) {
        $query->where('direction', $request->direction);
    }

    /*
    |--------------------------------------------------------------------------
    | 7. FILTRE DESTINATION
    |--------------------------------------------------------------------------
    */

    if ($request->filled('destination')) {
        $query->where(
            'destination',
            'like',
            '%' . trim($request->destination) . '%'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 8. FILTRE DATE DE DÉPART
    |--------------------------------------------------------------------------
    */

    if ($request->filled('date_depart_du')) {
        try {
            $date = \Carbon\Carbon::parse(
                $request->date_depart_du
            )->toDateString();

            $query->whereDate('date_depart', '>=', $date);
        } catch (\Throwable $e) {
            // Une date invalide est ignorée.
        }
    }

    if ($request->filled('date_depart_au')) {
        try {
            $date = \Carbon\Carbon::parse(
                $request->date_depart_au
            )->toDateString();

            $query->whereDate('date_depart', '<=', $date);
        } catch (\Throwable $e) {
            // Une date invalide est ignorée.
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 9. PAGINATION DES MISSIONS
    |--------------------------------------------------------------------------
    */

    $missions = $query
        ->latest('id')
        ->paginate(15, ['*'], 'missions_page')
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | 10. INITIATIONS DES MISSIONS NATIONALES
    |--------------------------------------------------------------------------
    |
    | ADMIN :
    |   Toutes les initiations.
    |
    | DIRECTION :
    |   Uniquement ses propres initiations.
    |
    | CG :
    |   Les initiations transmises au CG.
    */

    $initiations = collect();

    
if (class_exists(\App\Models\MissionInitiation::class)) {
    $initiationQuery = \App\Models\MissionInitiation::with([
        'demandeur',
        'mission',
    ])->latest('id');

    switch ($profil) {
        case 'direction':
            // La Direction voit ses propres initiations.
            $initiationQuery->where('user_id', $user->id);
            break;

        case 'cg':
        case 'controle_gestion':
            // Contrôle de gestion
            $initiationQuery->whereIn('statut', [
                'en_attente_cg',
            ]);
            break;

        case 'dga':
            // Direction générale adjointe
            $initiationQuery->whereIn('statut', [
                'en_attente_visa_dga',
            ]);
            break;

        case 'dagrh':
            // Direction de l'administration et des RH
            $initiationQuery->whereIn('statut', [
                'en_attente_visa_dagrh',
            ]);
            break;

        case 'moyens_generaux':
            // Moyens généraux
            $initiationQuery->whereIn('statut', [
                'en_attente_mg',
                'en_attente_moyens_generaux',
            ]);
            break;

        case 'rh':
        case 'ressources_humaines':
            // Ressources humaines
            $initiationQuery->whereIn('statut', [
                'en_attente_rh',
                'en_attente_rh_apres_mg',
            ]);
            break;

        case 'budget':
            // Service du budget
            $initiationQuery->whereIn('statut', [
                'en_attente_budget',
            ]);
            break;

        case 'comptabilite':
        case 'comptabilité':
            // Comptabilité
            $initiationQuery->whereIn('statut', [
                'en_attente_comptabilite',
            ]);
            break;

        case 'admin':
            // L'administrateur voit toutes les initiations.
            break;

        default:
            // Tout autre profil n'a pas accès à cette liste.
            $initiationQuery->whereRaw('1 = 0');
            break;
    }

    $initiations = $initiationQuery
        ->paginate(10, ['*'], 'initiations_page')
        ->withQueryString();
}

    /*
    |--------------------------------------------------------------------------
    | 11. DIRECTIONS
    |--------------------------------------------------------------------------
    */

    $directions = \App\Models\Direction::orderBy(
        'nom_direction'
    )->get();

    /*
    |--------------------------------------------------------------------------
    | 12. CHAUFFEURS
    |--------------------------------------------------------------------------
    */

    $chauffeurs = \App\Models\Agent::orderBy('nom')
        ->orderBy('prenom')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | 13. AFFICHAGE
    |--------------------------------------------------------------------------
    */

    return view('missions.index', compact(
        'missions',
        'initiations',
        'directions',
        'chauffeurs'
    ));
}


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    
public function store(Request $request)
{
    $this->verifierProfil([
        'controle_gestion',
        'admin',
    ]);

    /*
    |--------------------------------------------------------------------------
    | 1. VALIDATION DU FORMULAIRE
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'numero' => [
            'nullable',
            'string',
            'max:100',
            'unique:missions,numero',
        ],

        'objet' => [
            'required',
            'string',
            'max:1000',
        ],

        'direction' => [
            'required',
            'string',
            'max:255',
        ],

        'type' => [
            'required',
            Rule::in(['nationale', 'internationale']),
        ],

        'pays_id' => [
            Rule::requiredIf(
                $request->input('type') === 'internationale'
            ),
            'nullable',
            'integer',
            'exists:pays,id',
        ],

        'region_id' => [
            'required',
            'integer',
            'exists:regions,id',
        ],

        'destination' => [
            'required',
            'string',
            'max:255',
        ],

        'date_depart' => [
            'required',
            'date_format:Y-m-d',
        ],

        'date_retour' => [
            'required',
            'date_format:Y-m-d',
            'after_or_equal:date_depart',
        ],

        'mission_initiation_id' => [
            'nullable',
            'integer',
            'exists:mission_initiations,id',
        ],

        'agent_ids' => [
            'required',
            'array',
            'min:1',
        ],

        'agent_ids.*' => [
            'required',
            'integer',
            'distinct',
            'exists:agents,id',
        ],
    ], [
        'objet.required' => 'L’objet de la mission est obligatoire.',
        'direction.required' => 'Veuillez sélectionner une direction.',
        'type.required' => 'Le type de mission est obligatoire.',
        'pays_id.required' => 'Veuillez sélectionner le pays de destination.',
        'region_id.required' => 'Veuillez sélectionner une région.',
        'destination.required' => 'Veuillez sélectionner un cercle.',
        'date_depart.required' => 'La date de départ est obligatoire.',
        'date_retour.required' => 'La date de retour est obligatoire.',
        'date_retour.after_or_equal' =>
            'La date de retour doit être supérieure ou égale à la date de départ.',
        'agent_ids.required' =>
            'Veuillez sélectionner au moins un participant.',
        'agent_ids.min' =>
            'Veuillez sélectionner au moins un participant.',
        'agent_ids.*.distinct' =>
            'Un même agent ne peut pas être sélectionné plusieurs fois.',
    ]);

    try {
        /*
        |--------------------------------------------------------------------------
        | 2. IDENTIFIANTS ET NORMALISATION
        |--------------------------------------------------------------------------
        */

        $agentIds = array_values(array_unique(
            array_map('intval', $validated['agent_ids'])
        ));

        $regionId = (int) $validated['region_id'];

        /*
        |--------------------------------------------------------------------------
        | 3. DÉTERMINATION DU PAYS
        |--------------------------------------------------------------------------
        */

        if ($validated['type'] === 'nationale') {
            $mali = Pays::query()
                ->whereRaw('LOWER(TRIM(nom)) = ?', ['mali'])
                ->first();

            if (!$mali) {
                return $this->reponseErreurValidation(
                    $request,
                    'Le pays Mali est introuvable dans la table des pays.',
                    'pays_id'
                );
            }

            $paysId = (int) $mali->id;
        } else {
            $paysId = (int) $validated['pays_id'];
        }

        /*
        |--------------------------------------------------------------------------
        | 4. VÉRIFICATION DE LA RÉGION
        |--------------------------------------------------------------------------
        */

        $region = Region::query()
            ->where('id', $regionId)
            ->where('pays_id', $paysId)
            ->first();

        if (!$region) {
            return $this->reponseErreurValidation(
                $request,
                'La région sélectionnée ne correspond pas au pays choisi.',
                'region_id'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. VÉRIFICATION DU CERCLE
        |--------------------------------------------------------------------------
        |
        | Hypothèse du schéma :
        | cercles.id
        | cercles.region_id
        | cercles.nom_cercle
        |
        | Le cercle doit réellement appartenir à la région sélectionnée.
        */

        $cercle = DB::table('cercles')
            ->where('region_id', $regionId)
            ->where('nom_cercle', $validated['destination'])
            ->first();

        if (!$cercle) {
            return $this->reponseErreurValidation(
                $request,
                'Le cercle sélectionné n’appartient pas à la région choisie.',
                'destination'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. CONTRÔLE DES DATES ET DURÉE INCLUSIVE
        |--------------------------------------------------------------------------
        */

        $dateDepart = Carbon::createFromFormat(
            '!Y-m-d',
            $validated['date_depart']
        );

        $dateRetour = Carbon::createFromFormat(
            '!Y-m-d',
            $validated['date_retour']
        );

        if ($dateRetour->lt($dateDepart)) {
            return $this->reponseErreurValidation(
                $request,
                'La date de retour ne peut pas précéder la date de départ.',
                'date_retour'
            );
        }

        // Les jours de départ et de retour sont comptés.
        $duree = (int) $dateDepart->diffInDays($dateRetour) + 1;

        /*
        |--------------------------------------------------------------------------
        | 7. NUMÉRO DE MISSION
        |--------------------------------------------------------------------------
        */

        $numero = !empty($validated['numero'])
            ? trim($validated['numero'])
            : $this->genererNumeroMission();

        /*
        |--------------------------------------------------------------------------
        | 8. CONTRÔLE DES CONFLITS DES AGENTS
        |--------------------------------------------------------------------------
        */

        $this->verifierConflitsAgents(
            $agentIds,
            $dateDepart,
            $dateRetour
        );

        /*
        |--------------------------------------------------------------------------
        | 9. CRÉATION TRANSACTIONNELLE
        |--------------------------------------------------------------------------
        */

        $mission = DB::transaction(function () use (
            $validated,
            $numero,
            $duree,
            $agentIds,
            $regionId,
            $paysId,
            $dateDepart,
            $dateRetour
        ) {
            /*
            | Création de la mission
            */

            $mission = Mission::create([
                'numero' => $numero,
                'objet' => $validated['objet'],
                'direction' => $validated['direction'],
                'type' => $validated['type'],
                'pays_id' => $paysId,

                // Une seule région par mission.
                'region_id' => $regionId,

                // Le cercle choisi dans le formulaire.
                'destination' => $validated['destination'],

                'date_depart' => $dateDepart->toDateString(),
                'date_retour' => $dateRetour->toDateString(),
                'duree' => $duree,

                'mission_initiation_id' =>
                    $validated['mission_initiation_id'] ?? null,

                // Première étape du circuit de validation.
                'statut' => 'en_attente_cg',

                'montant_total' => 0,
            ]);

            /*
            | Charger les données nécessaires au calcul
            */

            $mission->load('pays.zoneMajoration');

            $total = 0;

            /*
            | Enregistrer les participants et leurs indemnités
            */

            $agents = Agent::query()
                ->whereIn('id', $agentIds)
                ->get()
                ->keyBy('id');

            foreach ($agentIds as $agentId) {
                $agent = $agents->get($agentId);

                if (!$agent) {
                    throw new \RuntimeException(
                        "Agent introuvable : {$agentId}"
                    );
                }

                $calcul = $this->calculator->calculer(
                    $agent,
                    $mission,
                    $duree
                );

                MissionAgent::create([
                    'mission_id' => $mission->id,
                    'agent_id' => $agent->id,

                    'nombre_jours' =>
                        $calcul['nombre_jours'],

                    'frais_journalier' =>
                        $calcul['frais_journalier'],

                    'indemnite_journaliere' =>
                        $calcul['indemnite_journaliere'],

                    'taux_majoration' =>
                        $calcul['taux_majoration'],

                    'montant_majoration' =>
                        $calcul['montant_majoration'],

                    'montant_total' =>
                        $calcul['montant_total'],
                ]);

                $total += (float) $calcul['montant_total'];
            }

            /*
            | Mise à jour du budget global
            */

            $mission->update([
                'montant_total' => round($total, 2),
            ]);

            return $mission;
        });

        /*
        |--------------------------------------------------------------------------
        | 10. RÉPONSE
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' =>
                    'Mission créée et transmise au Contrôle général.',
                'mission_id' => $mission->id,
                'numero' => $mission->numero,
                'statut' => $mission->statut,
                'montant_total' => $mission->montant_total,
                'redirect' => route('missions.index'),
            ], 201);
        }

        return redirect()
            ->route('missions.index')
            ->with(
                'success',
                'Mission créée et transmise au Contrôle général.'
            );

    } catch (\Illuminate\Validation\ValidationException $e) {
        throw $e;

    } catch (\Throwable $e) {
        Log::error('Erreur lors de la création SIGOM-CANAM', [
            'message' => $e->getMessage(),
            'fichier' => $e->getFile(),
            'ligne' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        report($e);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de création de la mission.',
                'errors' => config('app.debug')
                    ? ['exception' => [$e->getMessage()]]
                    : null,
            ], 500);
        }

        return back()
            ->withInput()
            ->withErrors([
                'error' =>
                    'Échec de création de la mission. Consultez le journal Laravel.',
            ]);
    }
}


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {

    $user = auth()->user();

$profil = strtolower(trim(
    $user->profil
    ?? $user->role
    ?? ''
));
        $mission = Mission::with([
            'pays',
            'region',
            'pays.zoneMajoration',
            'voiture',
            'chauffeur',
            'carburant',
            'missionAgents.agent.serviceRelation.directionRelation',
            'directionRelation',
            'commentaires.user',
            'budgetsInternationaux.agent',
        ])->findOrFail($id);

        /*
    |--------------------------------------------------------------------------
    | Vérification de l'accès à la mission
    |--------------------------------------------------------------------------
    */

        $this->verifierAccesMission($mission);

        /*
    |--------------------------------------------------------------------------
    | Charger uniquement les agents ayant le poste de chauffeur
    |--------------------------------------------------------------------------
    |
    | Le champ "fonction" de la table agents contient le poste.
    |
    */

/*
|--------------------------------------------------------------------------
| Chauffeurs disponibles pour cette mission
|--------------------------------------------------------------------------
|
| On affiche uniquement :
| - les agents dont la fonction contient "chauffeur"
| - qui ne sont pas déjà affectés à une autre mission
|   pendant la période de la mission actuelle
|
*/

        $chauffeurs = Agent::whereNotNull('fonction')
            ->whereRaw(
                'LOWER(TRIM(poste)) = ?',
                ['Chauffeur']
            )
            ->whereNotExists(function ($query) use ($mission) {

                $query->select(DB::raw(1))
                    ->from('mission_agents')
                    ->join(
                        'missions',
                        'missions.id',
                        '=',
                        'mission_agents.mission_id'
                    )
                    ->whereColumn(
                        'mission_agents.agent_id',
                        'agents.id'
                    )

                /*
            |--------------------------------------------------------------------------
            | Ne pas considérer la mission actuelle
            |--------------------------------------------------------------------------
            */
                    ->where(
                        'missions.id',
                        '!=',
                        $mission->id
                    )

                /*
            |--------------------------------------------------------------------------
            | Les brouillons et missions annulées
            | ne bloquent pas le chauffeur
            |--------------------------------------------------------------------------
            */
                    ->whereNotIn(
                        'missions.statut',
                        [
                            'brouillon',
                            'annulee',
                        ]
                    )

                /*
            |--------------------------------------------------------------------------
            | Vérification du chevauchement des dates
            |--------------------------------------------------------------------------
            |
            | Mission existante :
            | départ <= départ/retour de la nouvelle mission
            |
            | ET
            |
            | retour >= départ de la nouvelle mission
            |--------------------------------------------------------------------------
            */
                    ->whereDate(
                        'missions.date_depart',
                        '<=',
                        $mission->date_retour
                    )
                    ->whereDate(
                        'missions.date_retour',
                        '>=',
                        $mission->date_depart
                    );
            })
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view(
            'missions.show',
            compact(
                'mission',
                'chauffeurs',
                'profil'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $this->verifierProfil(['controle_gestion', 'admin']);

        $mission = Mission::with([
            'missionAgents.agent.serviceRelation.directionRelation',
            'pays',
            'region',
        ])->findOrFail($id);

        if ($mission->statut !== 'en_attente_cg') {
            return redirect()
                ->route('missions.show', $mission->id)
                ->with('error', 'Seules les missions en non transmise par le controle de gestion peuvent être modifiées.');
        }

        $directions = Direction::orderBy('nom_direction')->get();

        $pays = Pays::with('regions')
            ->orderBy('nom')
            ->get();

        $agents = Agent::with('serviceRelation')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $cercles = \Illuminate\Support\Facades\DB::table('cercles')
            ->select('id', 'nom_cercle', 'region_id')
            ->orderBy('nom_cercle')
            ->get();

        return view('missions.edit', compact(
            'mission',
            'directions',
            'pays',
            'agents',
            'cercles'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

    public function update(Request $request, $id)
    {
        $this->verifierProfil([
            'controle_gestion',
            'admin',
        ]);

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. RÉCUPÉRER LA MISSION
            |--------------------------------------------------------------------------
            */

            $mission = Mission::findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | 2. VÉRIFIER LE STATUT
            |--------------------------------------------------------------------------
            */

            if ($mission->statut !== 'en_attente_cg') {

                return $this->erreur(
                    'Seules les missions non transmise par le controle de gestion peuvent être modifiées.',
                    422
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 3. VALIDATION
            |--------------------------------------------------------------------------
            */

            $validated = $request->validate([

                'objet'       => [
                    'required',
                    'string',
                    'max:1000',
                ],

                'direction'   => [
                    'required',
                    'string',
                    'max:255',
                ],

                'type'        => [
                    'required',
                    Rule::in([
                        'nationale',
                        'internationale',
                    ]),
                ],

                'pays_id'     => [
                    'nullable',
                    'integer',
                    'exists:pays,id',
                ],

                'region_id'   => [
                    'nullable',
                    'integer',
                    'exists:regions,id',
                ],

                'destination' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'date_depart' => [
                    'required',
                    'date',
                ],

                'date_retour' => [
                    'required',
                    'date',
                    'after_or_equal:date_depart',
                ],

                'agent_ids'   => [
                    'required',
                    'array',
                    'min:1',
                ],

                'agent_ids.*' => [
                    'integer',
                    'exists:agents,id',
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | 4. PAYS / RÉGION
            |--------------------------------------------------------------------------
            */

            $paysId   = $validated['pays_id'] ?? null;
            $regionId = $validated['region_id'] ?? null;

            /*
            |--------------------------------------------------------------------------
            | MISSION NATIONALE
            |--------------------------------------------------------------------------
            */

            if ($validated['type'] === 'nationale') {

                $mali = Pays::whereRaw(
                    'LOWER(nom) = ?',
                    ['mali']
                )->first();

                if (! $mali) {

                    return $this->erreur(
                        'Le pays "Mali" n’existe pas dans la table des pays.',
                        422
                    );
                }

                $paysId = $mali->id;

                if (empty($regionId)) {

                    return $this->erreur(
                        'Veuillez sélectionner la région de la mission.',
                        422
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | MISSION INTERNATIONALE
            |--------------------------------------------------------------------------
            */

            if ($validated['type'] === 'internationale') {

                if (empty($paysId)) {

                    return $this->erreur(
                        'Le pays est obligatoire pour une mission internationale.',
                        422
                    );
                }

                if (empty($regionId)) {

                    return $this->erreur(
                        'La région est obligatoire pour une mission internationale.',
                        422
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | VÉRIFIER LA COHÉRENCE PAYS / RÉGION
            |--------------------------------------------------------------------------
            */

            if ($paysId && $regionId) {

                $regionExiste = Region::where(
                    'id',
                    $regionId
                )
                    ->where(
                        'pays_id',
                        $paysId
                    )
                    ->exists();

                if (! $regionExiste) {

                    return $this->erreur(
                        'La région sélectionnée ne correspond pas au pays choisi.',
                        422
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 5. DATES
            |--------------------------------------------------------------------------
            */

            $dateDepart = Carbon::parse(
                $validated['date_depart']
            );

            $dateRetour = Carbon::parse(
                $validated['date_retour']
            );

            if ($dateRetour->lt($dateDepart)) {

                return $this->erreur(
                    'La date de retour ne peut pas être antérieure à la date de départ.',
                    422
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 6. DURÉE INCLUSIVE
            |--------------------------------------------------------------------------
            */

            $duree = $dateDepart->diffInDays(
                $dateRetour
            ) + 1;

            /*
            |--------------------------------------------------------------------------
            | 7. AGENTS UNIQUES
            |--------------------------------------------------------------------------
            */

            $agentIds = array_values(
                array_unique(
                    array_map(
                        'intval',
                        $validated['agent_ids']
                    )
                )
            );

            if (empty($agentIds)) {

                return $this->erreur(
                    'Au moins un agent doit participer à la mission.',
                    422
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 8. VÉRIFIER LES CONFLITS
            |--------------------------------------------------------------------------
            |
            | On exclut la mission actuelle.
            |
            */

            $this->verifierConflitsAgents(
                $agentIds,
                $dateDepart,
                $dateRetour,
                $mission->id
            );

            /*
            |--------------------------------------------------------------------------
            | 9. TRANSACTION
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use ($mission, $validated, $paysId, $regionId, $dateDepart, $dateRetour, $duree, $agentIds) {

                /*
                |--------------------------------------------------------------------------
                | METTRE À JOUR LA MISSION
                |--------------------------------------------------------------------------
                */

                $mission->update([

                    'objet'       =>
                    $validated['objet'],

                    'direction'   =>
                    $validated['direction'],

                    'type'        =>
                    $validated['type'],

                    'pays_id'     =>
                    $paysId,

                    'region_id'   =>
                    $regionId,

                    'destination' =>
                    $validated['destination'],

                    'date_depart' =>
                    $dateDepart->format('Y-m-d'),

                    'date_retour' =>
                    $dateRetour->format('Y-m-d'),

                    'duree'       =>
                    $duree,
                ]);

                /*
                |--------------------------------------------------------------------------
                | RECHARGER LA MISSION
                |--------------------------------------------------------------------------
                */

                $mission->refresh();

                $mission->load(
                    'pays.zoneMajoration'
                );

                /*
                |--------------------------------------------------------------------------
                | SUPPRIMER LES ANCIENS PARTICIPANTS
                |--------------------------------------------------------------------------
                */

                MissionAgent::where(
                    'mission_id',
                    $mission->id
                )->delete();

                /*
                |--------------------------------------------------------------------------
                | RECALCULER LES FRAIS
                |--------------------------------------------------------------------------
                */

                $total = 0;

                foreach ($agentIds as $agentId) {

                    $agent = Agent::findOrFail(
                        $agentId
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | CALCUL AUTOMATIQUE
                    |--------------------------------------------------------------------------
                    */

                    $calcul = $this->calculator->calculer(
                        $agent,
                        $mission,
                        $duree
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | ENREGISTRER LE PARTICIPANT
                    |--------------------------------------------------------------------------
                    */

                    MissionAgent::create([

                        'mission_id'            =>
                        $mission->id,

                        'agent_id'              =>
                        $agent->id,

                        'nombre_jours'          =>
                        $calcul['nombre_jours'],

                        'frais_journalier'      =>
                        $calcul['frais_journalier'],

                        'indemnite_journaliere' =>
                        $calcul['indemnite_journaliere'],

                        'taux_majoration'       =>
                        $calcul['taux_majoration'],

                        'montant_majoration'    =>
                        $calcul['montant_majoration'],

                        'montant_total'         =>
                        $calcul['montant_total'],
                    ]);

                    $total += (float)
                        $calcul['montant_total'];
                }

                /*
|--------------------------------------------------------------------------
| TOTAL MISSION
|--------------------------------------------------------------------------
*/

                $montantTotal = round($total, 2);

                $montantComplementaireInternational = round(
                    (float) ($mission->montant_complementaire_international ?? 0),
                    2
                );

                $montantTotalGeneral = round(
                    $montantTotal
                     + $montantComplementaireInternational,
                    2
                );

                $mission->update([

                    'montant_total'         =>
                    $montantTotal,

                    'montant_total_general' =>
                    $montantTotalGeneral,
                ]);

                /*
                |--------------------------------------------------------------------------
                | METTRE À JOUR LE TOTAL
                |--------------------------------------------------------------------------
                */

            });

            /*
            |--------------------------------------------------------------------------
            | 10. RECHARGER LES DONNÉES
            |--------------------------------------------------------------------------
            */

            $mission->refresh();

            /*
            |--------------------------------------------------------------------------
            | 11. RÉPONSE JSON
            |--------------------------------------------------------------------------
            */

            return $this->succes(
                'Mission modifiée avec succès.',
                [
                    'mission_id'    =>
                    $mission->id,

                    'numero'        =>
                    $mission->numero,

                    'duree'         =>
                    $mission->duree,

                    'montant_total' =>
                    $mission->montant_total,

                    'statut'        =>
                    $mission->statut,

                    'redirect'      =>
                    route(
                        'missions.show',
                        $mission->id
                    ),
                ]
            );

        } catch (\Illuminate\Validation\ValidationException $e) {

            /*
            |--------------------------------------------------------------------------
            | ERREURS DE VALIDATION AJAX
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => false,

                'message' =>
                'Veuillez corriger les erreurs du formulaire.',

                'errors'  =>
                $e->errors(),

            ], 422);

        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | ERREUR SERVEUR
            |--------------------------------------------------------------------------
            */

            return $this->erreur(
                $e->getMessage(),
                500
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $this->verifierProfil([
            'direction',
            'admin',
        ]);

        $mission = Mission::findOrFail($id);

        // Une mission déjà transmise ne peut pas être supprimée
        if ($mission->statut !== 'brouillon') {
            return redirect()
                ->route('missions.index')
                ->with(
                    'error',
                    'Seules les missions en brouillon peuvent être supprimées.'
                );
        }

        try {

            DB::transaction(function () use ($mission) {

                // Supprimer les agents associés à la mission
                MissionAgent::where('mission_id', $mission->id)->delete();

                // Supprimer la mission
                $mission->delete();
            });

            return redirect()
                ->route('missions.index')
                ->with(
                    'success',
                    'La mission a été supprimée avec succès.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->route('missions.index')
                ->with(
                    'error',
                    'Impossible de supprimer la mission : ' . $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSMETTRE RH
    |--------------------------------------------------------------------------
    */

    public function transmettreRH($id)
    {
        $this->verifierProfil([
            'direction',
            'admin',
        ]);

        $mission = Mission::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Vérification du statut
        |--------------------------------------------------------------------------
        */

        if ($mission->statut !== 'brouillon') {

            return redirect()
                ->route('missions.index')
                ->with(
                    'error',
                    'Seules les missions en brouillon peuvent être transmises à la RH.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Transmission à la RH
        |--------------------------------------------------------------------------
        */

        try {

            $mission->update([
                'statut' => 'en_attente_rh',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Retour sur la liste des missions
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('missions.index')
                ->with(
                    'success',
                    'La mission ' . $mission->numero . ' a été transmise à la RH avec succès.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->route('missions.index')
                ->with(
                    'error',
                    'Erreur lors de la transmission de la mission : '
                    . $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RH
    |--------------------------------------------------------------------------
    |
    | Le chauffeur est automatiquement ajouté à mission_agents.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RH
    |--------------------------------------------------------------------------
    |
    | NATIONAL :
    | Direction → RH → Moyens Généraux
    |
    | INTERNATIONAL :
    | Direction → RH → Budget
    |
    | Le chauffeur est automatiquement ajouté à mission_agents.
    |
    */

    public function validerRH(Request $request, $id)
    {
        try {

            /*
        |--------------------------------------------------------------------------
        | Vérification du profil
        |--------------------------------------------------------------------------
        */

            $this->verifierProfil(['rh', 'admin']);

            /*
        |--------------------------------------------------------------------------
        | Récupération de la mission
        |--------------------------------------------------------------------------
        */

            $mission = Mission::with([
                'missionAgents.agent',
            ])->findOrFail($id);

            $this->verifierAccesMission($mission);

            /*
        |--------------------------------------------------------------------------
        | Vérification du statut
        |--------------------------------------------------------------------------
        */

            if ($mission->statut !== 'en_attente_rh') {
                return back()->with(
                    'error',
                    'Cette mission n\'est pas en attente de validation par la RH.'
                );
            }

            /*
        |--------------------------------------------------------------------------
        | CAS 1 : MISSION INTERNATIONALE
        |--------------------------------------------------------------------------
        |
        | Pour une mission internationale :
        |
        | - La RH ne désigne PAS de chauffeur.
        | - La RH peut renseigner le numéro d'ordre.
        | - Aucun chauffeur n'est ajouté aux participants à cette étape.
        | - La mission est directement transmise au Budget.
        |
        */

            if ($mission->type === 'internationale') {

                $validated = $request->validate([
                    'numero_ordre' => [
                        'nullable',
                        'string',
                        'max:255',
                    ],
                    'commentaire'  => ['nullable', 'string', 'max:2000'],
                ]);

                DB::beginTransaction();

                /*
             * Le numéro d'ordre peut être renseigné ou laissé vide.
             */
                if (array_key_exists('numero_ordre', $validated)) {
                    $mission->numero_ordre = $validated['numero_ordre'];
                }

                /*
             * AUCUNE affectation de chauffeur ici.
             *
             * On ne modifie donc PAS :
             *
             * $mission->chauffeur_id
             *
             * et on n'ajoute aucun chauffeur dans mission_agents.
             */

                $mission->statut = 'en_attente_budget';

                $mission->save();

                DB::table('mission_commentaires')->insert([
    'mission_id'  => $mission->id,
    'user_id'     => auth()->id(),
    'etape'       => 'RH',
    'action'      => 'validation',
    'commentaire' => $validated['commentaire'] ?? null,
    'created_at'  => now(),
    'updated_at'  => now(),
]);

                // Synchroniser le statut de l'initiation associée
if ($mission->mission_initiation_id) {
    MissionInitiation::where(
        'id',
        $mission->mission_initiation_id
    )->update([
        'statut' => 'en_attente_budget',
        'updated_at' => now(),
    ]);
}

                DB::commit();

                return redirect()
                    ->route('missions.show', $mission->id)
                    ->with(
                        'success',
                        'La mission internationale a été validée par la RH et transmise directement au Budget.'
                    );
            }

            /*
        |--------------------------------------------------------------------------
        | CAS 2 : MISSION NATIONALE
        |--------------------------------------------------------------------------
        |
        | IMPORTANT :
        | Le fonctionnement national reste inchangé.
        |
        | La RH doit :
        | - affecter un chauffeur ;
        | - renseigner le numéro d'ordre ;
        | - le chauffeur est ajouté comme participant ;
        | - ses frais et indemnités sont calculés ;
        | - la mission est transmise aux Moyens généraux.
        |
        */

            $validated = $request->validate([
                'chauffeur_id' => [
                    'required',
                    'exists:agents,id',
                ],
                'commentaire'  => ['nullable', 'string', 'max:2000'],
            ], [
                'chauffeur_id.required' =>
                'Le chauffeur est obligatoire pour une mission nationale.',

                'chauffeur_id.exists'   =>
                'Le chauffeur sélectionné n\'existe pas.',
            ]);

            /*
        |--------------------------------------------------------------------------
        | Vérification du chauffeur
        |--------------------------------------------------------------------------
        */

            $chauffeur = Agent::findOrFail(
                $validated['chauffeur_id']
            );

            /*
        |--------------------------------------------------------------------------
        | Vérification des conflits du chauffeur
        |--------------------------------------------------------------------------
        */

            $conflits = $this->verifierConflitsAgents([$chauffeur->id], Carbon::parse($mission->date_depart), Carbon::parse($mission->date_retour), $mission->id);

            if (! empty($conflits)) {
                return back()->with(
                    'error',
                    'Le chauffeur sélectionné est déjà engagé sur une autre mission pendant cette période.'
                );
            }

            DB::beginTransaction();

            /*
        |--------------------------------------------------------------------------
        | Enregistrement du chauffeur et du numéro d'ordre
        |--------------------------------------------------------------------------
        */

            $mission->chauffeur_id = $chauffeur->id;

            /*
        |--------------------------------------------------------------------------
        | Ajouter le chauffeur comme participant
        |--------------------------------------------------------------------------
        */

            $missionAgent = MissionAgent::where('mission_id', $mission->id)
                ->where('agent_id', $chauffeur->id)
                ->first();

            if (! $missionAgent) {

                $calcul = $this->calculator->calculer(
                    $chauffeur,
                    $mission,
                    $mission->duree
                );

                MissionAgent::create([
                    'mission_id'            => $mission->id,
                    'agent_id'              => $chauffeur->id,

                    'nombre_jours'          =>
                    $calcul['nombre_jours'],

                    'frais_journalier'      =>
                    $calcul['frais_journalier'],

                    'indemnite_journaliere' =>
                    $calcul['indemnite_journaliere'],

                    'taux_majoration'       =>
                    $calcul['taux_majoration'],

                    'montant_majoration'    =>
                    $calcul['montant_majoration'],

                    'montant_total'         =>
                    $calcul['montant_total'],
                ]);

            } else {

                /*
             * Le chauffeur existe déjà comme participant.
             * On recalcule ses montants.
             */

                $calcul = $this->calculator->calculer(
                    $chauffeur,
                    $mission,
                    $mission->duree
                );

                $missionAgent->update([
                    'nombre_jours'          =>
                    $calcul['nombre_jours'],

                    'frais_journalier'      =>
                    $calcul['frais_journalier'],

                    'indemnite_journaliere' =>
                    $calcul['indemnite_journaliere'],

                    'taux_majoration'       =>
                    $calcul['taux_majoration'],

                    'montant_majoration'    =>
                    $calcul['montant_majoration'],

                    'montant_total'         =>
                    $calcul['montant_total'],
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | Recalcul du total des participants
        |--------------------------------------------------------------------------
        */

            $mission->load('missionAgents');

            $mission->montant_total = round(
                $mission->missionAgents->sum(
                    'montant_total'
                ),
                2
            );

            /*
        |--------------------------------------------------------------------------
        | Mission nationale :
        | RH → Moyens généraux
        |--------------------------------------------------------------------------
        */

            $mission->statut = 'en_attente_budget';

            $mission->save();

            DB::table('mission_commentaires')->insert([
    'mission_id'  => $mission->id,
    'user_id'     => auth()->id(),
    'etape'       => 'RH',
    'action'      => 'validation',
    'commentaire' => $validated['commentaire'] ?? null,
    'created_at'  => now(),
    'updated_at'  => now(),
]);

            // Synchroniser le statut de l'initiation associée
if ($mission->mission_initiation_id) {
    MissionInitiation::where(
        'id',
        $mission->mission_initiation_id
    )->update([
        'statut' => 'en_attente_budget',
        'updated_at' => now(),
    ]);
}

            DB::commit();

            return redirect()
                ->route('missions.show', $mission->id)
                ->with(
                    'success',
                    'La mission nationale a été validée par la RH et transmise aux Moyens généraux.'
                );

        } catch (\Throwable $e) {

            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            \Log::error(
                'Erreur lors de la validation RH de la mission.',
                [
                    'mission_id' => $id,
                    'message'    => $e->getMessage(),
                    'file'       => $e->getFile(),
                    'line'       => $e->getLine(),
                ]
            );

            return back()->with(
                'error',
                'Une erreur est survenue lors de la validation RH : '
                . $e->getMessage()
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VÉHICULES DISPONIBLES
    |--------------------------------------------------------------------------
    */

/*
|--------------------------------------------------------------------------
| VÉHICULES DISPONIBLES
|--------------------------------------------------------------------------
|
| Retourne uniquement les véhicules :
| - disponibles
| - non hors service
| - non affectés à une autre mission active
| - sans chevauchement avec les dates de la mission actuelle
|
*/

    public function vehiculesDisponibles($id)
    {
        $this->verifierProfil([
            'moyens_generaux',
            'admin',
        ]);

        $mission = Mission::findOrFail($id);

        /*
    |--------------------------------------------------------------------------
    | Vérifier que la mission est bien au stade Moyens généraux
    |--------------------------------------------------------------------------
    */

        if ($mission->statut !== 'en_attente_moyens_generaux') {
            return response()->json([
                'success'   => false,
                'message'   => 'Cette mission n’est pas en attente des Moyens Généraux.',
                'vehicules' => [],
            ], 422);
        }

        /*
    |--------------------------------------------------------------------------
    | VÉHICULES LIBRES PENDANT TOUTE LA PÉRIODE
    |--------------------------------------------------------------------------
    */

        $vehicules = Voiture::where('disponible', true)

            ->where(
                'etat',
                '!=',
                'hors_service'
            )

            ->whereNotExists(function ($query) use ($mission) {

                $query->select(DB::raw(1))
                    ->from('missions')

                /*
                |------------------------------------------------------------------
                | Le véhicule de l'autre mission doit être le même
                |------------------------------------------------------------------
                */

                    ->whereColumn(
                        'missions.voiture_id',
                        'voitures.id'
                    )

                /*
                |------------------------------------------------------------------
                | Ne pas comparer avec la mission actuelle
                |------------------------------------------------------------------
                */

                    ->where(
                        'missions.id',
                        '!=',
                        $mission->id
                    )

                /*
                |------------------------------------------------------------------
                | Missions considérées comme actives
                |------------------------------------------------------------------
                */

                    ->whereIn(
                        'missions.statut',
                        [
                            'en_attente_rh',
                            'en_attente_moyens_generaux',
                            'en_attente_rh_apres_mg',
                            'en_attente_budget',
                            'en_attente_comptabilite',
                        ]
                    )

                /*
                |------------------------------------------------------------------
                | CHEVAUCHEMENT DES DATES
                |------------------------------------------------------------------
                |
                | Exemple :
                |
                | Mission actuelle : 20 → 25
                |
                | Autre mission :    23 → 28  = CONFLIT
                | Autre mission :    15 → 20  = CONFLIT
                | Autre mission :    26 → 30  = PAS DE CONFLIT
                |
                */

                    ->whereDate(
                        'missions.date_depart',
                        '<=',
                        $mission->date_retour
                    )

                    ->whereDate(
                        'missions.date_retour',
                        '>=',
                        $mission->date_depart
                    );
            })

            ->orderBy('immatriculation')
            ->get();

        return response()->json([
            'success'   => true,
            'vehicules' => $vehicules,
        ]);
    }

/*
|--------------------------------------------------------------------------
| AFFECTER VOITURE
|--------------------------------------------------------------------------
*/

    
public function affecterVoiture(Request $request, $id)
{
    $user = auth()->user();

    if (!$user) {
        abort(401, 'Vous devez être connecté.');
    }

    $profil = strtolower(trim($user->profil ?? ''));

    if (!in_array($profil, ['moyens_generaux', 'mg'], true)) {
        abort(
            403,
            'Seuls les Moyens généraux peuvent affecter un véhicule.'
        );
    }

    $validated = $request->validate([
        'voiture_id' => ['required', 'integer', 'exists:voitures,id'],
        'observation_moyens_generaux' => [
            'nullable',
            'string',
            'max:2000',
        ],
    ]);

    DB::transaction(function () use ($id, $user, $validated) {
        $mission = Mission::whereKey($id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($mission->statut !== 'en_attente_mg') {
            abort(
                422,
                'Cette mission n’est pas en attente de traitement par les Moyens généraux.'
            );
        }

        // Verrouiller le véhicule et vérifier sa disponibilité.
        $voiture = \App\Models\Voiture::whereKey($validated['voiture_id'])
            ->lockForUpdate()
            ->firstOrFail();

        
/*
|--------------------------------------------------------------------------
| Vérifier la disponibilité du véhicule sur la période de la mission
|--------------------------------------------------------------------------
*/

if ($voiture->etat === 'hors_service') {
    abort(422, 'Ce véhicule est hors service.');
}

// Vérifier que les dates de la mission sont renseignées.
if (
    empty($mission->date_depart)
    || empty($mission->date_retour)
) {
    abort(
        422,
        'Les dates de début et de fin de la mission doivent être renseignées.'
    );
}

if ($mission->date_debut > $mission->date_fin) {
    abort(
        422,
        'La date de début ne peut pas être postérieure à la date de fin.'
    );
}

// Rechercher une autre mission utilisant déjà ce véhicule
// pendant une période qui chevauche celle de la mission actuelle.
$chevauchement = Mission::where('voiture_id', $voiture->id)
    ->where('id', '!=', $mission->id)
    ->whereNotIn('statut', [
        'terminee',
        'annulee',
    ])
    ->whereDate('date_depart', '<=', $mission->date_depart)
    ->whereDate('date_retour', '>=', $mission->date_retour)
    ->lockForUpdate()
    ->exists();

if ($chevauchement) {
    abort(
        422,
        'Ce véhicule est déjà affecté à une autre mission pendant cette période. Veuillez sélectionner un autre véhicule ou modifier les dates de la mission.'
    );
}

        /*
         * Enregistrer l'affectation.
         * Le champ voiture_id suppose que ce nom est utilisé
         * dans la table missions.
         */
        $mission->voiture_id = $voiture->id;

        // Enregistrer l'observation si la colonne existe dans missions.
        // Sinon, elle sera conservée dans l'historique ci-dessous.
        $observation = $validated['observation_moyens_generaux'] ?? null;

        $mission->save();

        DB::table('mission_commentaires')->insert([
            'mission_id' => $mission->id,
            'user_id' => $user->id,
            'etape' => 'MOYENS_GENERAUX',
            'action' => 'affectation_vehicule',
            'commentaire' => $observation,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Marquer le véhicule comme indisponible.
        $voiture->disponible = false;
        $voiture->save();
    });

    return redirect()->back()->with(
        'success',
        'Le véhicule a été affecté à la mission avec succès.'
    );
}



public function transmettreRHApresMG(Request $request, $id)
{
    $user = auth()->user();

    if (!$user) {
        abort(401, 'Vous devez être connecté.');
    }

    $profil = strtolower(trim($user->profil ?? ''));

    if (!in_array($profil, ['moyens_generaux', 'mg'], true)) {
        abort(
            403,
            'Seuls les Moyens généraux peuvent transmettre cette mission à la RH.'
        );
    }

    $validated = $request->validate([
        'commentaire' => ['nullable', 'string', 'max:2000'],
    ]);

    DB::transaction(function () use ($id, $user, $validated) {
        $mission = Mission::whereKey($id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($mission->statut !== 'en_attente_mg') {
            abort(
                422,
                'Cette mission n’est pas en attente de traitement par les Moyens généraux.'
            );
        }

        $nouveauStatut = 'en_attente_rh';

        DB::table('mission_commentaires')->insert([
            'mission_id' => $mission->id,
            'user_id' => $user->id,
            'etape' => 'MOYENS_GENERAUX',
            'action' => 'transmission',
            'commentaire' => $validated['commentaire'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mission->statut = $nouveauStatut;
        $mission->save();

        if ($mission->mission_initiation_id) {
            DB::table('mission_initiations')
                ->where('id', $mission->mission_initiation_id)
                ->update([
                    'statut' => $nouveauStatut,
                    'updated_at' => now(),
                ]);
        }
    });

    return redirect()->back()->with(
        'success',
        'La mission a été transmise aux Ressources humaines avec succès.'
    );
}



    /*
    |--------------------------------------------------------------------------
    | RETOUR RH APRÈS MOYENS GÉNÉRAUX
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ENVOYER BUDGET
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ENVOYER BUDGET
    |--------------------------------------------------------------------------
    |
    | Cette méthode concerne UNIQUEMENT les missions nationales.
    |
    | NATIONAL :
    | RH après MG → Budget
    |
    | INTERNATIONAL :
    | RH → Budget directement
    | donc cette méthode n'est jamais utilisée.
    |
    */

    public function envoyerBudget($id)
    {
        $this->verifierProfil([
            'rh',
            'admin',
        ]);

        $mission = Mission::with([
            'missionAgents.agent',
            'pays.zoneMajoration',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | UNE MISSION INTERNATIONALE NE PASSE PAS PAR CETTE MÉTHODE
        |--------------------------------------------------------------------------
        */

        if ($mission->type === 'internationale') {

            return $this->erreur(
                'Une mission internationale est transmise directement au Budget après validation RH.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUT NATIONAL
        |--------------------------------------------------------------------------
        */

        if ($mission->statut !== 'en_attente_rh_apres_mg') {

            return $this->erreur(
                'Cette mission n’est pas prête à être envoyée au Budget.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CHAUFFEUR
        |--------------------------------------------------------------------------
        */

        if (! $mission->chauffeur_id) {

            return $this->erreur(
                'Le chauffeur doit être défini.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VÉHICULE
        |--------------------------------------------------------------------------
        */

        if (! $mission->voiture_id) {

            return $this->erreur(
                'Un véhicule doit être affecté.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PARTICIPANTS
        |--------------------------------------------------------------------------
        */

        if ($mission->missionAgents->isEmpty()) {

            return $this->erreur(
                'La mission ne possède aucun participant.'
            );
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | RECALCUL
            |--------------------------------------------------------------------------
            */

            $this->recalculerMission(
                $mission
            );

            if (
                (float) $mission->fresh()->montant_total <= 0
            ) {

                return $this->erreur(
                    'Le montant total de la mission doit être supérieur à zéro.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | ENVOI AU BUDGET
            |--------------------------------------------------------------------------
            */

            $mission->update([
                'statut' => 'en_attente_budget',
            ]);

        } catch (Throwable $e) {

            return $this->erreur(
                $e->getMessage()
            );
        }

        return redirect()
            ->route('missions.index')
            ->with(
                'success',
                'La mission ' .
                $mission->numero .
                ' a été envoyée au Budget avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION BUDGET
    |--------------------------------------------------------------------------
    */

    /**
     * Valider le budget
     *
     * Pour une mission nationale :
     * - saisie du nombre de voitures
     * - quantité de carburant
     * - quantité de carburant ville
     * - prix du litre
     * - calcul automatique des montants
     * - calcul du total carburant
     * - calcul du total général
     *
     * Pour une mission internationale :
     * - aucun carburant
     * - total général = total mission
     */
    public function validerBudget($id)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | 1. Vérifier le statut de la mission
            |--------------------------------------------------------------------------
            */
            /*  |--------------------------------------------------------------------------
                | 1. Récupérer explicitement la mission
                |--------------------------------------------------------------------------
                */
            $mission = Mission::with([
                'carburant',
                'missionAgents.agent',
            ])->findOrFail($id);

            if ($mission->statut !== 'en_attente_budget') {

                return back()->with(
                    'error',
                    'Cette mission ne peut pas être validée par le Budget dans son état actuel.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Transaction
            |--------------------------------------------------------------------------
            */
            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | 3. MISSION NATIONALE
            |--------------------------------------------------------------------------
            */
            if ($mission->type === 'nationale') {

                /*
                |--------------------------------------------------------------------------
                | Vérifier que le carburant a été enregistré
                |--------------------------------------------------------------------------
                */
                $carburant = $mission->carburant()->first();

                if (! $carburant) {

                    DB::rollBack();

                    return back()->with(
                        'error',
                        'Impossible de valider le budget : les informations carburant doivent être enregistrées avant la validation.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Recalcul serveur du carburant
                |--------------------------------------------------------------------------
                | On ne fait pas confiance aux montants envoyés par le navigateur.
                |--------------------------------------------------------------------------
                */

                $quantiteCarburant =
                (float) $carburant->quantite_carburant;

                $quantiteCarburantVille =
                (float) $carburant->quantite_carburant_ville;

                $prixLitre =
                (float) $carburant->prix_litre;

                /*
                |--------------------------------------------------------------------------
                | Montant carburant
                |--------------------------------------------------------------------------
                */

                $montantCarburant =
                    round(
                    $quantiteCarburant * $prixLitre,
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | Montant carburant ville
                |--------------------------------------------------------------------------
                */

                $montantCarburantVille =
                    round(
                    $quantiteCarburantVille * $prixLitre,
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | Sous-total carburant
                |--------------------------------------------------------------------------
                */

                $sousTotalCarburant =
                    round(
                    $montantCarburant +
                    $montantCarburantVille,
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | Mettre à jour la table mission_carburants
                |--------------------------------------------------------------------------
                */

                $carburant->update([

                    'montant_carburant'       =>
                    $montantCarburant,

                    'montant_carburant_ville' =>
                    $montantCarburantVille,

                    'sous_total'              =>
                    $sousTotalCarburant,

                ]);

                /*
                |--------------------------------------------------------------------------
                | Montant général de la mission
                |--------------------------------------------------------------------------
                */

                $montantMission =
                (float) $mission->montant_total;

                $montantTotalGeneral =
                    round(
                    $montantMission +
                    $sousTotalCarburant,
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | Mise à jour de la table missions
                |--------------------------------------------------------------------------
                */

                $mission->montant_carburant =
                    $sousTotalCarburant;

                $mission->montant_total_general =
                    $montantTotalGeneral;

                /*
                |--------------------------------------------------------------------------
                | Passage à la Comptabilité
                |--------------------------------------------------------------------------
                */

                $mission->statut =
                    'en_attente_comptabilite';

                $mission->save();

        if ($mission->mission_initiation_id) {
            DB::table('mission_initiations')
                ->where('id', $mission->mission_initiation_id)
                ->update([
                    'statut' => 'en_attente_comptabilite',
                    'updated_at' => now(),
                ]);
        }

            }

            /*
            |--------------------------------------------------------------------------
            | 4. MISSION INTERNATIONALE
            |--------------------------------------------------------------------------
            */else {

                /*
                |--------------------------------------------------------------------------
                | Une mission internationale ne prend pas de carburant
                |--------------------------------------------------------------------------
                */

                $mission->montant_carburant =
                    0;

                $mission->montant_total_general =
                (float) $mission->montant_total
                 + (float) ($mission->montant_complementaire_international ?? 0);

                /*
                |--------------------------------------------------------------------------
                | Passage à la Comptabilité
                |--------------------------------------------------------------------------
                */

                $mission->statut =
                    'en_attente_comptabilite';

                $mission->save();

        if ($mission->mission_initiation_id) {
            DB::table('mission_initiations')
                ->where('id', $mission->mission_initiation_id)
                ->update([
                    'statut' => 'en_attente_comptabilite',
                    'updated_at' => now(),
                ]);
        }
            }

            /*
            |--------------------------------------------------------------------------
            | 5. Valider la transaction
            |--------------------------------------------------------------------------
            */

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | 6. Message de confirmation
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('missions.show', $mission->id)
                ->with(
                    'success',
                    'Le budget de la mission a été validé avec succès. La mission est maintenant transmise à la Comptabilité.'
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Annuler la transaction en cas d'erreur
            |--------------------------------------------------------------------------
            */

            DB::rollBack();

            /*
            |--------------------------------------------------------------------------
            | Journaliser l'erreur
            |--------------------------------------------------------------------------
            */

            \Log::error(
                'Erreur lors de la validation du budget de la mission',
                [
                    'mission_id' => $mission->id,
                    'message'    => $e->getMessage(),
                    'file'       => $e->getFile(),
                    'line'       => $e->getLine(),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Retour utilisateur
            |--------------------------------------------------------------------------
            */

            return back()->with(
                'error',
                'Une erreur est survenue lors de la validation du budget : '
                . $e->getMessage()
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PAIEMENT
    |--------------------------------------------------------------------------
    */

    public function payer(Request $request, $id)
    {
        $this->verifierProfil([
            'comptabilite',
            'admin',
        ]);

        try {

            $mission = Mission::findOrFail($id);

            if ($mission->statut !== 'en_attente_comptabilite') {

                return redirect()
                    ->route('missions.show', $mission->id)
                    ->with(
                        'error',
                        'Cette mission n’est pas prête pour le paiement.'
                    );
            }

            $validated = $request->validate([

                'montant_paye'       => [
                    'required',
                    'numeric',
                    'min:0',
                ],

                'reference_paiement' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'date_paiement'      => [
                    'required',
                    'date',
                ],
            ]);

            if (
                round(
                    (float) $validated['montant_paye'],
                    2
                )
                !==
                round(
                    (float) $mission->montant_total_general,
                    2
                )
            ) {

                return redirect()
                    ->route('missions.show', $mission->id)
                    ->with(
                        'error',
                        'Le montant payé doit être exactement égal au montant total de la mission.'
                    );
            }

            $mission->update([

                'montant_paye'       =>
                $validated['montant_paye'],

                'reference_paiement' =>
                $validated['reference_paiement'],

                'date_paiement'      =>
                $validated['date_paiement'],

                'statut'             =>
                'payee',
            ]);

            return redirect()
                ->route('missions.show', $mission->id)
                ->with(
                    'success',
                    'Le paiement de la mission ' .
                    $mission->numero .
                    ' a été enregistré avec succès.'
                );

        } catch (\Illuminate\Validation\ValidationException $e) {

            return back()
                ->withInput()
                ->withErrors($e->errors());

        } catch (Throwable $e) {

            return redirect()
                ->route('missions.show', $mission->id)
                ->with(
                    'error',
                    'Erreur lors du paiement : ' .
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TERMINER
    |--------------------------------------------------------------------------
    */

    public function terminer($id)
    {
        $this->verifierProfil([
            'direction',
            'admin',
        ]);

        $mission = Mission::findOrFail($id);

        if ($mission->statut !== 'payee') {

            return redirect()
                ->route('missions.index')
                ->with(
                    'error',
                    'Seule une mission payée peut être terminée.'
                );
        }

        $mission->update([
            'statut' => 'terminee',
        ]);

        return redirect()
            ->route('missions.index')
            ->with(
                'success',
                'La mission ' .
                $mission->numero .
                ' a été terminée avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | ANNULER
    |--------------------------------------------------------------------------
    */

    public function annuler($id)
    {
        $this->verifierProfil([
            'direction',
            'rh',
            'budget',
            'moyens_generaux',
            'comptabilite',
            'admin',
        ]);

        $mission = Mission::findOrFail($id);

        if (
            in_array(
                $mission->statut,
                [
                    'payee',
                    'terminee',
                    'annulee',
                ]
            )
        ) {

            return redirect()
                ->route('missions.index')
                ->with(
                    'error',
                    'Cette mission ne peut plus être annulée.'
                );
        }

        $mission->update([
            'statut' => 'annulee',
        ]);

        return redirect()
            ->route('missions.index')
            ->with(
                'success',
                'La mission ' .
                $mission->numero .
                ' a été annulée.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | RECALCULER
    |--------------------------------------------------------------------------
    */

    public function recalculer($id)
    {
        $this->verifierProfil([
            'direction',
            'rh',
            'budget',
            'admin',
        ]);

        $mission =
        Mission::with([
            'missionAgents.agent',
            'pays.zoneMajoration',
        ])->findOrFail($id);

        try {

            $this->recalculerMission(
                $mission
            );

        } catch (Throwable $e) {

            return $this->erreur(
                $e->getMessage()
            );
        }

        return $this->succes(
            'Mission recalculée.',
            [
                'montant_total' =>
                $mission->fresh()->montant_total,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RECALCUL GLOBAL
    |--------------------------------------------------------------------------
    */

    protected function recalculerMission(
        Mission $mission
    ): void {

        $mission->load([
            'missionAgents.agent',
            'pays.zoneMajoration',
        ]);

        $jours =
        $this->calculerNombreJours(
            $mission
        );

        DB::transaction(function () use ($mission, $jours) {

            $total = 0;

            foreach (
                $mission->missionAgents as $missionAgent
            ) {

                $calcul =
                $this->calculator->calculer(
                    $missionAgent->agent,
                    $mission,
                    $jours
                );

                $missionAgent->update([

                    'nombre_jours'          =>
                    $calcul['nombre_jours'],

                    'frais_journalier'      =>
                    $calcul['frais_journalier'],

                    'indemnite_journaliere' =>
                    $calcul['indemnite_journaliere'],

                    'taux_majoration'       =>
                    $calcul['taux_majoration'],

                    'montant_majoration'    =>
                    $calcul['montant_majoration'],

                    'montant_total'         =>
                    $calcul['montant_total'],
                ]);

                $total +=
                    $calcul['montant_total'];
            }

            $mission->update([

                'duree'         =>
                $jours,

                'montant_total' =>
                $total,
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CONFLITS
    |--------------------------------------------------------------------------
    */

    protected function verifierConflitsAgents(
        array $agentIds,
        Carbon $dateDepart,
        Carbon $dateRetour,
        ?int $missionId = null
    ): void {

        foreach (
            array_unique($agentIds) as $agentId
        ) {

            $query =
            MissionAgent::where(
                'agent_id',
                $agentId
            )
                ->whereHas(
                    'mission',
                    function ($q) use ($dateDepart, $dateRetour, $missionId) {

                        if ($missionId) {
                            $q->where(
                                'id',
                                '!=',
                                $missionId
                            );
                        }

                        $q->whereNotIn(
                            'statut',
                            [
                                'brouillon',
                                'annulee',
                            ]
                        )
                            ->whereDate(
                                'date_depart',
                                '<=',
                                $dateRetour
                            )
                            ->whereDate(
                                'date_retour',
                                '>=',
                                $dateDepart
                            );
                    }
                );

            if ($query->exists()) {

                $agent =
                Agent::find($agentId);

                throw new \Exception(
                    "L'agent {$agent->prenom} {$agent->nom} " .
                    "est déjà affecté à une autre mission pendant cette période."
                );
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DURÉE
    |--------------------------------------------------------------------------
    */

    protected function calculerNombreJours(
        Mission $mission
    ): int {

        $depart =
        Carbon::parse(
            $mission->date_depart
        );

        $retour =
        Carbon::parse(
            $mission->date_retour
        );

        if ($retour->lt($depart)) {
            throw new \Exception(
                'La date de retour ne peut pas être antérieure à la date de départ.'
            );
        }

        return
        $depart->diffInDays(
            $retour
        ) + 1;
    }

    /*
    |--------------------------------------------------------------------------
    | NUMÉRO MISSION
    |--------------------------------------------------------------------------
    */

    protected function genererNumeroMission(): string
    {
        do {

            $numero =
            'MISSION-' .
            now()->format('Y') .
            '-' .
            str_pad(
                random_int(1, 999999),
                6,
                '0',
                STR_PAD_LEFT
            );

        } while (
            Mission::where(
                'numero',
                $numero
            )->exists()
        );

        return $numero;
    }

    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */

    protected function verifierProfil(
        array $profils
    ): void {

        $user =
        auth()->user();

        if (! $user) {
            abort(403);
        }

        if (
            method_exists(
                $user,
                'isAdmin'
            )
            &&
            $user->isAdmin()
        ) {
            return;
        }

        if (
            isset($user->profil)
            &&
            in_array(
                $user->profil,
                $profils
            )
        ) {
            return;
        }

        abort(403);
    }

    /*
    |--------------------------------------------------------------------------
    | JSON
    |--------------------------------------------------------------------------
    */

    protected function succes(
        string $message,
        array $data = []
    ) {
        return response()->json(
            array_merge(
                [
                    'success' => true,
                    'message' => $message,
                ],
                $data
            )
        );
    }

    protected function erreur(
        string $message,
        int $code = 422
    ) {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }

    /*
|--------------------------------------------------------------------------
| RETOUR RH → DIRECTION
|--------------------------------------------------------------------------
*/

    public function retourDirection(Request $request, $id)
    {
        $this->verifierProfil([
            'rh',
            'admin',
        ]);

        $request->validate([
            'commentaire' => [
                'required',
                'string',
                'min:3',
                'max:2000',
            ],
        ], [
            'commentaire.required' =>
            'Le motif du retour est obligatoire.',

            'commentaire.min'      =>
            'Le motif du retour doit contenir au moins 3 caractères.',

            'commentaire.max'      =>
            'Le motif du retour ne peut pas dépasser 2000 caractères.',
        ]);

        $mission = Mission::findOrFail($id);

        if ($mission->statut !== 'en_attente_rh') {
            return $this->erreur(
                'La mission ne peut pas être retournée à la Direction à ce stade.'
            );
        }

        DB::transaction(function () use ($mission, $request) {

            DB::table('mission_commentaires')->insert([
                'mission_id'  => $mission->id,
                'user_id'     => auth()->id(),
                'etape'       => 'RH',
                'action'      => 'retour',
                'commentaire' => trim($request->commentaire),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $this->synchroniserStatutRetour(
        $mission,
        'brouillon'
    );

            $mission->update([
                'statut' => 'brouillon',
            ]);
        });

        return redirect()
            ->route('missions.index')
            ->with(
                'success',
                'La mission a été retournée à la Direction avec succès.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | RETOUR MOYENS GENERAUX → RH
    |--------------------------------------------------------------------------
    */

    public function retourRH(Request $request, $id)
    {
        $this->verifierProfil([
            'moyens_generaux',
            'admin',
        ]);

        $request->validate([
            'commentaire' => [
                'required',
                'string',
                'min:3',
                'max:2000',
            ],
        ], [
            'commentaire.required' =>
            'Le motif du retour est obligatoire.',

            'commentaire.min'      =>
            'Le motif du retour doit contenir au moins 3 caractères.',

            'commentaire.max'      =>
            'Le motif du retour ne peut pas dépasser 2000 caractères.',
        ]);

        $mission = Mission::findOrFail($id);

        if ($mission->statut !== 'en_attente_moyens_generaux') {
            return $this->erreur(
                'La mission ne peut pas être retournée à RH à ce stade.'
            );
        }

        DB::transaction(function () use ($mission, $request) {

            DB::table('mission_commentaires')->insert([
                'mission_id'  => $mission->id,
                'user_id'     => auth()->id(),
                'etape'       => 'MOYENS_GENERAUX',
                'action'      => 'retour',
                'commentaire' => trim($request->commentaire),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $this->synchroniserStatutRetour(
        $mission,
        'en_attente_rh'
    );

            $mission->update([
                'statut' => 'en_attente_rh',
            ]);
        });

        return redirect()
            ->route('missions.index')
            ->with(
                'success',
                'La mission a été retournée à RH avec succès.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | RETOUR RH APRÈS MG → MOYENS GENERAUX
    |--------------------------------------------------------------------------
    */

    public function retourMoyensGeneraux(Request $request, $id)
    {
        $this->verifierProfil([
            'rh',
            'admin',
        ]);

        $request->validate([
            'commentaire' => [
                'required',
                'string',
                'min:3',
                'max:2000',
            ],
        ], [
            'commentaire.required' =>
            'Le motif du retour est obligatoire.',

            'commentaire.min'      =>
            'Le motif du retour doit contenir au moins 3 caractères.',

            'commentaire.max'      =>
            'Le motif du retour ne peut pas dépasser 2000 caractères.',
        ]);

        $mission = Mission::findOrFail($id);

        if ($mission->statut !== 'en_attente_rh_apres_mg') {
            return $this->erreur(
                'La mission ne peut pas être retournée aux Moyens Généraux à ce stade.'
            );
        }

        DB::transaction(function () use ($mission, $request) {

            DB::table('mission_commentaires')->insert([
                'mission_id'  => $mission->id,
                'user_id'     => auth()->id(),
                'etape'       => 'RH_APRES_MG',
                'action'      => 'retour',
                'commentaire' => trim($request->commentaire),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $this->synchroniserStatutRetour(
        $mission,
        'en_attente_moyens_generaux'
    );

            $mission->update([
                'statut' => 'en_attente_moyens_generaux',
            ]);
        });

        return redirect()
            ->route('missions.index')
            ->with(
                'success',
                'La mission a été retournée aux Moyens Généraux avec succès.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | RETOUR BUDGET → RH
    |--------------------------------------------------------------------------
    */

    public function retourRHBudget(Request $request, $id)
    {
        $this->verifierProfil([
            'budget',
            'admin',
        ]);

        $request->validate([
            'commentaire' => [
                'required',
                'string',
                'min:3',
                'max:2000',
            ],
        ], [
            'commentaire.required' =>
            'Le motif du retour est obligatoire.',

            'commentaire.min'      =>
            'Le motif du retour doit contenir au moins 3 caractères.',

            'commentaire.max'      =>
            'Le motif du retour ne peut pas dépasser 2000 caractères.',
        ]);

        $mission = Mission::findOrFail($id);

        if ($mission->statut !== 'en_attente_budget') {
            return $this->erreur(
                'La mission ne peut pas être retournée à RH à ce stade.'
            );
        }

        DB::transaction(function () use ($mission, $request) {

            DB::table('mission_commentaires')->insert([
                'mission_id'  => $mission->id,
                'user_id'     => auth()->id(),
                'etape'       => 'BUDGET',
                'action'      => 'retour',
                'commentaire' => trim($request->commentaire),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $this->synchroniserStatutRetour(
        $mission,
        'en_attente_rh_apres_mg'
    );

            $mission->update([
                'statut' => 'en_attente_rh_apres_mg',
            ]);
        });

        return redirect()
            ->route('missions.index')
            ->with(
                'success',
                'La mission a été retournée à RH avec succès.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | RETOUR COMPTABILITÉ → BUDGET
    |--------------------------------------------------------------------------
    */

    public function retourBudget(Request $request, $id)
    {
        $this->verifierProfil([
            'comptabilite',
            'admin',
        ]);

        $request->validate([
            'commentaire' => [
                'required',
                'string',
                'min:3',
                'max:2000',
            ],
        ], [
            'commentaire.required' =>
            'Le motif du retour est obligatoire.',

            'commentaire.min'      =>
            'Le motif du retour doit contenir au moins 3 caractères.',

            'commentaire.max'      =>
            'Le motif du retour ne peut pas dépasser 2000 caractères.',
        ]);

        $mission = Mission::findOrFail($id);

        if ($mission->statut !== 'en_attente_comptabilite') {
            return $this->erreur(
                'La mission ne peut pas être retournée au Budget à ce stade.'
            );
        }

        DB::transaction(function () use ($mission, $request) {

            DB::table('mission_commentaires')->insert([
                'mission_id'  => $mission->id,
                'user_id'     => auth()->id(),
                'etape'       => 'COMPTABILITE',
                'action'      => 'retour',
                'commentaire' => trim($request->commentaire),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $this->synchroniserStatutRetour(
        $mission,
        'en_attente_budget'
    );

            $mission->update([
                'statut' => 'en_attente_budget',
            ]);
        });

        return redirect()
            ->route('missions.index')
            ->with(
                'success',
                'La mission a été retournée au Budget avec succès.'
            );

    }

    
/*
|--------------------------------------------------------------------------
| RETOUR DGA → DIRECTION — CONSULTATION
|--------------------------------------------------------------------------
*/
public function retourDirectionConsultation(Request $request, $id)
{
    $this->verifierProfil([
        'dga',
        'admin',
    ]);

    $request->validate([
        'commentaire' => ['required', 'string', 'min:3', 'max:2000'],
    ], [
        'commentaire.required' => 'Le motif du retour est obligatoire.',
        'commentaire.min' => 'Le motif doit contenir au moins 3 caractères.',
        'commentaire.max' => 'Le motif ne peut pas dépasser 2000 caractères.',
    ]);

    $mission = Mission::findOrFail($id);

    if ($mission->type !== 'nationale' ||
        $mission->statut !== 'en_attente_visa_dga') {
        return $this->erreur(
            'La mission ne peut pas être retournée à la Direction à ce stade.'
        );
    }

    DB::transaction(function () use ($mission, $request) {
        DB::table('mission_commentaires')->insert([
            'mission_id'  => $mission->id,
            'user_id'     => auth()->id(),
            'etape'       => 'DGA',
            'action'      => 'retour',
            'commentaire' => trim($request->commentaire),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $this->synchroniserStatutRetour(
        $mission,
        'en_attente_service_demandeur'
    );
        $mission->update([
            'statut' => 'en_attente_service_demandeur',
        ]);
    });

    return redirect()
        ->route('missions.index')
        ->with(
            'success',
            'La mission a été retournée à la Direction pour consultation.'
        );
}

/*
|--------------------------------------------------------------------------
| RETOUR DAGRH → DGA
|--------------------------------------------------------------------------
*/
public function retourDGA(Request $request, $id)
{
    $this->verifierProfil([
        'dagrh',
        'admin',
    ]);

    $request->validate([
        'commentaire' => ['required', 'string', 'min:3', 'max:2000'],
    ], [
        'commentaire.required' => 'Le motif du retour est obligatoire.',
        'commentaire.min' => 'Le motif doit contenir au moins 3 caractères.',
        'commentaire.max' => 'Le motif ne peut pas dépasser 2000 caractères.',
    ]);

    $mission = Mission::findOrFail($id);

    if ($mission->type !== 'nationale' ||
        $mission->statut !== 'en_attente_visa_dagrh') {
        return $this->erreur(
            'La mission ne peut pas être retournée au DGA à ce stade.'
        );
    }

    DB::transaction(function () use ($mission, $request) {
        DB::table('mission_commentaires')->insert([
            'mission_id'  => $mission->id,
            'user_id'     => auth()->id(),
            'etape'       => 'DAGRH',
            'action'      => 'retour',
            'commentaire' => trim($request->commentaire),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $this->synchroniserStatutRetour(
        $mission,
        'en_attente_visa_dga'
    );

        $mission->update([
            'statut' => 'en_attente_visa_dga',
        ]);
    });

    return redirect()
        ->route('missions.index')
        ->with(
            'success',
            'La mission a été retournée au DGA pour réexamen.'
        );
}


    private function verifierAccesMission(Mission $mission)
    {
        $user = auth()->user();

        if ($user->profil !== 'direction') {
            return;
        }

        if ((int) $mission->direction !== (int) $user->direction_id) {

            abort(
                403,
                'Vous n’êtes pas autorisé à consulter cette mission.'
            );
        }
    }

    /**
     * Enregistrer / mettre à jour les informations carburant
     * d'une mission nationale.
     */

    public function gererCarburant(Request $request, Mission $mission)
    {
        /*
        |--------------------------------------------------------------------------
        | Vérification du statut
        |--------------------------------------------------------------------------
        */

        if ($mission->statut !== 'en_attente_budget') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'La mission n’est pas actuellement au niveau du Budget.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Le carburant concerne uniquement les missions nationales
        |--------------------------------------------------------------------------
        */

        if ($mission->type !== 'nationale') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'La gestion du carburant concerne uniquement les missions nationales.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'nombre_voitures'          => [
                'required',
                'integer',
                'min:1',
            ],

            'quantite_carburant'       => [
                'required',
                'numeric',
                'min:0',
            ],

            'quantite_carburant_ville' => [
                'required',
                'numeric',
                'min:0',
            ],

            'prix_litre'               => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Conversion
        |--------------------------------------------------------------------------
        */

        $nombreVoitures = (int) $validated['nombre_voitures'];

        $quantiteCarburant =
        (float) $validated['quantite_carburant'];

        $quantiteCarburantVille =
        (float) $validated['quantite_carburant_ville'];

        $prixLitre =
        (float) $validated['prix_litre'];

        /*
        |--------------------------------------------------------------------------
        | Calculs
        |--------------------------------------------------------------------------
        */

        $montantCarburant = round(
            $quantiteCarburant * $prixLitre,
            2
        );

        $montantCarburantVille = round(
            $quantiteCarburantVille * $prixLitre,
            2
        );

        $sousTotalCarburant = round(
            $montantCarburant +
            $montantCarburantVille,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Montant actuel de la mission
        |--------------------------------------------------------------------------
        */

        $montantMission = (float) $mission->montant_total;

        /*
        |--------------------------------------------------------------------------
        | Total général
        |--------------------------------------------------------------------------
        */

        $montantTotalGeneral = round(
            $montantMission +
            $sousTotalCarburant,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. Enregistrer dans mission_carburants
            |--------------------------------------------------------------------------
            */

            $mission->carburant()->updateOrCreate(
                [
                    'mission_id' => $mission->id,
                ],
                [
                    'nombre_voitures'          =>
                    $nombreVoitures,

                    'quantite_carburant'       =>
                    $quantiteCarburant,

                    'quantite_carburant_ville' =>
                    $quantiteCarburantVille,

                    'prix_litre'               =>
                    $prixLitre,

                    'montant_carburant'        =>
                    $montantCarburant,

                    'montant_carburant_ville'  =>
                    $montantCarburantVille,

                    'sous_total'               =>
                    $sousTotalCarburant,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 2. Mise à jour DIRECTE de missions
            |--------------------------------------------------------------------------
            */

            $updated = DB::table('missions')
                ->where('id', $mission->id)
                ->update([
                    'montant_carburant'     =>
                    $sousTotalCarburant,

                    'montant_total_general' =>
                    $montantTotalGeneral,

                    'updated_at'            =>
                    now(),
                ]);

            /*
            |--------------------------------------------------------------------------
            | Vérifier que missions a bien été modifiée
            |--------------------------------------------------------------------------
            */

            if ($updated === 0) {

                throw new \Exception(
                    'La table missions n’a pas été mise à jour. '
                    . 'Vérifiez que la mission existe et que les colonnes '
                    . 'montant_carburant et montant_total_general existent.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validation transaction
            |--------------------------------------------------------------------------
            */

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Recharger la mission
            |--------------------------------------------------------------------------
            */

            $mission->refresh();

            /*
            |--------------------------------------------------------------------------
            | Vérification finale
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'missions.show',
                    $mission->id
                )
                ->with(
                    'success',
                    'Carburant enregistré avec succès. '
                    . 'Montant carburant : '
                    . number_format(
                        $mission->montant_carburant,
                        0,
                        ',',
                        ' '
                    )
                    . ' FCFA. '
                    . 'Montant total général : '
                    . number_format(
                        $mission->montant_total_general,
                        0,
                        ',',
                        ' '
                    )
                    . ' FCFA.'
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Annuler toute la transaction
            |--------------------------------------------------------------------------
            */

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Erreur lors de l’enregistrement du carburant : '
                    . $e->getMessage()
                );
        }
    }

    public function gererBudgetInternational(Request $request, $id)
    {
        $mission = Mission::with([
            'missionAgents.agent',
            'budgetsInternationaux',
        ])->findOrFail($id);

        // Vérification des droits d'accès
        $this->verifierAccesMission($mission);

        // Vérifier qu'il s'agit bien d'une mission internationale
        if ($mission->type !== 'internationale') {
            return redirect()
                ->route('missions.show', $mission->id)
                ->with(
                    'error',
                    'Les informations complémentaires concernent uniquement les missions internationales.'
                );
        }

        // Vérifier que le budget peut encore être modifié
        if ($mission->statut !== 'en_attente_budget') {
            return redirect()
                ->route('missions.show', $mission->id)
                ->with(
                    'error',
                    'Le budget de cette mission ne peut plus être modifié à cette étape.'
                );
        }

        // Récupérer les participants de la mission
        $participants = $mission->missionAgents
            ->filter(function ($missionAgent) {
                return $missionAgent->agent !== null;
            });

        if ($participants->isEmpty()) {
            return redirect()
                ->route('missions.show', $mission->id)
                ->with(
                    'error',
                    'Aucun participant n\'est enregistré sur cette mission.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | Validation du formulaire
    |--------------------------------------------------------------------------
    */

        $validated = $request->validate([
            'participants'                       => [
                'required',
                'array',
            ],

            'participants.*.frais_participation' => [
                'required',
                'numeric',
                'min:0',
            ],

            'participants.*.frais_visa'          => [
                'required',
                'numeric',
                'min:0',
            ],

            'participants.*.billet_avion'        => [
                'required',
                'numeric',
                'min:0',
            ],

            'participants.*.classe_billet'       => [
                'required',
                'in:economique,affaires',
            ],
        ]);

        DB::beginTransaction();

        try {

            /*
        |--------------------------------------------------------------------------
        | Vérification que TOUS les participants sont renseignés
        |--------------------------------------------------------------------------
        */

            foreach ($participants as $missionAgent) {

                $agent = $missionAgent->agent;

                if (! $agent) {
                    continue;
                }

                $agentId = $agent->id;

                if (! isset($validated['participants'][$agentId])) {

                    throw new \Exception(
                        "Les informations budgétaires du participant "
                        . $agent->prenom . ' '
                        . $agent->nom
                        . ' sont manquantes.'
                    );
                }
            }

            /*
        |--------------------------------------------------------------------------
        | Enregistrement des informations complémentaires
        |--------------------------------------------------------------------------
        */

            foreach ($participants as $missionAgent) {

                $agent = $missionAgent->agent;

                if (! $agent) {
                    continue;
                }

                $agentId = $agent->id;

                $data = $validated['participants'][$agentId];

                $fraisParticipation = round(
                    (float) $data['frais_participation'],
                    2
                );

                $fraisVisa = round(
                    (float) $data['frais_visa'],
                    2
                );

                $billetAvion = round(
                    (float) $data['billet_avion'],
                    2
                );

                /*
            |--------------------------------------------------------------------------
            | Total complémentaire de cet agent
            |--------------------------------------------------------------------------
            |
            | Participation + Visa + Billet d'avion
            |
            */

                $montantTotal = round(
                    $fraisParticipation
                     + $fraisVisa
                     + $billetAvion,
                    2
                );

                /*
            |--------------------------------------------------------------------------
            | Création ou modification
            |--------------------------------------------------------------------------
            |
            | Une seule ligne par :
            |
            | mission_id + agent_id
            |
            */

                MissionBudgetInternational::updateOrCreate(
                    [
                        'mission_id' => $mission->id,
                        'agent_id'   => $agentId,
                    ],
                    [
                        'frais_participation' => $fraisParticipation,
                        'frais_visa'          => $fraisVisa,
                        'billet_avion'        => $billetAvion,
                        'classe_billet'       => $data['classe_billet'],
                        'montant_total'       => $montantTotal,
                    ]
                );
            }

            /*
        |--------------------------------------------------------------------------
        | Recalcul du total des informations complémentaires
        |--------------------------------------------------------------------------
        |
        | On relit les données enregistrées en base.
        | La base devient ainsi la source officielle du calcul.
        |
        */

            $totalBudgetInternational = MissionBudgetInternational::where(
                'mission_id',
                $mission->id
            )->sum('montant_total');

            $totalBudgetInternational = round(
                (float) $totalBudgetInternational,
                2
            );

            /*
        |--------------------------------------------------------------------------
        | Total des participants
        |--------------------------------------------------------------------------
        |
        | montant_total représente déjà :
        |
        | frais + indemnités + majoration
        |
        | de tous les participants, y compris le chauffeur
        | lorsqu'il est enregistré comme participant.
        |
        */

            $totalParticipants = round(
                (float) $mission->montant_total,
                2
            );

            /*
        |--------------------------------------------------------------------------
        | Calcul du total général
        |--------------------------------------------------------------------------
        |
        | Mission internationale :
        |
        | TOTAL PARTICIPANTS
        | +
        | TOTAL INFORMATIONS COMPLÉMENTAIRES
        | =
        | TOTAL GÉNÉRAL
        |
        */

            $totalGeneral = round(
                $totalParticipants
                 + $totalBudgetInternational,
                2
            );

            /*
        |--------------------------------------------------------------------------
        | Mise à jour de la table missions
        |--------------------------------------------------------------------------
        */

            $mission->update([
                // Total frais/indemnités des participants
                'montant_total'                        => $totalParticipants,

                // Total participation + visa + billets
                'montant_complementaire_international' =>
                $totalBudgetInternational,

                // Une mission internationale n'utilise pas le carburant
                'montant_carburant'                    => 0,

                // Total définitif de la mission
                'montant_total_general'                => $totalGeneral,
            ]);

            DB::commit();

            return redirect()
                ->route('missions.show', $mission->id)
                ->with(
                    'success',
                    'Les informations complémentaires de la mission internationale ont été enregistrées avec succès.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error(
                'Erreur lors de l\'enregistrement du budget international.',
                [
                    'mission_id' => $mission->id,
                    'message'    => $e->getMessage(),
                    'file'       => $e->getFile(),
                    'line'       => $e->getLine(),
                ]
            );

            return redirect()
                ->route('missions.show', $mission->id)
                ->with(
                    'error',
                    'Une erreur est survenue lors de l\'enregistrement des informations internationales : '
                    . $e->getMessage()
                );
        }
    }

/**
 * Retourne une erreur de validation vers le formulaire.
 */
    private function reponseErreurValidation(
        Request $request,
        string $message,
        string $champ
    ) {
        // Si la requête vient d'AJAX / JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors'  => [
                    $champ => [$message],
                ],
            ], 422);
        }

        // Requête classique
        return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                $champ => $message,
            ]);
    }

    
/**
 * CG → Service demandeur / Direction pour consultation.
 * Met à jour les statuts dans les deux tables.
 */
public function transmettreDirectionConsultation($id)
{
    $user = auth()->user();

    if (!$user) {
        abort(401, 'Vous devez être connecté.');
    }

    $profil = strtolower(trim($user->profil ?? ''));

    if (!in_array($profil, ['cg', 'controle_gestion'], true)) {
        abort(403, 'Seul le Contrôle de gestion peut transmettre cette mission.');
    }

    $mission = Mission::findOrFail($id);

    if ($mission->type !== 'nationale') {
        return redirect()->route('missions.index')
            ->with('error', 'Cette transmission concerne uniquement les missions nationales.');
    }

    if ($mission->statut !== 'en_attente_cg') {
        return redirect()->route('missions.index')
            ->with('error', 'Cette mission n’est plus à l’étape du Contrôle de gestion.');
    }

    DB::transaction(function () use ($mission) {

        $nouveauStatut = 'en_attente_service_demandeur';

        // Mise à jour de la mission opérationnelle.
        $mission->statut = $nouveauStatut;
        $mission->save();

        // Mise à jour de la demande initiale associée.
        if (!empty($mission->mission_initiation_id)) {
            MissionInitiation::where(
                'id',
                $mission->mission_initiation_id
            )->update([
                'statut' => $nouveauStatut,
                'updated_at' => now(),
            ]);
        }
    });

    return redirect()->route('missions.index')
        ->with('success', 'La mission a été transmise au service demandeur pour consultation.');
}



/**
 * Direction → DGA pour visa.
 * Enregistre le commentaire et synchronise les statuts.
 */
public function transmettreDGA(Request $request, $id)
{
    $user = auth()->user();

    if (!$user) {
        abort(401, 'Vous devez être connecté.');
    }

    $profil = strtolower(trim($user->profil ?? ''));

    if ($profil !== 'direction') {
        abort(
            403,
            'Seule la Direction peut transmettre cette mission au DGA.'
        );
    }

    // Validation du commentaire facultatif.
    $validated = $request->validate([
        'commentaire' => ['nullable', 'string', 'max:2000'],
    ], [
        'commentaire.string' => 'Le commentaire doit être un texte valide.',
        'commentaire.max' => 'Le commentaire ne doit pas dépasser 2 000 caractères.',
    ]);

    $mission = Mission::findOrFail($id);

    if ($mission->type !== 'nationale') {
        return redirect()->route('missions.index')
            ->with(
                'error',
                'Cette étape concerne uniquement les missions nationales.'
            );
    }

    if ($mission->statut !== 'en_attente_service_demandeur') {
        return redirect()->route('missions.index')
            ->with(
                'error',
                'Cette mission n’est pas en attente de consultation par la Direction.'
            );
    }

    // Vérifier l'appartenance à la direction de l'utilisateur.
    $nomDirection = $user->direction->nom_direction ?? null;

    if (
        !$nomDirection ||
        trim((string) $mission->direction) !== trim((string) $nomDirection)
    ) {
        abort(
            403,
            'Vous ne pouvez pas transmettre une mission relevant d’une autre direction.'
        );
    }

    DB::transaction(function () use ($mission, $user, $validated) {

        $nouveauStatut = 'en_attente_visa_dga';

        // Enregistrer la transmission et le commentaire dans l'historique.
        DB::table('mission_commentaires')->insert([
            'mission_id' => $mission->id,
            'user_id' => $user->id,
            'etape' => 'DIRECTION',
            'action' => 'transmission',
            'commentaire' => $validated['commentaire'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mise à jour de la mission opérationnelle.
        $mission->statut = $nouveauStatut;
        $mission->save();

        // Synchronisation de la demande initiale associée.
        if (!empty($mission->mission_initiation_id)) {
            MissionInitiation::where(
                'id',
                $mission->mission_initiation_id
            )->update([
                'statut' => $nouveauStatut,
                'updated_at' => now(),
            ]);
        }
    });

    return redirect()->route('missions.index')
        ->with(
            'success',
            'La mission a été transmise au DGA pour visa.'
        );
}



/*
|--------------------------------------------------------------------------
| RETOUR DIRECTION → CONTRÔLE DE GESTION
|--------------------------------------------------------------------------
*/
public function retourCG(Request $request, $id)
{
    $this->verifierProfil([
        'direction',
        'admin',
    ]);

    $request->validate([
        'commentaire' => ['required', 'string', 'min:3', 'max:2000'],
    ], [
        'commentaire.required' => 'Le motif du retour est obligatoire.',
        'commentaire.min' => 'Le motif doit contenir au moins 3 caractères.',
        'commentaire.max' => 'Le motif ne peut pas dépasser 2000 caractères.',
    ]);

    $mission = Mission::findOrFail($id);

    if (
        $mission->type !== 'nationale' ||
        $mission->statut !== 'en_attente_service_demandeur'
    ) {
        return $this->erreur(
            'La mission ne peut pas être retournée au Contrôle de gestion à cette étape.'
        );
    }

    // Vérifier que la mission appartient à la direction connectée.
    if (strtolower(trim(auth()->user()->profil ?? '')) !== 'admin') {
        $nomDirection = auth()->user()->direction->nom_direction ?? null;

        if (
            !$nomDirection ||
            trim((string) $mission->direction) !== trim((string) $nomDirection)
        ) {
            abort(403, 'Vous ne pouvez pas retourner une mission d’une autre direction.');
        }
    }

    DB::transaction(function () use ($mission, $request) {
        DB::table('mission_commentaires')->insert([
            'mission_id'  => $mission->id,
            'user_id'     => auth()->id(),
            'etape'       => 'DIRECTION',
            'action'      => 'retour',
            'commentaire' => trim($request->commentaire),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $this->synchroniserStatutRetour(
        $mission,
        'en_attente_cg'
    );

        $mission->update([
            'statut' => 'en_attente_cg',
        ]);
    });

    return redirect()
        ->route('missions.index')
        ->with(
            'success',
            'La mission a été retournée au Contrôle de gestion avec succès.'
        );
}


/**
 * Synchronise le statut de la mission et de son initiation.
 */
private function synchroniserStatutInitiation(
    Mission $mission,
    string $statut
): void {
    // Mettre à jour le statut de la mission
    $mission->update([
        'statut' => $statut,
    ]);

    // Mettre à jour l'initiation associée
    if ($mission->mission_initiation_id) {
        DB::table('mission_initiations')
            ->where('id', $mission->mission_initiation_id)
            ->update([
                'statut' => $statut,
                'updated_at' => now(),
            ]);
    }
}


public function transmettreDAGRH(Request $request, $id)
{
    // Seuls le DGA et, si nécessaire, l'administrateur
    // peuvent transmettre la mission à la DAGRH.
    $this->verifierProfil(['dga', 'admin']);

    $request->validate([
        'commentaire' => ['nullable', 'string', 'max:2000'],
    ], [
        'commentaire.string' => 'L’observation doit être un texte valide.',
        'commentaire.max' => 'L’observation ne doit pas dépasser 2 000 caractères.',
    ]);

    DB::transaction(function () use ($request, $id) {

        // Verrouillage de la mission pendant le traitement.
        $mission = Mission::whereKey($id)
            ->lockForUpdate()
            ->firstOrFail();

        // Vérifier que la mission se trouve bien à l'étape du DGA.
        if ($mission->statut !== 'en_attente_visa_dga') {
            abort(
                422,
                'Cette mission ne peut pas être transmise à la DAGRH à cette étape.'
            );
        }

        // Enregistrer la transmission dans l'historique.
        DB::table('mission_commentaires')->insert([
            'mission_id' => $mission->id,
            'user_id' => auth()->id(),
            'etape' => 'DGA',
            'action' => 'transmission',
            'commentaire' => $request->input('commentaire'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mettre à jour le statut de la mission.
        $mission->update([
            'statut' => 'en_attente_visa_dagrh',
        ]);

        // Synchroniser le statut de l'initiation liée.
        if ($mission->mission_initiation_id) {
            DB::table('mission_initiations')
                ->where('id', $mission->mission_initiation_id)
                ->update([
                    'statut' => 'en_attente_visa_dagrh',
                    'updated_at' => now(),
                ]);
        }
    });

    return redirect()
        ->back()
        ->with(
            'success',
            'La mission a été transmise à la DAGRH avec succès.'
        );
}


public function transmettreMoyensGeneraux(Request $request, $id)
{
    // Autoriser la DAGRH et éventuellement l'administrateur.
    $this->verifierProfil(['dagrh', 'admin']);

    $request->validate([
        'commentaire' => ['nullable', 'string', 'max:2000'],
    ], [
        'commentaire.string' => 'L’observation doit être un texte valide.',
        'commentaire.max' => 'L’observation ne doit pas dépasser 2 000 caractères.',
    ]);

    DB::transaction(function () use ($request, $id) {

        // Verrouiller la mission pendant le traitement.
        $mission = Mission::whereKey($id)
            ->lockForUpdate()
            ->firstOrFail();

        // Vérifier que la mission attend bien le visa de la DAGRH.
        if ($mission->statut !== 'en_attente_visa_dagrh') {
            abort(
                422,
                'Cette mission ne peut pas être transmise aux Moyens généraux à cette étape.'
            );
        }

        // Enregistrer la transmission dans l'historique.
        DB::table('mission_commentaires')->insert([
            'mission_id' => $mission->id,
            'user_id' => auth()->id(),
            'etape' => 'DAGRH',
            'action' => 'transmission',
            'commentaire' => $request->input('commentaire'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Passer la mission aux Moyens généraux.
        $mission->update([
            'statut' => 'en_attente_mg',
        ]);

        // Synchroniser le statut de l'initiation liée.
        if ($mission->mission_initiation_id) {
            DB::table('mission_initiations')
                ->where('id', $mission->mission_initiation_id)
                ->update([
                    'statut' => 'en_attente_mg',
                    'updated_at' => now(),
                ]);
        }
    });

    return redirect()
        ->back()
        ->with(
            'success',
            'La mission a été transmise aux Moyens généraux avec succès.'
        );
}

/**
 * Retourner une mission des Moyens généraux vers la DAGRH.
 */
public function retournerDAGRH(Request $request, $id)
{
    $user = auth()->user();

    if (!$user) {
        abort(401, 'Vous devez être connecté.');
    }

    $profil = strtolower(trim($user->profil ?? ''));

    if (!in_array($profil, ['moyens_generaux', 'mg', 'admin'], true)) {
        abort(403, 'Seuls les Moyens généraux peuvent retourner cette mission à la DAGRH.');
    }

    $validated = $request->validate([
        'commentaire' => ['required', 'string', 'max:2000'],
    ], [
        'commentaire.required' => 'Veuillez préciser le motif du retour à la DAGRH.',
        'commentaire.max' => 'Le commentaire ne doit pas dépasser 2 000 caractères.',
    ]);

    DB::transaction(function () use ($id, $user, $validated) {
        $mission = Mission::where('id', $id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($mission->statut !== 'en_attente_mg') {
            abort(422, 'Cette mission n’est pas en attente de traitement par les Moyens généraux.');
        }

        $ancienStatut = $mission->statut;
        $nouveauStatut = 'en_attente_visa_dagrh';

        // Enregistrer le retour dans l'historique
        DB::table('mission_commentaires')->insert([
            'mission_id' => $mission->id,
            'user_id' => $user->id,
            'etape' => 'MOYENS_GENERAUX',
            'action' => 'retour_dagrh',
            'commentaire' => $validated['commentaire'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mettre à jour le statut de la mission
        $mission->statut = $nouveauStatut;
        $mission->save();

        // Synchroniser le statut de l'initiation associée
        if (!empty($mission->mission_initiation_id)) {
            MissionInitiation::where('id', $mission->mission_initiation_id)
                ->update([
                    'statut' => $nouveauStatut,
                    'updated_at' => now(),
                ]);
        }
    });

    return redirect()
        ->back()
        ->with('success', 'La mission a été retournée à la DAGRH avec succès.');
}

/**
 * Retourner une mission des RH vers les Moyens généraux.
 */
public function retournerMGDepuisRH(Request $request, $id)
{
    $user = auth()->user();

    if (!$user) {
        abort(401, 'Vous devez être connecté.');
    }

    $profil = strtolower(trim($user->profil ?? ''));

    if (!in_array($profil, ['rh', 'ressources_humaines', 'admin'], true)) {
        abort(403, 'Seules les Ressources humaines peuvent retourner cette mission aux Moyens généraux.');
    }

    $validated = $request->validate([
        'commentaire' => ['required', 'string', 'max:2000'],
    ], [
        'commentaire.required' => 'Veuillez préciser le motif du retour aux Moyens généraux.',
        'commentaire.max' => 'Le commentaire ne doit pas dépasser 2 000 caractères.',
    ]);

    DB::transaction(function () use ($id, $user, $validated, $profil) {
        $mission = Mission::where('id', $id)
            ->lockForUpdate()
            ->firstOrFail();

        if (!in_array($mission->statut, [
            'en_attente_rh',
            'en_attente_rh_apres_mg',
        ], true)) {
            abort(422, 'Cette mission n’est pas en attente de traitement par les RH.');
        }

        $nouveauStatut = 'en_attente_mg';

        // Enregistrer le retour dans l'historique.
        DB::table('mission_commentaires')->insert([
            'mission_id' => $mission->id,
            'user_id' => $user->id,
            'etape' => 'RH',
            'action' => 'retour_mg',
            'commentaire' => $validated['commentaire'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mettre à jour la mission.
        $mission->statut = $nouveauStatut;
        $mission->save();

        // Synchroniser l'initiation associée.
        if ($mission->mission_initiation_id) {
            MissionInitiation::where(
                'id',
                $mission->mission_initiation_id
            )->update([
                'statut' => $nouveauStatut,
                'updated_at' => now(),
            ]);
        }
    });

    return redirect()->back()->with(
        'success',
        'La mission a été retournée aux Moyens généraux avec succès.'
    );
}


/**
 * Synchronise le statut de la mission et celui de son initiation.
 */
private function synchroniserStatutRetour(
    Mission $mission,
    string $statut
): void {
    // Mettre à jour le statut de la mission.
    $mission->update([
        'statut' => $statut,
    ]);

    // Mettre à jour l'initiation liée, si elle existe.
    if ($mission->mission_initiation_id) {
        DB::table('mission_initiations')
            ->where('id', $mission->mission_initiation_id)
            ->update([
                'statut' => $statut,
                'updated_at' => now(),
            ]);
    }
}





}
