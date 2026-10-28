<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'nom_service',
        'description',
        'direction',
    ];

    /**
     * Valeur brute de la colonne :
     *
     * $service->direction
     *
     * retourne le nom de la direction enregistré
     * dans la table services.
     */

    /**
     * Relation vers la direction.
     *
     * On utilise "directionRelation" pour éviter
     * le conflit avec la colonne "direction".
     */
    public function directionRelation()
    {
        return $this->belongsTo(
            Direction::class,
            'direction',
            'nom_direction'
        );
    }

    public function direction()
    {
        return $this->belongsTo(
            Direction::class,
            'direction',
            'nom_direction'
        );
    }

    /**
     * Un service possède plusieurs agents.
     */
    public function agents()
    {
        return $this->hasMany(
            Agent::class,
            'service',
            'nom_service'
        );
    }
}