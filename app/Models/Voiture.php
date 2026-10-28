<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
    use HasFactory;

    protected $table = 'voitures';

    protected $fillable = [
        'immatriculation',
        'marque',
        'modele',
        'annee',
        'couleur',
        'type_carburant',
        'kilometrage',
        'etat',
        'disponible',
        'date_mise_service',
        'observation',
    ];

    protected $casts = [
        'annee' => 'integer',
        'kilometrage' => 'integer',
        'disponible' => 'boolean',
        'date_mise_service' => 'date',
    ];

    public function missions()
    {
        return $this->hasMany(
            Mission::class,
            'voiture_id'
        );
    }
}