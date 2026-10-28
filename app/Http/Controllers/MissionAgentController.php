<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Mission;
use App\Models\MissionAgent;
use App\Services\MissionCalculatorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class MissionAgentController extends Controller
{
    protected MissionCalculatorService $calculator;

    public function __construct(
        MissionCalculatorService $calculator
    ) {
        $this->calculator = $calculator;
    }

    /**
     * Liste des agents d'une mission.
     */
    public function index($missionId)
    {
        $mission = Mission::with([
            'missionAgents.agent',
            'pays.zoneMajoration',
        ])->findOrFail($missionId);

        return response()->json([
            'success' => true,
            'mission' => $mission,
            'missionAgents' => $mission->missionAgents,
        ]);
    }

    /**
     * Ajouter un agent à une mission.
     */
    public function store(
        Request $request,
        $missionId
    ) {
        $mission = Mission::with([
            'pays.zoneMajoration'
        ])->findOrFail($missionId);

        $validated = $request->validate([
            'agent_id' => [
                'required',
                'integer',
                'exists:agents,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | MISSION MODIFIABLE
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $mission->statut,
                ['brouillon', 'en_attente_rh']
            )
        ) {
            return $this->erreur(
                'Les agents ne peuvent plus être modifiés à ce stade.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DOUBLON
        |--------------------------------------------------------------------------
        */

        $existe = MissionAgent::where(
            'mission_id',
            $mission->id
        )
        ->where(
            'agent_id',
            $validated['agent_id']
        )
        ->exists();

        if ($existe) {
            return $this->erreur(
                'Cet agent participe déjà à cette mission.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AGENT
        |--------------------------------------------------------------------------
        */

        $agent = Agent::findOrFail(
            $validated['agent_id']
        );

        /*
        |--------------------------------------------------------------------------
        | DURÉE
        |--------------------------------------------------------------------------
        */

        $jours = $this->calculerNombreJours(
            $mission
        );

        /*
        |--------------------------------------------------------------------------
        | CONFLIT DE DATES
        |--------------------------------------------------------------------------
        */

        if (
            $this->agentEnConflit(
                $agent->id,
                $mission
            )
        ) {
            return $this->erreur(
                "L'agent {$agent->prenom} {$agent->nom} " .
                "est déjà affecté à une autre mission " .
                "pendant cette période."
            );
        }

        try {

            DB::transaction(function () use (
                $mission,
                $agent,
                $jours
            ) {

                $calcul =
                    $this->calculator->calculer(
                        $agent,
                        $mission,
                        $jours
                    );

                MissionAgent::create([
                    'mission_id' =>
                        $mission->id,

                    'agent_id' =>
                        $agent->id,

                    'nombre_jours' =>
                        $calcul['nombre_jours'],

                    'frais_journalier' =>
                        $calcul['frais_journalier'],

                    'indemnite_journaliere' =>
                        $calcul['indemnite_journaliere'],

                    'taux_majoration' =>
                        $calcul['taux_majoration'],

                    'montant_majoration' =>
                        $calcul['montant_majoration'],

                    'montant_total' =>
                        $calcul['montant_total'],
                ]);

                $this->recalculerTotalMission(
                    $mission
                );
            });

            return;

        } catch (Throwable $e) {

            throw $e;
        }

        return $this->succes(
            'Agent ajouté avec succès.'
        );
    }

    /**
     * Ajouter plusieurs agents.
     */
    public function ajouterPlusieurs(
        Request $request,
        $missionId
    ) {
        $mission = Mission::with([
            'pays.zoneMajoration',
        ])->findOrFail($missionId);

        $validated = $request->validate([
            'agent_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'agent_ids.*' => [
                'integer',
                'exists:agents,id',
            ],
        ]);

        if (
            !in_array(
                $mission->statut,
                ['brouillon', 'en_attente_rh']
            )
        ) {
            return $this->erreur(
                'Les agents ne peuvent plus être modifiés à ce stade.'
            );
        }

        $jours =
            $this->calculerNombreJours(
                $mission
            );

        $ajoutes = 0;

        try {

            DB::transaction(function () use (
                $mission,
                $validated,
                $jours,
                &$ajoutes
            ) {

                foreach (
                    array_unique($validated['agent_ids'])
                    as $agentId
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | DÉJÀ PRÉSENT
                    |--------------------------------------------------------------------------
                    */

                    $existe =
                        MissionAgent::where(
                            'mission_id',
                            $mission->id
                        )
                        ->where(
                            'agent_id',
                            $agentId
                        )
                        ->exists();

                    if ($existe) {
                        continue;
                    }

                    $agent =
                        Agent::findOrFail($agentId);

                    /*
                    |--------------------------------------------------------------------------
                    | CONFLIT
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $this->agentEnConflit(
                            $agent->id,
                            $mission
                        )
                    ) {
                        throw new \Exception(
                            "L'agent {$agent->prenom} {$agent->nom} " .
                            "est déjà affecté à une autre mission " .
                            "pendant cette période."
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CALCUL
                    |--------------------------------------------------------------------------
                    */

                    $calcul =
                        $this->calculator->calculer(
                            $agent,
                            $mission,
                            $jours
                        );

                    MissionAgent::create([
                        'mission_id' =>
                            $mission->id,

                        'agent_id' =>
                            $agent->id,

                        'nombre_jours' =>
                            $calcul['nombre_jours'],

                        'frais_journalier' =>
                            $calcul['frais_journalier'],

                        'indemnite_journaliere' =>
                            $calcul['indemnite_journaliere'],

                        'taux_majoration' =>
                            $calcul['taux_majoration'],

                        'montant_majoration' =>
                            $calcul['montant_majoration'],

                        'montant_total' =>
                            $calcul['montant_total'],
                    ]);

                    $ajoutes++;
                }

                $this->recalculerTotalMission(
                    $mission
                );
            });

        } catch (Throwable $e) {

            return $this->erreur(
                $e->getMessage()
            );
        }

        return $this->succes(
            "{$ajoutes} agent(s) ajouté(s) avec succès."
        );
    }

    /**
     * Modifier la participation d'un agent.
     */
    public function update(
        Request $request,
        $id
    ) {
        $missionAgent =
            MissionAgent::with([
                'mission.pays.zoneMajoration',
                'agent',
            ])->findOrFail($id);

        $mission =
            $missionAgent->mission;

        if (
            !in_array(
                $mission->statut,
                ['brouillon', 'en_attente_rh']
            )
        ) {
            return $this->erreur(
                'Cette participation ne peut plus être modifiée.'
            );
        }

        $validated = $request->validate([
            'agent_id' => [
                'required',
                'integer',
                'exists:agents,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOUVEL AGENT
        |--------------------------------------------------------------------------
        */

        if (
            (int) $validated['agent_id']
            !==
            (int) $missionAgent->agent_id
        ) {

            $duplicate =
                MissionAgent::where(
                    'mission_id',
                    $mission->id
                )
                ->where(
                    'agent_id',
                    $validated['agent_id']
                )
                ->where(
                    'id',
                    '!=',
                    $missionAgent->id
                )
                ->exists();

            if ($duplicate) {
                return $this->erreur(
                    'Cet agent participe déjà à cette mission.'
                );
            }
        }

        $agent =
            Agent::findOrFail(
                $validated['agent_id']
            );

        if (
            $this->agentEnConflit(
                $agent->id,
                $mission,
                $missionAgent->id
            )
        ) {
            return $this->erreur(
                "L'agent {$agent->prenom} {$agent->nom} " .
                "est déjà affecté à une autre mission " .
                "pendant cette période."
            );
        }

        $jours =
            $this->calculerNombreJours(
                $mission
            );

        try {

            DB::transaction(function () use (
                $missionAgent,
                $mission,
                $agent,
                $jours
            ) {

                $calcul =
                    $this->calculator->calculer(
                        $agent,
                        $mission,
                        $jours
                    );

                $missionAgent->update([

                    'agent_id' =>
                        $agent->id,

                    'nombre_jours' =>
                        $calcul['nombre_jours'],

                    'frais_journalier' =>
                        $calcul['frais_journalier'],

                    'indemnite_journaliere' =>
                        $calcul['indemnite_journaliere'],

                    'taux_majoration' =>
                        $calcul['taux_majoration'],

                    'montant_majoration' =>
                        $calcul['montant_majoration'],

                    'montant_total' =>
                        $calcul['montant_total'],
                ]);

                $this->recalculerTotalMission(
                    $mission
                );
            });

        } catch (Throwable $e) {

            return $this->erreur(
                $e->getMessage()
            );
        }

        return $this->succes(
            'Participation modifiée avec succès.'
        );
    }

    /**
     * Supprimer un agent.
     */
    public function destroy($id)
    {
        $missionAgent =
            MissionAgent::with('mission')
                ->findOrFail($id);

        $mission =
            $missionAgent->mission;

        if (
            !in_array(
                $mission->statut,
                ['brouillon', 'en_attente_rh']
            )
        ) {
            return $this->erreur(
                'Cet agent ne peut plus être retiré de la mission.'
            );
        }

        try {

            DB::transaction(function () use (
                $missionAgent,
                $mission
            ) {

                $missionAgent->delete();

                $this->recalculerTotalMission(
                    $mission
                );
            });

        } catch (Throwable $e) {

            return $this->erreur(
                $e->getMessage()
            );
        }

        return $this->succes(
            'Agent retiré de la mission.'
        );
    }

    /**
     * Recalculer tous les participants.
     */
    public function recalculer($missionId)
    {
        $mission = Mission::with([
            'pays.zoneMajoration',
            'missionAgents.agent',
        ])->findOrFail($missionId);

        try {

            DB::transaction(function () use (
                $mission
            ) {

                $jours =
                    $this->calculerNombreJours(
                        $mission
                    );

                $total = 0;

                foreach (
                    $mission->missionAgents
                    as $missionAgent
                ) {

                    $calcul =
                        $this->calculator->calculer(
                            $missionAgent->agent,
                            $mission,
                            $jours
                        );

                    $missionAgent->update([

                        'nombre_jours' =>
                            $calcul['nombre_jours'],

                        'frais_journalier' =>
                            $calcul['frais_journalier'],

                        'indemnite_journaliere' =>
                            $calcul['indemnite_journaliere'],

                        'taux_majoration' =>
                            $calcul['taux_majoration'],

                        'montant_majoration' =>
                            $calcul['montant_majoration'],

                        'montant_total' =>
                            $calcul['montant_total'],
                    ]);

                    $total +=
                        $calcul['montant_total'];
                }

                $mission->update([
                    'duree' => $jours,
                    'montant_total' => $total,
                ]);
            });

        } catch (Throwable $e) {

            return $this->erreur(
                $e->getMessage()
            );
        }

        return $this->succes(
            'Calcul de la mission mis à jour.',
            [
                'montant_total' =>
                    $mission->fresh()->montant_total
            ]
        );
    }

    /**
     * Recalculer le total de la mission.
     */
    protected function recalculerTotalMission(
        Mission $mission
    ): void {

        $total =
            MissionAgent::where(
                'mission_id',
                $mission->id
            )->sum('montant_total');

        $mission->update([
            'montant_total' => $total,
        ]);
    }

    /**
     * Vérifier conflit agent.
     */
    protected function agentEnConflit(
        int $agentId,
        Mission $mission,
        ?int $missionAgentId = null
    ): bool {

        $query =
            MissionAgent::where(
                'agent_id',
                $agentId
            )
            ->whereHas(
                'mission',
                function ($q) use ($mission) {

                    $q->where(
                        'id',
                        '!=',
                        $mission->id
                    )
                    ->whereNotIn(
                        'statut',
                        [
                            'brouillon',
                            'annulee',
                        ]
                    )
                    ->whereDate(
                        'date_depart',
                        '<=',
                        $mission->date_retour
                    )
                    ->whereDate(
                        'date_retour',
                        '>=',
                        $mission->date_depart
                    );
                }
            );

        if ($missionAgentId) {

            $query->where(
                'id',
                '!=',
                $missionAgentId
            );
        }

        return $query->exists();
    }

    /**
     * Durée inclusive.
     */
    protected function calculerNombreJours(
        Mission $mission
    ): int {

        if (
            !$mission->date_depart ||
            !$mission->date_retour
        ) {
            throw new \InvalidArgumentException(
                'Les dates de mission sont obligatoires.'
            );
        }

        $depart =
            Carbon::parse(
                $mission->date_depart
            );

        $retour =
            Carbon::parse(
                $mission->date_retour
            );

        if ($retour->lt($depart)) {
            throw new \InvalidArgumentException(
                'La date de retour doit être supérieure ou égale à la date de départ.'
            );
        }

        return
            $depart->diffInDays($retour) + 1;
    }

    protected function succes(
        string $message,
        array $data = []
    ) {
        return response()->json(
            array_merge(
                [
                    'success' => true,
                    'message' => $message,
                ],
                $data
            )
        );
    }

    protected function erreur(
        string $message,
        int $code = 422
    ) {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}