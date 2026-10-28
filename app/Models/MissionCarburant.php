<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionCarburant extends Model
{
    use HasFactory;

    protected $table = 'mission_carburants';

    protected $fillable = [
        'mission_id',
        'nombre_voitures',
        'quantite_carburant',
        'quantite_carburant_ville',
        'prix_litre',
        'montant_carburant',
        'montant_carburant_ville',
        'sous_total',
    ];

    protected $casts = [
        'nombre_voitures' =>
            'integer',

        'quantite_carburant' =>
            'decimal:2',

        'quantite_carburant_ville' =>
            'decimal:2',

        'prix_litre' =>
            'decimal:2',

        'montant_carburant' =>
            'decimal:2',

        'montant_carburant_ville' =>
            'decimal:2',

        'sous_total' =>
            'decimal:2',
    ];

    public function mission()
    {
        return $this->belongsTo(
            Mission::class,
            'mission_id'
        );
    }
}