<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Fonction;
use App\Models\Categorie;
use App\Models\Service;
use App\Models\Mission;
use App\Models\MissionAgent;
use App\Models\MissionBudgetInternational;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agents';

    protected $fillable = [
        'matricule',
        'prenom',
        'nom',
        'fonction',
        'categorie',
        'service',
        'poste',
    ];


    /**
     * =========================================================
     * SERVICE
     * =========================================================
     *
     * agents.service
     *        ↓
     * services.nom_service
     */
    public function serviceRelation()
    {
        return $this->belongsTo(
            Service::class,
            'service',
            'nom_service'
        );
    }


    /**
     * =========================================================
     * FONCTION
     * =========================================================
     *
     * agents.fonction
     *        ↓
     * fonctions.nom_fonction
     */
    public function fonctionRelation()
    {
        return $this->belongsTo(
            Fonction::class,
            'fonction',
            'nom_fonction'
        );
    }


    /**
     * =========================================================
     * CATÉGORIE
     * =========================================================
     *
     * agents.categorie
     *        ↓
     * categories.nom_categorie
     */
    public function categorieRelation()
    {
        return $this->belongsTo(
            Categorie::class,
            'categorie',
            'nom_categorie'
        );
    }


    /**
     * =========================================================
     * MISSIONS
     * =========================================================
     */
    public function missions()
    {
        return $this->belongsToMany(
            Mission::class,
            'mission_agents',
            'agent_id',
            'mission_id'
        )
            ->withPivot([
                'nombre_jours',
                'frais_journalier',
                'indemnite_journaliere',
                'taux_majoration',
                'montant_majoration',
                'montant_total',
            ])
            ->withTimestamps();
    }


    /**
     * =========================================================
     * MISSION AGENTS
     * =========================================================
     */
    public function missionAgents()
    {
        return $this->hasMany(
            MissionAgent::class,
            'agent_id'
        );
    }

    public function budgetsInternationaux()
    {
        return $this->hasMany(MissionBudgetInternational::class, 'agent_id');
    }
}