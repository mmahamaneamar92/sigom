<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Liste des services.
     */
    public function index()
    {
        $users = User::all();

        $directions = Direction::orderBy(
            'nom_direction',
            'asc'
        )->get();

        $services = Service::with(
            'directionRelation'
        )
        ->orderBy('direction', 'asc')
        ->orderBy('nom_service', 'asc')
        ->paginate(100);

        return view(
            'service.index',
            compact(
                'services',
                'directions',
                'users'
            )
        );
    }

    /**
     * Retourner les services en AJAX.
     */
    public function fetchservice()
    {
        $services = Service::with(
            'directionRelation'
        )
        ->orderBy('nom_service', 'asc')
        ->get();

        return response()->json([
            'status' => 200,
            'services' => $services
        ]);
    }

    /**
     * Ajouter un service.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_service' =>
                    'required|string|max:255',

                'description' =>
                    'required|string',

                'direction' =>
                    'required|string|max:255|exists:directions,nom_direction',
            ],
            [
                'nom_service.required' =>
                    'Le nom du service est obligatoire.',

                'description.required' =>
                    'La description est obligatoire.',

                'direction.required' =>
                    'Veuillez sélectionner une direction.',

                'direction.exists' =>
                    'La direction sélectionnée n’existe pas.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ]);
        }

        $exists = Service::where(
            'nom_service',
            $request->nom_service
        )->exists();

        if ($exists) {
            return response()->json([
                'status' => 409,
                'message' => 'Ce service existe déjà.'
            ]);
        }

        $service = Service::create([
            'nom_service' =>
                $request->nom_service,

            'description' =>
                $request->description,

            'direction' =>
                $request->direction,
        ]);

        $service->load(
            'directionRelation'
        );

        return response()->json([
            'status' => 200,
            'message' => 'Service ajouté avec succès.',
            'service' => $service
        ]);
    }

    /**
     * Modifier un service.
     */
    public function edit($id)
    {
        $service = Service::with(
            'directionRelation'
        )->find($id);

        if (!$service) {
            return response()->json([
                'status' => 404,
                'message' => 'Service introuvable.'
            ]);
        }

        return response()->json([
            'status' => 200,
            'service' => $service
        ]);
    }

    /**
     * Mettre à jour un service.
     */
    public function update(
        Request $request,
        $id
    ) {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_service' =>
                    'required|string|max:255',

                'description' =>
                    'required|string',

                'direction' =>
                    'required|string|max:255|exists:directions,nom_direction',
            ],
            [
                'nom_service.required' =>
                    'Le nom du service est obligatoire.',

                'description.required' =>
                    'La description est obligatoire.',

                'direction.required' =>
                    'Veuillez sélectionner une direction.',

                'direction.exists' =>
                    'La direction sélectionnée n’existe pas.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()
            ]);
        }

        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'status' => 404,
                'message' => 'Service introuvable.'
            ]);
        }

        $exists = Service::where(
            'nom_service',
            $request->nom_service
        )
        ->where('id', '!=', $id)
        ->exists();

        if ($exists) {
            return response()->json([
                'status' => 409,
                'message' =>
                    'Un autre service porte déjà ce nom.'
            ]);
        }

        $service->update([
            'nom_service' =>
                $request->nom_service,

            'description' =>
                $request->description,

            'direction' =>
                $request->direction,
        ]);

        $service->load(
            'directionRelation'
        );

        return response()->json([
            'status' => 200,
            'message' =>
                'Service modifié avec succès.',
            'service' => $service
        ]);
    }

    /**
     * Supprimer un service.
     */
    public function destroy($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'status' => 404,
                'message' => 'Service introuvable.'
            ]);
        }

        /*
         * Un service ne peut pas être supprimé
         * s'il possède encore des agents.
         */
        if ($service->agents()->exists()) {
            return response()->json([
                'status' => 409,
                'message' =>
                    'Impossible de supprimer ce service car des agents y sont encore affectés.'
            ]);
        }

        $service->delete();

        return response()->json([
            'status' => 200,
            'message' =>
                'Service supprimé avec succès.'
        ]);
    }

    /**
     * Récupérer la direction d'un service.
     */
    public function getdirections($serviceid = 0)
    {
        $directionData = Service::where(
            'id',
            $serviceid
        )
        ->select(
            'id',
            'direction'
        )
        ->get();

        return response()->json([
            'data' => $directionData
        ]);
    }
}