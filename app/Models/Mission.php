<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pays;
use App\Models\Region;
use App\Models\MissionCommentaire;
use App\Models\MissionCarburant;
use App\Models\MissionBudgetInternational;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = [

        'numero',
        'objet',
        'direction',
        'type',

        'mission_initiation_id',

        'pays_id',
        'region_id',
        'destination',

        'date_depart',
        'date_retour',
        'duree',

        'statut',
        'montant_total',

        'chauffeur_id',

        'voiture_id',
        'date_affectation_voiture',
        'observation_moyens_generaux',

        'montant_paye',
        'date_paiement',
        'reference_paiement',

        // Carburant
        'montant_carburant',
        'montant_total_general',
        'montant_complementaire_international',
    ];

    protected $casts = [

        'date_depart' =>
            'date',

        'date_retour' =>
            'date',

        'date_affectation_voiture' =>
            'date',

        'date_paiement' =>
            'date',

        'montant_total' =>
            'decimal:2',

        'montant_paye' =>
            'decimal:2',

        'montant_carburant' => 'decimal:2',
        'montant_total_general' => 'decimal:2',
        'montant_complementaire_international' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | PAYS
    |--------------------------------------------------------------------------
    */

    public function pays()
    {
        return $this->belongsTo(
            Pays::class,
            'pays_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REGION
    |--------------------------------------------------------------------------
    */

    public function region()
    {
        return $this->belongsTo(
            Region::class,
            'region_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VOITURE
    |--------------------------------------------------------------------------
    */

    public function voiture()
    {
        return $this->belongsTo(
            Voiture::class,
            'voiture_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHAUFFEUR
    |--------------------------------------------------------------------------
    */

    public function chauffeur()
    {
        return $this->belongsTo(
            Agent::class,
            'chauffeur_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AGENTS VIA MISSION_AGENTS
    |--------------------------------------------------------------------------
    */

    public function missionAgents()
    {
        return $this->hasMany(
            MissionAgent::class,
            'mission_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AGENTS
    |--------------------------------------------------------------------------
    */

    public function agents()
    {
        return $this->belongsToMany(
            Agent::class,
            'mission_agents',
            'mission_id',
            'agent_id'
        )->withPivot([

                    'nombre_jours',
                    'frais_journalier',
                    'indemnite_journaliere',
                    'taux_majoration',
                    'montant_majoration',
                    'montant_total',

                ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DIRECTION
    |--------------------------------------------------------------------------
    */

    public function directionRelation()
    {
        return $this->belongsTo(
            Direction::class,
            'direction',
            'nom_direction'
        );
    }

    public function commentaires()
    {
        return $this->hasMany(
            MissionCommentaire::class,
            'mission_id'
        )->orderByDesc('created_at');
    }

    public function carburant()
    {
        return $this->hasOne(
            MissionCarburant::class,
            'mission_id'
        );
    }

    public function budgetsInternationaux()
    {
        return $this->hasMany(MissionBudgetInternational::class, 'mission_id');
    }

    public function regions()
{
    return $this->belongsToMany(
        Region::class,
        'mission_region',
        'mission_id',
        'region_id'
    );
}


public function initiation()
{
    return $this->belongsTo(
        MissionInitiation::class,
        'mission_initiation_id'
    );
}

}