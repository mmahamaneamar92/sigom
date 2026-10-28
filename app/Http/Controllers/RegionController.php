<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegionController extends Controller
{
    /**
     * Afficher la page des régions
     */
    public function index()
    {
        $pays = Pays::orderBy('nom', 'asc')->get();

        return view('regions.index', compact('pays'));
    }

    /**
     * Retourner la liste des régions en AJAX
     */
    public function fetchregion()
    {
        $regions = Region::with('pays')
            ->orderBy('nom', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'regions' => $regions
        ]);
    }

    /**
     * Formulaire de création
     *
     * Conservé pour compatibilité.
     */
    public function create()
    {
        $pays = Pays::orderBy('nom', 'asc')->get();

        return view('regions.create', compact('pays'));
    }

    /**
     * Enregistrer une région
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'pays_id' => [
                    'required',
                    'integer',
                    'exists:pays,id'
                ],

                'nom' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'code' => [
                    'nullable',
                    'string',
                    'max:255'
                ],
            ],
            [
                'pays_id.required' =>
                    'Veuillez sélectionner un pays.',

                'pays_id.exists' =>
                    'Le pays sélectionné n’existe pas.',

                'nom.required' =>
                    'Le nom de la région est obligatoire.',

                'nom.max' =>
                    'Le nom de la région ne peut pas dépasser 255 caractères.',

                'code.max' =>
                    'Le code de la région ne peut pas dépasser 255 caractères.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ]);
        }

        /**
         * Vérifier si une région du même nom
         * existe déjà dans le même pays.
         */
        $nom = trim($request->nom);

        $existe = Region::where('pays_id', $request->pays_id)
            ->whereRaw(
                'LOWER(nom) = ?',
                [strtolower($nom)]
            )
            ->exists();

        if ($existe) {
            return response()->json([
                'status' => 400,
                'errors' => [
                    'nom' => [
                        'Cette région existe déjà dans ce pays.'
                    ]
                ]
            ]);
        }

        $region = Region::create([
            'pays_id' => $request->pays_id,

            'nom' => $nom,

            'code' => $request->code
                ? strtoupper(trim($request->code))
                : null,
        ]);

        /**
         * Charger le pays pour que l'AJAX
         * puisse l'afficher directement.
         */
        $region->load('pays');

        return response()->json([
            'status' => 200,
            'message' => 'Région ajoutée avec succès.',
            'region' => $region
        ]);
    }

    /**
     * Récupérer une région pour modification
     */
    public function edit($id)
    {
        $region = Region::with('pays')->find($id);

        if (!$region) {
            return response()->json([
                'status' => 404,
                'message' => 'Région introuvable.'
            ]);
        }

        return response()->json([
            'status' => 200,
            'region' => $region
        ]);
    }

    /**
     * Modifier une région
     */
    public function update(Request $request, $id)
    {
        $region = Region::find($id);

        if (!$region) {
            return response()->json([
                'status' => 404,
                'message' => 'Région introuvable.'
            ]);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'pays_id' => [
                    'required',
                    'integer',
                    'exists:pays,id'
                ],

                'nom' => [
                    'required',
                    'string',
                    'max:255'
                ],

                'code' => [
                    'nullable',
                    'string',
                    'max:255'
                ],
            ],
            [
                'pays_id.required' =>
                    'Veuillez sélectionner un pays.',

                'pays_id.exists' =>
                    'Le pays sélectionné n’existe pas.',

                'nom.required' =>
                    'Le nom de la région est obligatoire.',

                'nom.max' =>
                    'Le nom de la région ne peut pas dépasser 255 caractères.',

                'code.max' =>
                    'Le code de la région ne peut pas dépasser 255 caractères.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ]);
        }

        /**
         * Vérifier le doublon dans le même pays.
         */
        $nom = trim($request->nom);

        $existe = Region::where('pays_id', $request->pays_id)
            ->where('id', '!=', $region->id)
            ->whereRaw(
                'LOWER(nom) = ?',
                [strtolower($nom)]
            )
            ->exists();

        if ($existe) {
            return response()->json([
                'status' => 400,
                'errors' => [
                    'nom' => [
                        'Cette région existe déjà dans ce pays.'
                    ]
                ]
            ]);
        }

        $region->update([
            'pays_id' => $request->pays_id,

            'nom' => $nom,

            'code' => $request->code
                ? strtoupper(trim($request->code))
                : null,
        ]);

        $region->load('pays');

        return response()->json([
            'status' => 200,
            'message' => 'Région modifiée avec succès.',
            'region' => $region
        ]);
    }

    /**
     * Supprimer une région
     */
    public function destroy($id)
    {
        $region = Region::find($id);

        if (!$region) {
            return response()->json([
                'status' => 404,
                'message' => 'Région introuvable.'
            ]);
        }

        /**
         * Vérifier si la région est utilisée
         * dans une ou plusieurs missions.
         */
        if ($region->missions()->exists()) {
            return response()->json([
                'status' => 409,
                'message' =>
                    'Impossible de supprimer cette région car elle est utilisée dans une ou plusieurs missions.'
            ]);
        }

        $region->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Région supprimée avec succès.'
        ]);
    }

    /**
     * Retourner les régions d'un pays en JSON.
     *
     * Utilisé notamment dans les formulaires de mission
     * pour charger les régions après sélection du pays.
     */
    public function byPays($paysId)
    {
        /**
         * Vérifier que le pays existe.
         */
        $pays = Pays::find($paysId);

        if (!$pays) {
            return response()->json([
                'status' => 404,
                'message' => 'Pays introuvable.'
            ]);
        }

        $regions = Region::where('pays_id', $paysId)
            ->orderBy('nom', 'asc')
            ->get([
                'id',
                'pays_id',
                'nom',
                'code'
            ]);

        return response()->json([
            'status' => 200,
            'regions' => $regions
        ]);
    }
}