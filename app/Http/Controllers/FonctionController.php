<?php

namespace App\Http\Controllers;

use App\Models\Fonction;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FonctionController extends Controller
{
    /**
     * Liste des fonctions
     */
    public function index()
    {
        $fonctions = Fonction::withCount('agents')
            ->orderBy('nom_fonction', 'asc')
            ->paginate(10);

        return view('fonction.index', compact('fonctions'));
    }

    /**
     * Récupérer les fonctions en JSON
     */
    public function fetchfonction()
    {
        $fonctions = Fonction::withCount('agents')
            ->orderBy('nom_fonction', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'fonctions' => $fonctions
        ]);
    }

    /**
     * Ajouter une fonction
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_fonction' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:fonctions,nom_fonction'
                ],

                'description' => [
                    'nullable',
                    'string'
                ],
            ],
            [
                'nom_fonction.required' =>
                    'Le nom de la fonction est obligatoire.',

                'nom_fonction.unique' =>
                    'Cette fonction existe déjà.',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $fonction = Fonction::create([
            'nom_fonction' => $request->nom_fonction,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Fonction ajoutée avec succès.',
            'fonction' => $fonction
        ]);
    }

    /**
     * Modifier une fonction
     */
    public function edit($id)
    {
        $fonction = Fonction::withCount('agents')
            ->find($id);

        if (!$fonction) {

            return response()->json([
                'status' => 404,
                'message' => 'Fonction introuvable.'
            ]);
        }

        return response()->json([
            'status' => 200,
            'fonction' => $fonction
        ]);
    }

    /**
     * Mettre à jour une fonction
     */
    public function update(Request $request, $id)
    {
        $fonction = Fonction::find($id);

        if (!$fonction) {

            return response()->json([
                'status' => 404,
                'message' => 'Fonction introuvable.'
            ]);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'nom_fonction' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:fonctions,nom_fonction,' . $id
                ],

                'description' => [
                    'nullable',
                    'string'
                ],
            ],
            [
                'nom_fonction.required' =>
                    'Le nom de la fonction est obligatoire.',

                'nom_fonction.unique' =>
                    'Cette fonction est déjà utilisée.',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $fonction->update([
            'nom_fonction' => $request->nom_fonction,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Fonction modifiée avec succès.',
            'fonction' => $fonction
        ]);
    }

    /**
     * Supprimer une fonction
     */
    public function destroy($id)
    {
        $fonction = Fonction::find($id);

        if (!$fonction) {

            return response()->json([
                'status' => 404,
                'message' => 'Fonction introuvable.'
            ]);
        }

        /*
         * On empêche la suppression si des agents
         * utilisent encore cette fonction.
         */
        if ($fonction->agents()->exists()) {

            return response()->json([
                'status' => 409,
                'message' =>
                    'Impossible de supprimer cette fonction car des agents y sont encore rattachés.'
            ]);
        }

        $fonction->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Fonction supprimée avec succès.'
        ]);
    }
}
