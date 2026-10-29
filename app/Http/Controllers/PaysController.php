<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\ZoneMajoration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaysController extends Controller
{
    /**
     * Afficher la page des pays
     */
    public function index()
    {
        $zones = ZoneMajoration::orderBy('libelle', 'asc')->get();

        return view('pays.index', compact('zones'));
    }

    /**
     * Retourner la liste des pays en AJAX
     */
    public function fetchpays()
    {
        $pays = Pays::with('zoneMajoration')
            ->withCount('regions')
            ->orderBy('nom', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'pays' => $pays
        ]);
    }

    /**
     * Formulaire de création
     *
     * Non utilisé avec l'interface AJAX,
     * mais conservé pour compatibilité.
     */
    public function create()
    {
        $zones = ZoneMajoration::orderBy('libelle', 'asc')->get();

        return view('pays.create', compact('zones'));
    }

    /**
     * Enregistrer un pays
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:pays,nom'
                ],

                'code_iso' => [
                    'nullable',
                    'string',
                    'max:3',
                    'unique:pays,code_iso'
                ],

                'zone_majoration_id' => [
                    'nullable',
                    'integer',
                    'exists:zones_majoration,id'
                ],
            ],
            [
                'nom.required' =>
                    'Le nom du pays est obligatoire.',

                'nom.unique' =>
                    'Ce pays existe déjà.',

                'code_iso.max' =>
                    'Le code ISO ne peut pas dépasser 3 caractères.',

                'code_iso.unique' =>
                    'Ce code ISO est déjà utilisé.',

                'zone_majoration_id.exists' =>
                    'La zone de majoration sélectionnée n’existe pas.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ]);
        }

        $pays = Pays::create([
            'nom' => trim($request->nom),

            'code_iso' => $request->code_iso
                ? strtoupper(trim($request->code_iso))
                : null,

            'zone_majoration_id' =>
                $request->zone_majoration_id ?: null,
        ]);

        $pays->load('zoneMajoration');

        return response()->json([
            'status' => 200,
            'message' => 'Pays ajouté avec succès.',
            'pays' => $pays
        ]);
    }

    /**
     * Récupérer un pays pour modification
     */
    public function edit($id)
    {
        $pays = Pays::with('zoneMajoration')
            ->withCount('regions')
            ->find($id);

        if (!$pays) {
            return response()->json([
                'status' => 404,
                'message' => 'Pays introuvable.'
            ]);
        }

        return response()->json([
            'status' => 200,
            'pays' => $pays
        ]);
    }

    /**
     * Modifier un pays
     */
    public function update(Request $request, $id)
    {
        $pays = Pays::find($id);

        if (!$pays) {
            return response()->json([
                'status' => 404,
                'message' => 'Pays introuvable.'
            ]);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'nom' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('pays', 'nom')->ignore($pays->id)
                ],

                'code_iso' => [
                    'nullable',
                    'string',
                    'max:3',
                    Rule::unique('pays', 'code_iso')->ignore($pays->id)
                ],

                'zone_majoration_id' => [
                    'nullable',
                    'integer',
                    'exists:zones_majoration,id'
                ],
            ],
            [
                'nom.required' =>
                    'Le nom du pays est obligatoire.',

                'nom.unique' =>
                    'Ce pays existe déjà.',

                'code_iso.max' =>
                    'Le code ISO ne peut pas dépasser 3 caractères.',

                'code_iso.unique' =>
                    'Ce code ISO est déjà utilisé.',

                'zone_majoration_id.exists' =>
                    'La zone de majoration sélectionnée n’existe pas.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ]);
        }

        $pays->update([
            'nom' => trim($request->nom),

            'code_iso' => $request->code_iso
                ? strtoupper(trim($request->code_iso))
                : null,

            'zone_majoration_id' =>
                $request->zone_majoration_id ?: null,
        ]);

        $pays->load('zoneMajoration');

        return response()->json([
            'status' => 200,
            'message' => 'Pays modifié avec succès.',
            'pays' => $pays
        ]);
    }

    /**
     * Supprimer un pays
     */
    public function destroy($id)
    {
        $pays = Pays::find($id);

        if (!$pays) {
            return response()->json([
                'status' => 404,
                'message' => 'Pays introuvable.'
            ]);
        }

        /**
         * Vérifier si le pays possède des régions
         */
        if ($pays->regions()->exists()) {
            return response()->json([
                'status' => 409,
                'message' =>
                    'Impossible de supprimer ce pays car il possède une ou plusieurs régions.'
            ]);
        }

        /**
         * Vérifier si le pays est utilisé dans une mission
         */
        if ($pays->missions()->exists()) {
            return response()->json([
                'status' => 409,
                'message' =>
                    'Impossible de supprimer ce pays car il est utilisé dans une ou plusieurs missions.'
            ]);
        }

        $pays->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Pays supprimé avec succès.'
        ]);
    }
}