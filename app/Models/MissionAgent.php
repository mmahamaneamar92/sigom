<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionAgent extends Model
{
    use HasFactory;

    protected $table = 'mission_agents';

    protected $fillable = [

        'mission_id',
        'agent_id',

        'nombre_jours',

        'frais_journalier',
        'indemnite_journaliere',

        'taux_majoration',
        'montant_majoration',

        'montant_total',
    ];

    protected $casts = [

        'nombre_jours' =>
            'integer',

        'frais_journalier' =>
            'decimal:2',

        'indemnite_journaliere' =>
            'decimal:2',

        'taux_majoration' =>
            'decimal:2',

        'montant_majoration' =>
            'decimal:2',

        'montant_total' =>
            'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | MISSION
    |--------------------------------------------------------------------------
    */

    public function mission()
    {
        return $this->belongsTo(
            Mission::class,
            'mission_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AGENT
    |--------------------------------------------------------------------------
    */

    public function agent()
    {
        return $this->belongsTo(
            Agent::class,
            'agent_id'
        );
    }
}