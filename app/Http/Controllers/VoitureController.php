<?php

namespace App\Http\Controllers;

use App\Models\Voiture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoitureController extends Controller
{
    /**
     * Liste des voitures.
     */
    public function index()
    {
        $voitures = Voiture::withCount('missions')
            ->orderBy('immatriculation', 'asc')
            ->paginate(10);

        return view(
            'voiture.index',
            compact('voitures')
        );
    }

    /**
     * Retourner toutes les voitures.
     */
    public function fetchvoiture()
    {
        $voitures = Voiture::withCount('missions')
            ->orderBy('immatriculation', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'voitures' => $voitures
        ]);
    }

    /**
     * Ajouter une voiture.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'immatriculation' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:voitures,immatriculation'
                ],

                'marque' => [
                    'required',
                    'string',
                    'max:100'
                ],

                'modele' => [
                    'required',
                    'string',
                    'max:100'
                ],

                'annee' => [
                    'nullable',
                    'integer',
                    'min:1900',
                    'max:' . date('Y')
                ],

                'couleur' => [
                    'nullable',
                    'string',
                    'max:50'
                ],

                'type_carburant' => [
                    'required',
                    'in:essence,diesel,hybride,electrique'
                ],

                'kilometrage' => [
                    'required',
                    'integer',
                    'min:0'
                ],

                'etat' => [
                    'required',
                    'in:bon,moyen,mauvais,hors_service'
                ],

                'disponible' => [
                    'required',
                    'boolean'
                ],

                'date_mise_service' => [
                    'nullable',
                    'date'
                ],

                'observation' => [
                    'nullable',
                    'string'
                ],
            ],
            [
                'immatriculation.required' =>
                    'L’immatriculation est obligatoire.',

                'immatriculation.unique' =>
                    'Cette immatriculation existe déjà.',

                'marque.required' =>
                    'La marque est obligatoire.',

                'modele.required' =>
                    'Le modèle est obligatoire.',

                'annee.integer' =>
                    'L’année doit être un nombre entier.',

                'annee.min' =>
                    'L’année n’est pas valide.',

                'annee.max' =>
                    'L’année ne peut pas être supérieure à l’année actuelle.',

                'type_carburant.required' =>
                    'Le type de carburant est obligatoire.',

                'type_carburant.in' =>
                    'Le type de carburant sélectionné est invalide.',

                'kilometrage.required' =>
                    'Le kilométrage est obligatoire.',

                'kilometrage.min' =>
                    'Le kilométrage ne peut pas être négatif.',

                'etat.required' =>
                    'L’état du véhicule est obligatoire.',

                'etat.in' =>
                    'L’état sélectionné est invalide.',

                'disponible.required' =>
                    'Veuillez indiquer si la voiture est disponible.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $voiture = Voiture::create([
            'immatriculation' =>
                strtoupper(trim($request->immatriculation)),

            'marque' =>
                trim($request->marque),

            'modele' =>
                trim($request->modele),

            'annee' =>
                $request->annee,

            'couleur' =>
                $request->couleur,

            'type_carburant' =>
                $request->type_carburant,

            'kilometrage' =>
                $request->kilometrage,

            'etat' =>
                $request->etat,

            'disponible' =>
                $request->disponible,

            'date_mise_service' =>
                $request->date_mise_service,

            'observation' =>
                $request->observation,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Voiture ajoutée avec succès.',
            'voiture' => $voiture
        ]);
    }

    /**
     * Afficher une voiture.
     */
    public function edit($id)
    {
        $voiture = Voiture::withCount('missions')
            ->find($id);

        if (!$voiture) {
            return response()->json([
                'status' => 404,
                'message' => 'Voiture introuvable.'
            ]);
        }

        return response()->json([
            'status' => 200,
            'voiture' => $voiture
        ]);
    }

    /**
     * Modifier une voiture.
     */
    public function update(Request $request, $id)
    {
        $voiture = Voiture::find($id);

        if (!$voiture) {
            return response()->json([
                'status' => 404,
                'message' => 'Voiture introuvable.'
            ]);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'immatriculation' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:voitures,immatriculation,' . $id
                ],

                'marque' => [
                    'required',
                    'string',
                    'max:100'
                ],

                'modele' => [
                    'required',
                    'string',
                    'max:100'
                ],

                'annee' => [
                    'nullable',
                    'integer',
                    'min:1900',
                    'max:' . date('Y')
                ],

                'couleur' => [
                    'nullable',
                    'string',
                    'max:50'
                ],

                'type_carburant' => [
                    'required',
                    'in:essence,diesel,hybride,electrique'
                ],

                'kilometrage' => [
                    'required',
                    'integer',
                    'min:0'
                ],

                'etat' => [
                    'required',
                    'in:bon,moyen,mauvais,hors_service'
                ],

                'disponible' => [
                    'required',
                    'boolean'
                ],

                'date_mise_service' => [
                    'nullable',
                    'date'
                ],

                'observation' => [
                    'nullable',
                    'string'
                ],
            ],
            [
                'immatriculation.required' =>
                    'L’immatriculation est obligatoire.',

                'immatriculation.unique' =>
                    'Cette immatriculation existe déjà.',

                'marque.required' =>
                    'La marque est obligatoire.',

                'modele.required' =>
                    'Le modèle est obligatoire.',

                'type_carburant.required' =>
                    'Le type de carburant est obligatoire.',

                'kilometrage.required' =>
                    'Le kilométrage est obligatoire.',

                'etat.required' =>
                    'L’état du véhicule est obligatoire.',

                'disponible.required' =>
                    'Veuillez indiquer si la voiture est disponible.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $voiture->update([
            'immatriculation' =>
                strtoupper(trim($request->immatriculation)),

            'marque' =>
                trim($request->marque),

            'modele' =>
                trim($request->modele),

            'annee' =>
                $request->annee,

            'couleur' =>
                $request->couleur,

            'type_carburant' =>
                $request->type_carburant,

            'kilometrage' =>
                $request->kilometrage,

            'etat' =>
                $request->etat,

            'disponible' =>
                $request->disponible,

            'date_mise_service' =>
                $request->date_mise_service,

            'observation' =>
                $request->observation,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Voiture modifiée avec succès.',
            'voiture' => $voiture
        ]);
    }

    /**
     * Supprimer une voiture.
     */
    public function destroy($id)
    {
        $voiture = Voiture::find($id);

        if (!$voiture) {
            return response()->json([
                'status' => 404,
                'message' => 'Voiture introuvable.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Une voiture utilisée dans une mission ne doit pas être supprimée
        |--------------------------------------------------------------------------
        */

        if ($voiture->missions()->exists()) {
            return response()->json([
                'status' => 409,
                'message' =>
                    'Impossible de supprimer cette voiture car elle est déjà utilisée dans une ou plusieurs missions.'
            ], 409);
        }

        $voiture->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Voiture supprimée avec succès.'
        ]);
    }
}