<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Service;
use App\Models\Fonction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    /**
     * Afficher la liste des agents
     */
    public function index()
    {
        $agents = Agent::with([
            'serviceRelation.directionRelation',
            'fonctionRelation',
            'categorieRelation'
        ])
        ->orderBy('nom', 'asc')
        ->orderBy('prenom', 'asc')
        ->paginate(400);

        $services = Service::with('directionRelation')
            ->orderBy('nom_service', 'asc')
            ->get();

        /*
         * Les fonctions sont chargées avec leur catégorie.
         *
         * La catégorie sera automatiquement récupérée
         * lorsque l'utilisateur sélectionne une fonction.
         */
        $fonctions = Fonction::with('categorie')
            ->orderBy('nom_fonction', 'asc')
            ->get();

        return view('agent.index', compact(
            'agents',
            'services',
            'fonctions'
        ));
    }


    /**
     * Retourner la liste des agents en JSON
     */
    public function fetchagent()
    {
        $agents = Agent::with([
            'serviceRelation.directionRelation',
            'fonctionRelation',
            'categorieRelation'
        ])
        ->orderBy('nom', 'asc')
        ->orderBy('prenom', 'asc')
        ->get();

        return response()->json([
            'status' => 200,
            'agents' => $agents
        ]);
    }


    /**
     * Enregistrer un nouvel agent
     */
    public function store(Request $request)
    {
        /*
         * IMPORTANT :
         *
         * Le formulaire envoie le NOM de la fonction.
         *
         * La catégorie n'est pas demandée à l'utilisateur.
         * Elle est récupérée automatiquement depuis la table
         * fonctions grâce à la fonction sélectionnée.
         */
        $validator = Validator::make(
            $request->all(),
            [
                'matricule' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:agents,matricule'
                ],

                'prenom' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'nom' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'fonction' => [
                    'required',
                    'string',
                    'max:255',
                    'exists:fonctions,nom_fonction'
                ],

                'service' => [
                    'required',
                    'string',
                    'max:255',
                    'exists:services,nom_service'
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
                'errors' => $validator->errors()
            ], 422);
        }


        /*
         * Rechercher la fonction sélectionnée
         * avec sa catégorie.
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
                'message' =>
                    'La fonction sélectionnée est introuvable.'
            ], 422);
        }


        /*
         * Vérifier que la fonction possède
         * une catégorie.
         */
        if (!$fonction->categorie) {

            return response()->json([
                'status' => 422,
                'message' =>
                    'La fonction sélectionnée n’est associée à aucune catégorie.'
            ], 422);
        }


        /*
         * Récupérer automatiquement le nom
         * de la catégorie.
         */
        $categorie = $fonction->categorie->nom_categorie;


        /*
         * Création de l'agent.
         *
         * La table agents contient :
         *
         * fonction  → nom de la fonction
         * categorie → nom de la catégorie
         */
        $agent = Agent::create([
            'matricule' => $request->matricule,
            'prenom' => $request->prenom,
            'nom' => $request->nom,

            'fonction' => $fonction->nom_fonction,
            'categorie' => $categorie,

            'service' => $request->service,
        ]);


        /*
         * Recharger les relations.
         */
        $agent->load([
            'serviceRelation.directionRelation',
            'fonctionRelation',
            'categorieRelation'
        ]);


        return response()->json([
            'status' => 200,
            'message' =>
                'Agent ajouté avec succès.',
            'agent' => $agent
        ]);
    }


    /**
     * Récupérer un agent pour modification
     */
    public function edit($id)
    {
        $agent = Agent::with([
            'serviceRelation.directionRelation',
            'fonctionRelation',
            'categorieRelation'
        ])->find($id);


        if (!$agent) {

            return response()->json([
                'status' => 404,
                'message' =>
                    'Agent introuvable.'
            ]);
        }


        return response()->json([
            'status' => 200,
            'agent' => $agent
        ]);
    }


    /**
     * Modifier un agent
     */
    public function update(
        Request $request,
        $id
    ) {
        /*
         * Rechercher l'agent.
         */
        $agent = Agent::find($id);


        if (!$agent) {

            return response()->json([
                'status' => 404,
                'message' =>
                    'Agent introuvable.'
            ]);
        }


        /*
         * Validation.
         */
        $validator = Validator::make(
            $request->all(),
            [
                'matricule' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:agents,matricule,' . $id
                ],

                'prenom' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'nom' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'fonction' => [
                    'required',
                    'string',
                    'max:255',
                    'exists:fonctions,nom_fonction'
                ],

                'service' => [
                    'required',
                    'string',
                    'max:255',
                    'exists:services,nom_service'
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

                'service.required' =>
                    'Veuillez sélectionner un service.',

                'service.exists' =>
                    'Le service sélectionné n’existe pas.',
            ]
        );


        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }


        /*
         * Rechercher la fonction sélectionnée
         * avec sa catégorie.
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
                'message' =>
                    'La fonction sélectionnée est introuvable.'
            ], 422);
        }


        /*
         * Vérifier la catégorie.
         */
        if (!$fonction->categorie) {

            return response()->json([
                'status' => 422,
                'message' =>
                    'La fonction sélectionnée n’est associée à aucune catégorie.'
            ], 422);
        }


        /*
         * Récupérer automatiquement la catégorie.
         */
        $categorie = $fonction->categorie->nom_categorie;


        /*
         * Mise à jour de l'agent.
         */
        $agent->update([
            'matricule' => $request->matricule,
            'prenom' => $request->prenom,
            'nom' => $request->nom,

            'fonction' => $fonction->nom_fonction,
            'categorie' => $categorie,

            'service' => $request->service,
        ]);


        /*
         * Recharger les relations.
         */
        $agent->load([
            'serviceRelation.directionRelation',
            'fonctionRelation',
            'categorieRelation'
        ]);


        return response()->json([
            'status' => 200,
            'message' =>
                'Agent modifié avec succès.',
            'agent' => $agent
        ]);
    }


    /**
     * Supprimer un agent
     */
    public function destroy($id)
    {
        $agent = Agent::find($id);


        if (!$agent) {

            return response()->json([
                'status' => 404,
                'message' =>
                    'Agent introuvable.'
            ]);
        }


        /*
         * Vérifier si l'agent participe
         * déjà à une mission.
         */
        if ($agent->missionAgents()->exists()) {

            return response()->json([
                'status' => 409,
                'message' =>
                    'Impossible de supprimer cet agent car il est affecté à une ou plusieurs missions.'
            ], 409);
        }


        $agent->delete();


        return response()->json([
            'status' => 200,
            'message' =>
                'Agent supprimé avec succès.'
        ]);
    }


    /**
     * Récupérer les agents d'un service
     */
    public function getAgentsByService($service)
    {
        $agents = Agent::with([
            'fonctionRelation',
            'categorieRelation'
        ])
        ->where(
            'service',
            $service
        )
        ->orderBy('nom', 'asc')
        ->orderBy('prenom', 'asc')
        ->get();


        return response()->json([
            'status' => 200,
            'agents' => $agents
        ]);
    }
}