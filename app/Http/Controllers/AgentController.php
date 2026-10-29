<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Service;
use App\Models\Poste;
use App\Models\Fonction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    /**
     * ============================================================
     * LISTE DES AGENTS
     * ============================================================
     */
    public function index()
    {
        $postes = Poste::orderBy('nom_poste')->get();

        $agents = Agent::with([
            'serviceRelation.directionRelation',
            'fonctionRelation.categorie',
            'categorieRelation',
        ])
        ->orderBy('nom', 'asc')
        ->orderBy('prenom', 'asc')
        ->paginate(400);

        /*
         * Services avec leur direction
         */
        $services = Service::with('directionRelation')
            ->orderBy('nom_service', 'asc')
            ->get();

        /*
         * Fonctions avec leur catégorie.
         *
         * La catégorie est déterminée automatiquement
         * à partir de la fonction.
         */
        $fonctions = Fonction::with('categorie')
            ->orderBy('nom_fonction', 'asc')
            ->get();

        return view(
            'agent.index',
            compact(
                'agents',
                'services',
                'fonctions',
                'postes'
            )
        );
    }


    /**
     * ============================================================
     * LISTE JSON DES AGENTS
     * ============================================================
     */
    public function fetchagent()
    {
        $agents = Agent::with([
            'serviceRelation.directionRelation',
            'fonctionRelation.categorie',
            'categorieRelation',
        ])
        ->orderBy('nom', 'asc')
        ->orderBy('prenom', 'asc')
        ->get();

        return response()->json([
            'status' => 200,
            'agents' => $agents,
        ]);
    }


    /**
     * ============================================================
     * ENREGISTRER UN AGENT
     * ============================================================
     */
    public function store(Request $request)
    {
        /*
         * --------------------------------------------------------
         * VALIDATION
         * --------------------------------------------------------
         *
         * Le formulaire envoie :
         *
         * fonction = nom de la fonction
         *
         * La catégorie n'est PAS envoyée comme donnée indépendante.
         * Elle sera récupérée automatiquement depuis la fonction.
         */
        $validator = Validator::make(
            $request->all(),
            [
                
                'matricule' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:agents,matricule',
                ],

                'prenom' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'nom' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'fonction' => [
                    'required',
                    'string',
                    'max:255',
                    'exists:fonctions,nom_fonction',
                ],

                'service' => [
                    'required',
                    'string',
                    'max:255',
                    'exists:services,nom_service',
                ],
            ],
            [
                'matricule.required' =>
                    'Le matricule est obligatoire.',

                'matricule.unique' =>
                    'Ce matricule existe déjà.',

                'prenom.required' =>
                    'Le prénom est obligatoire.',

                'nom.required' =>
                    'Le nom est obligatoire.',

                'fonction.required' =>
                    'Veuillez sélectionner une fonction.',

                'fonction.exists' =>
                    'La fonction sélectionnée n’existe pas.',

                'service.required' =>
                    'Veuillez sélectionner un service.',

                'service.exists' =>
                    'Le service sélectionné n’existe pas.',
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }


        /*
         * --------------------------------------------------------
         * RÉCUPÉRER LA FONCTION
         * --------------------------------------------------------
         *
         * On recherche la fonction par son nom.
         */
        $fonction = Fonction::with('categorie')
            ->where(
                'nom_fonction',
                $request->fonction
            )
            ->first();


        if (!$fonction) {

            return response()->json([
                'status' => 422,
                'errors' => [
                    'fonction' => [
                        'La fonction sélectionnée est introuvable.'
                    ]
                ]
            ], 422);
        }


        /*
         * --------------------------------------------------------
         * VÉRIFIER LA CATÉGORIE
         * --------------------------------------------------------
         */
        if (!$fonction->categorie_id) {

            return response()->json([
                'status' => 422,
                'errors' => [
                    'fonction' => [
                        'La fonction sélectionnée n’est associée à aucune catégorie.'
                    ]
                ]
            ], 422);
        }


        if (!$fonction->categorie) {

            return response()->json([
                'status' => 422,
                'errors' => [
                    'fonction' => [
                        'La catégorie associée à cette fonction est introuvable.'
                    ]
                ]
            ], 422);
        }


        /*
         * --------------------------------------------------------
         * RÉCUPÉRER LE NOM DE LA CATÉGORIE
         * --------------------------------------------------------
         */
        $categorie = $fonction->categorie->nom_categorie;


        /*
         * --------------------------------------------------------
         * CRÉATION DE L'AGENT
         * --------------------------------------------------------
         *
         * IMPORTANT :
         *
         * agents.fonction  = nom de la fonction
         * agents.categorie = nom de la catégorie
         *
         * Aucun fonction_id ni categorie_id n'est utilisé.
         */
        $agent = Agent::create([
            'matricule' => $request->matricule,
            'prenom' => $request->prenom,
            'nom' => $request->nom,

            'fonction' => $fonction->nom_fonction,
            'categorie' => $categorie,

            'service' => $request->service,
            'poste' => $request->poste,
        ]);


        /*
         * Charger les relations pour la réponse JSON.
         */
        $agent->load([
            'serviceRelation.directionRelation',
            'fonctionRelation.categorie',
            'categorieRelation',
        ]);


        return response()->json([
            'status' => 201,
            'message' => 'Agent ajouté avec succès.',
            'agent' => $agent,
        ], 201);
    }


    /**
     * ============================================================
     * RÉCUPÉRER UN AGENT POUR MODIFICATION
     * ============================================================
     */
    public function edit($id)
    {
        $agent = Agent::with([
            'serviceRelation.directionRelation',
            'fonctionRelation.categorie',
            'categorieRelation',
        ])
        ->find($id);


        if (!$agent) {

            return response()->json([
                'status' => 404,
                'message' => 'Agent introuvable.',
            ], 404);
        }


        return response()->json([
            'status' => 200,
            'agent' => $agent,
        ]);
    }



/**
 * ============================================================
 * MODIFIER UN AGENT
 * ============================================================
 */
public function update(
    Request $request,
    $id
) {
    /*
     * --------------------------------------------------------
     * RÉCUPÉRER L'AGENT
     * --------------------------------------------------------
     */
    $agent = Agent::find($id);


    if (!$agent) {

        return response()->json([
            'status' => 404,
            'message' => 'Agent introuvable.',
        ], 404);
    }


    /*
     * --------------------------------------------------------
     * VALIDATION
     * --------------------------------------------------------
     */
    $validator = Validator::make(
        $request->all(),
        [
            'matricule' => [
                'required',
                'string',
                'max:255',
                'unique:agents,matricule,' . $id,
            ],

            'prenom' => [
                'required',
                'string',
                'max:255',
            ],

            'nom' => [
                'required',
                'string',
                'max:255',
            ],

            'fonction' => [
                'required',
                'string',
                'max:255',
                'exists:fonctions,nom_fonction',
            ],

            /*
             * POSTE
             */
            'poste' => [
                'required',
                'string',
                'max:150',
                'exists:postes,nom_poste',
            ],

            'service' => [
                'required',
                'string',
                'max:255',
                'exists:services,nom_service',
            ],
        ],
        [
            'matricule.required' =>
                'Le matricule est obligatoire.',

            'matricule.unique' =>
                'Ce matricule est déjà utilisé par un autre agent.',

            'prenom.required' =>
                'Le prénom est obligatoire.',

            'nom.required' =>
                'Le nom est obligatoire.',

            'fonction.required' =>
                'Veuillez sélectionner une fonction.',

            'fonction.exists' =>
                'La fonction sélectionnée n’existe pas.',

            'poste.required' =>
                'Veuillez sélectionner un poste.',

            'poste.exists' =>
                'Le poste sélectionné n’existe pas.',

            'service.required' =>
                'Veuillez sélectionner un service.',

            'service.exists' =>
                'Le service sélectionné n’existe pas.',
        ]
    );


    /*
     * --------------------------------------------------------
     * ERREURS DE VALIDATION
     * --------------------------------------------------------
     */
    if ($validator->fails()) {

        return response()->json([
            'status' => 422,
            'errors' => $validator->errors(),
        ], 422);
    }


    /*
     * --------------------------------------------------------
     * RÉCUPÉRER LA FONCTION ET SA CATÉGORIE
     * --------------------------------------------------------
     */
    $fonction = Fonction::with('categorie')
        ->where(
            'nom_fonction',
            $request->fonction
        )
        ->first();


    /*
     * Fonction introuvable
     */
    if (!$fonction) {

        return response()->json([
            'status' => 422,
            'errors' => [
                'fonction' => [
                    'La fonction sélectionnée est introuvable.'
                ]
            ]
        ], 422);
    }


    /*
     * Fonction sans catégorie
     */
    if (!$fonction->categorie_id) {

        return response()->json([
            'status' => 422,
            'errors' => [
                'fonction' => [
                    'La fonction sélectionnée n’est associée à aucune catégorie.'
                ]
            ]
        ], 422);
    }


    /*
     * Catégorie introuvable
     */
    if (!$fonction->categorie) {

        return response()->json([
            'status' => 422,
            'errors' => [
                'fonction' => [
                    'La catégorie associée à cette fonction est introuvable.'
                ]
            ]
        ], 422);
    }


    /*
     * --------------------------------------------------------
     * RÉCUPÉRER AUTOMATIQUEMENT LA CATÉGORIE
     * --------------------------------------------------------
     */
    $categorie = $fonction->categorie->nom_categorie;


    /*
     * --------------------------------------------------------
     * MISE À JOUR DE L'AGENT
     * --------------------------------------------------------
     *
     * IMPORTANT :
     *
     * La table agents contient :
     *
     * fonction
     * poste
     * categorie
     * service
     *
     * et non :
     *
     * fonction_id
     * categorie_id
     * poste_id
     *
     */
    $agent->update([
        'matricule' => $request->matricule,

        'prenom' => $request->prenom,

        'nom' => $request->nom,

        /*
         * Fonction sélectionnée
         */
        'fonction' => $fonction->nom_fonction,

        /*
         * Poste sélectionné
         */
        'poste' => $request->poste,

        /*
         * Catégorie déterminée automatiquement
         * à partir de la fonction
         */
        'categorie' => $categorie,

        /*
         * Service sélectionné
         */
        'service' => $request->service,
    ]);


    /*
     * --------------------------------------------------------
     * RECHARGER LES RELATIONS
     * --------------------------------------------------------
     */
    $agent->load([
        'serviceRelation.directionRelation',
        'fonctionRelation.categorie',
        'categorieRelation',
    ]);


    /*
     * --------------------------------------------------------
     * RÉPONSE JSON
     * --------------------------------------------------------
     */
    return response()->json([
        'status' => 200,
        'message' => 'Agent modifié avec succès.',
        'agent' => $agent,
    ]);
}



    /**
     * ============================================================
     * SUPPRIMER UN AGENT
     * ============================================================
     */
    public function destroy($id)
    {
        $agent = Agent::find($id);


        if (!$agent) {

            return response()->json([
                'status' => 404,
                'message' => 'Agent introuvable.',
            ], 404);
        }


        /*
         * Un agent déjà affecté à une mission
         * ne doit pas être supprimé.
         */
        if ($agent->missionAgents()->exists()) {

            return response()->json([
                'status' => 409,
                'message' =>
                    'Impossible de supprimer cet agent car il est affecté à une ou plusieurs missions.',
            ], 409);
        }


        $agent->delete();


        return response()->json([
            'status' => 200,
            'message' => 'Agent supprimé avec succès.',
        ]);
    }


    /**
     * ============================================================
     * AGENTS PAR SERVICE
     * ============================================================
     */
    public function getAgentsByService($service)
    {
        $agents = Agent::with([
            'fonctionRelation.categorie',
            'categorieRelation',
        ])
        ->where('service', $service)
        ->orderBy('nom', 'asc')
        ->orderBy('prenom', 'asc')
        ->get();


        return response()->json([
            'status' => 200,
            'agents' => $agents,
        ]);
    }
}