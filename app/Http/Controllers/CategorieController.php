<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategorieController extends Controller
{
    /**
     * Liste des catégories
     */
    public function index()
    {
        $categories = Categorie::withCount('agents')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('categorie.index', compact('categories'));
    }

    /**
     * Récupérer les catégories en JSON
     */
    public function fetchcategorie()
    {
        $categories = Categorie::withCount('agents')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'categories' => $categories
        ]);
    }

    /**
     * Ajouter une catégorie
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'code' => [
                    'required',
                    'string',
                    'max:10',
                    'unique:categories,code'
                ],

                'nom_categorie' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:categories,nom_categorie'
                ],

                'description' => [
                    'nullable',
                    'string'
                ],
            ],
            [
                'code.required' =>
                    'Le code de la catégorie est obligatoire.',

                'code.unique' =>
                    'Ce code existe déjà.',

                'nom_categorie.required' =>
                    'Le nom de la catégorie est obligatoire.',

                'nom_categorie.unique' =>
                    'Cette catégorie existe déjà.',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $categorie = Categorie::create([
            'code' => $request->code,
            'nom_categorie' => $request->nom_categorie,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Catégorie ajoutée avec succès.',
            'categorie' => $categorie
        ]);
    }

    /**
     * Modifier une catégorie
     */
    public function edit($id)
    {
        $categorie = Categorie::withCount('agents')
            ->find($id);

        if (!$categorie) {

            return response()->json([
                'status' => 404,
                'message' => 'Catégorie introuvable.'
            ]);
        }

        return response()->json([
            'status' => 200,
            'categorie' => $categorie
        ]);
    }

    /**
     * Mettre à jour une catégorie
     */
    public function update(Request $request, $id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {

            return response()->json([
                'status' => 404,
                'message' => 'Catégorie introuvable.'
            ]);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'code' => [
                    'required',
                    'string',
                    'max:10',
                    'unique:categories,code,' . $id
                ],

                'nom_categorie' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:categories,nom_categorie,' . $id
                ],

                'description' => [
                    'nullable',
                    'string'
                ],
            ],
            [
                'code.required' =>
                    'Le code de la catégorie est obligatoire.',

                'code.unique' =>
                    'Ce code est déjà utilisé.',

                'nom_categorie.required' =>
                    'Le nom de la catégorie est obligatoire.',

                'nom_categorie.unique' =>
                    'Cette catégorie est déjà utilisée.',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $categorie->update([
            'code' => $request->code,
            'nom_categorie' => $request->nom_categorie,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Catégorie modifiée avec succès.',
            'categorie' => $categorie
        ]);
    }

    /**
     * Supprimer une catégorie
     */
    public function destroy($id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {

            return response()->json([
                'status' => 404,
                'message' => 'Catégorie introuvable.'
            ]);
        }

        /*
         * On empêche la suppression si des agents
         * utilisent encore cette catégorie.
         */
        if ($categorie->agents()->exists()) {

            return response()->json([
                'status' => 409,
                'message' =>
                    'Impossible de supprimer cette catégorie car des agents y sont encore rattachés.'
            ]);
        }

        $categorie->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Catégorie supprimée avec succès.'
        ]);
    }
}
