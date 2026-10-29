<?php

namespace App\Http\Controllers;

use App\Models\MissionInitiation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class MissionInitiationController extends Controller
{
    /**
     * Enregistrer et transmettre une demande de mission nationale.
     * Profils autorisés : Direction et Admin.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Veuillez vous connecter.');
        }

        $profil = strtolower(trim($user->profil ?? ''));

        if (!in_array($profil, ['direction', 'admin'], true)) {
            abort(
                403,
                'Vous n’êtes pas autorisé à initier une mission.'
            );
        }

        $validated = $request->validate([
            'objet' => [
                'required',
                'string',
                'min:5',
                'max:255',
            ],
            'tdr' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:10240',
            ],
        ], [
            'objet.required' => 'Veuillez renseigner l’objet de la mission.',
            'objet.min' => 'L’objet doit contenir au moins 5 caractères.',
            'objet.max' => 'L’objet ne doit pas dépasser 255 caractères.',
            'tdr.required' => 'Veuillez joindre le TDR de la mission.',
            'tdr.file' => 'Le fichier TDR transmis est invalide.',
            'tdr.mimes' => 'Le TDR doit être au format PDF, DOC ou DOCX.',
            'tdr.max' => 'Le fichier ne doit pas dépasser 10 Mo.',
        ]);

        $chemin = null;

        try {
            // Stockage privé du TDR.
            $chemin = $request->file('tdr')->store(
                'tdr-missions',
                'local'
            );

            if (!$chemin) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'general' => 'Impossible de stocker le fichier TDR.',
                    ]);
            }

            // Enregistrer uniquement l'initiation.
            // La mission complète sera créée par le CG.
            $initiation = DB::transaction(function () use (
                $user,
                $validated,
                $chemin
            ) {
                return MissionInitiation::create([
                    'user_id' => $user->id,
                    'objet' => trim($validated['objet']),
                    'type' => 'nationale',
                    'tdr_path' => $chemin,
                    'statut' => 'brouillon',
                ]);
            });

            return redirect()
                ->back()
                ->with(
                    'success',
                    'La demande de mission nationale a été transmise au Contrôle de gestion. Référence : INIT-'
                    . $initiation->id
                );

        } catch (Throwable $e) {
            // Nettoyer le fichier si l'enregistrement a échoué.
            if ($chemin) {
                Storage::disk('local')->delete($chemin);
            }

            Log::error(
                'Erreur lors de l’initiation d’une mission.',
                [
                    'user_id' => $user->id,
                    'exception' => $e,
                ]
            );

            return back()
                ->withInput()
                ->withErrors([
                    'general' => 'Une erreur est survenue lors de l’enregistrement de la demande.',
                ]);
        }
    }

    
/**
 * Télécharger le TDR d'une initiation de mission.
 */
public function telechargerTdr(MissionInitiation $missionInitiation)
{
    $user = auth()->user();

    if (!$user) {
        abort(401, 'Veuillez vous connecter.');
    }

    $profil = strtolower(trim($user->profil ?? ''));

    // Profils autorisés à consulter le TDR.
    $profilsAutorises = [
        'admin',
        'direction',
        'cg',
        'dga',
        'dagrh',
        'moyens_generaux',
        'rh',
        'budget',
        'comptabilite',
        'controle_gestion',
    ];

    if (!in_array($profil, $profilsAutorises, true)) {
        abort(403, 'Vous n’êtes pas autorisé à télécharger ce TDR.');
    }

    $chemin = $missionInitiation->tdr_path;

    if (!$chemin || !Storage::disk('local')->exists($chemin)) {
        abort(404, 'Le fichier TDR est introuvable.');
    }

    return Storage::disk('local')->download(
        $chemin,
        'TDR_mission_' . $missionInitiation->id . '.' .
        pathinfo($chemin, PATHINFO_EXTENSION)
    );
}


/**
 * Vérifier les droits sur une initiation.
 */
private function verifierAccesInitiation(MissionInitiation $initiation): void
{
    $user = auth()->user();

    if (!$user) {
        abort(401, 'Veuillez vous connecter.');
    }

    $profil = strtolower(trim($user->profil ?? ''));

    if (!in_array($profil, ['direction', 'admin'], true)) {
        abort(403, 'Vous n’êtes pas autorisé à gérer cette initiation.');
    }

    // Une Direction ne gère que ses propres demandes.
    if (
        $profil === 'direction'
        && (int) $initiation->user_id !== (int) $user->id
    ) {
        abort(403, 'Vous ne pouvez pas modifier cette demande.');
    }
}

/**
 * Vérifier que l'initiation est encore modifiable.
 */
private function verifierInitiationModifiable(
    MissionInitiation $initiation
): void {
    if (!in_array($initiation->statut, ['brouillon', 'en_attente_cg'], true)) {
        abort(
            403,
            'Cette demande ne peut plus être modifiée à cette étape du circuit.'
        );
    }

    // Empêcher la modification si le CG a déjà créé la mission.
    if ($initiation->mission()->exists()) {
        abort(
            403,
            'Une mission est déjà associée à cette initiation.'
        );
    }
}

/**
 * Afficher le formulaire de modification.
 */
public function edit(MissionInitiation $missionInitiation)
{
    $this->verifierAccesInitiation($missionInitiation);
    $this->verifierInitiationModifiable($missionInitiation);

    return view(
        'mission_initiations.edit',
        ['initiation' => $missionInitiation]
    );
}


/**
 * Modifier l'objet et, facultativement, remplacer le TDR.
 */
public function update(
    Request $request,
    MissionInitiation $missionInitiation
) {
    $this->verifierAccesInitiation($missionInitiation);
    $this->verifierInitiationModifiable($missionInitiation);

    $validated = $request->validate([
        'objet' => ['required', 'string', 'min:5', 'max:255'],
        'tdr' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
    ], [
        'objet.required' => 'Veuillez renseigner l’objet de la mission.',
        'objet.min' => 'L’objet doit contenir au moins 5 caractères.',
        'objet.max' => 'L’objet ne doit pas dépasser 255 caractères.',
        'tdr.file' => 'Le fichier TDR est invalide.',
        'tdr.mimes' => 'Le TDR doit être au format PDF, DOC ou DOCX.',
        'tdr.max' => 'Le fichier TDR ne doit pas dépasser 10 Mo.',
    ]);

    $ancienChemin = $missionInitiation->tdr_path;
    $nouveauChemin = null;

    /*
     * 1. Stocker le nouveau TDR, s'il existe.
     */
    if ($request->hasFile('tdr')) {
        $nouveauChemin = $request->file('tdr')->store(
            'tdr-missions',
            'local'
        );

        if (!$nouveauChemin) {
            return back()
                ->withInput()
                ->withErrors([
                    'general' => 'Impossible de stocker le nouveau TDR.',
                ]);
        }
    }

    /*
     * 2. Enregistrer les modifications en base.
     */
    try {
        \Illuminate\Support\Facades\DB::transaction(
            function () use (
                $missionInitiation,
                $validated,
                $nouveauChemin
            ) {
                $initiation = MissionInitiation::whereKey(
                    $missionInitiation->id
                )->lockForUpdate()->firstOrFail();

                // Vérifier à nouveau les conditions avant l'écriture.
                $this->verifierAccesInitiation($initiation);
                $this->verifierInitiationModifiable($initiation);

                $initiation->objet = trim($validated['objet']);

                if ($nouveauChemin !== null) {
                    $initiation->tdr_path = $nouveauChemin;
                }

                $initiation->save();
            }
        );
    } catch (\Throwable $e) {
        // La transaction a échoué : supprimer uniquement le nouveau fichier.
        if ($nouveauChemin !== null) {
            try {
                \Illuminate\Support\Facades\Storage::disk('local')
                    ->delete($nouveauChemin);
            } catch (\Throwable $fileException) {
                \Illuminate\Support\Facades\Log::warning(
                    'Impossible de supprimer le nouveau TDR après échec.',
                    ['fichier' => $nouveauChemin]
                );
            }
        }

        report($e);

        return back()
            ->withInput()
            ->withErrors([
                'general' => 'La modification a échoué. Vérifiez les journaux Laravel si le problème persiste.',
            ]);
    }

    /*
     * 3. La transaction a réussi.
     * La suppression de l'ancien fichier ne doit plus annuler le succès.
     */
    if (
        $nouveauChemin !== null
        && $ancienChemin
        && $ancienChemin !== $nouveauChemin
    ) {
        try {
            \Illuminate\Support\Facades\Storage::disk('local')
                ->delete($ancienChemin);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning(
                'Modification enregistrée, mais ancien TDR non supprimé.',
                [
                    'initiation_id' => $missionInitiation->id,
                    'ancien_fichier' => $ancienChemin,
                    'erreur' => $e->getMessage(),
                ]
            );
        }
    }

    // Utiliser la page des missions existante.
    return redirect()
        ->route('missions.index')
        ->with(
            'success',
            'La demande INIT-' . $missionInitiation->id .
            ' a été modifiée avec succès.'
        );
}



/**
 * Supprimer une initiation encore en brouillon.
 */
public function destroy(MissionInitiation $missionInitiation)
{
    $this->verifierAccesInitiation($missionInitiation);
    $this->verifierInitiationModifiable($missionInitiation);

    $id = $missionInitiation->id;
    $chemin = $missionInitiation->tdr_path;

    /*
     * 1. Supprimer l'initiation en base.
     */
    try {
        \Illuminate\Support\Facades\DB::transaction(
            function () use ($id) {
                $initiation = MissionInitiation::whereKey($id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Revérifier les droits et le statut avant suppression.
                $this->verifierAccesInitiation($initiation);
                $this->verifierInitiationModifiable($initiation);

                $initiation->delete();
            }
        );
    } catch (\Throwable $e) {
        report($e);

        return redirect()
            ->route('missions.index')
            ->withErrors([
                'general' => 'La suppression en base a échoué. Vérifiez que la demande n’a pas été prise en charge.',
            ]);
    }

    /*
     * 2. La suppression en base est réussie.
     * Le fichier est supprimé séparément pour éviter un faux message d'échec.
     */
    if ($chemin) {
        try {
            \Illuminate\Support\Facades\Storage::disk('local')
                ->delete($chemin);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning(
                'Initiation supprimée, mais le TDR n’a pas pu être supprimé.',
                [
                    'initiation_id' => $id,
                    'fichier' => $chemin,
                    'erreur' => $e->getMessage(),
                ]
            );
        }
    }

    return redirect()
        ->route('missions.index')
        ->with(
            'success',
            'La demande INIT-' . $id . ' a été supprimée avec succès.'
        );
}



public function transmettreCg(MissionInitiation $missionInitiation)
{
    $user = auth()->user();

    if (!$user) {
        abort(401, 'Veuillez vous connecter.');
    }

    $profil = strtolower(trim($user->profil ?? ''));

    if (!in_array($profil, ['direction', 'admin'], true)) {
        abort(403, 'Vous n’êtes pas autorisé à transmettre cette demande.');
    }

    if (
        $profil === 'direction'
        && (int) $missionInitiation->user_id !== (int) $user->id
    ) {
        abort(403, 'Vous ne pouvez pas transmettre cette demande.');
    }

    if ($missionInitiation->statut !== 'brouillon') {
        return back()->with(
            'error',
            'Seule une initiation en brouillon peut être transmise au CG.'
        );
    }

    if ($missionInitiation->mission()->exists()) {
        return back()->with(
            'error',
            'Une mission opérationnelle est déjà associée à cette initiation.'
        );
    }

    $missionInitiation->statut = 'en_attente_cg';
    $missionInitiation->save();

    return redirect()
        ->route('missions.index')
        ->with(
            'success',
            'La demande INIT-' . $missionInitiation->id .
            ' a été transmise au Contrôle de gestion.'
        );
}



}