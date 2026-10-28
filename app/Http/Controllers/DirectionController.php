<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DirectionController extends Controller
{
    /**
     * Liste des directions
     */
    public function index()
    {
        $directions = Direction::withCount('services')
            ->orderBy('nom_direction', 'asc')
            ->paginate(100);

        return view('direction.index', compact('directions'));
    }

    /**
     * Récupérer toutes les directions en JSON
     */
    public function fetchdirection()
    {
        $directions = Direction::withCount('services')
            ->orderBy('nom_direction', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'directions' => $directions
        ]);
    }

    /**
     * Ajouter une direction
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_direction' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:directions,nom_direction'
                ],

                'description' => [
                    'required',
                    'string'
                ],
            ],
            [
                'nom_direction.required' =>
                    'Le nom de la direction est obligatoire.',

                'nom_direction.unique' =>
                    'Cette direction existe déjà.',

                'description.required' =>
                    'La description est obligatoire.',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $direction = Direction::create([
            'nom_direction' => $request->nom_direction,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Direction ajoutée avec succès.',
            'direction' => $direction
        ]);
    }

    /**
     * Afficher une direction
     */
    public function edit($id)
    {
        $direction = Direction::withCount('services')
            ->find($id);

        if (!$direction) {

            return response()->json([
                'status' => 404,
                'message' => 'Direction introuvable.'
            ]);
        }

        return response()->json([
            'status' => 200,
            'direction' => $direction
        ]);
    }

    /**
     * Modifier une direction
     */
    public function update(Request $request, $id)
    {
        $direction = Direction::find($id);

        if (!$direction) {

            return response()->json([
                'status' => 404,
                'message' => 'Direction introuvable.'
            ]);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'nom_direction' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:directions,nom_direction,' . $id
                ],

                'description' => [
                    'required',
                    'string'
                ],
            ],
            [
                'nom_direction.required' =>
                    'Le nom de la direction est obligatoire.',

                'nom_direction.unique' =>
                    'Cette direction est déjà utilisée.',

                'description.required' =>
                    'La description est obligatoire.',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $direction->update([
            'nom_direction' => $request->nom_direction,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Direction modifiée avec succès.',
            'direction' => $direction
        ]);
    }

    /**
     * Supprimer une direction
     */
    public function destroy($id)
    {
        $direction = Direction::find($id);

        if (!$direction) {

            return response()->json([
                'status' => 404,
                'message' => 'Direction introuvable.'
            ]);
        }

        /*
         * Une direction ne doit pas être supprimée
         * si des services y sont encore rattachés.
         */
        if ($direction->services()->exists()) {

            return response()->json([
                'status' => 409,
                'message' =>
                    'Impossible de supprimer cette direction car des services y sont encore rattachés.'
            ]);
        }

        $direction->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Direction supprimée avec succès.'
        ]);
    }
}