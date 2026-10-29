<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonction extends Model
{
    use HasFactory;

    protected $table = 'fonctions';

    protected $fillable = [
        'nom_fonction',
        'description',
        'categorie_id',
    ];

    public function agents()
    {
        return $this->hasMany(
            Agent::class,
            'fonction',
            'nom_fonction'
        );
    }

     /**
     * Une fonction appartient à une catégorie.
     */
    public function categorie()
    {
        return $this->belongsTo(
            Categorie::class,
            'categorie_id'
        );
    }
}