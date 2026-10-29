<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'code',
        'nom_categorie',
        'description',
    ];

    public function agents()
    {
        return $this->hasMany(
            Agent::class,
            'categorie',
            'nom_categorie'
        );
    }

    /**
     * Une catégorie possède plusieurs fonctions.
     */
    public function fonctions()
    {
        return $this->hasMany(
            Fonction::class,
            'categorie_id'
        );
    }

    /** 
     * Barèmes de mission de cette catégorie. 
     */ 
    public function baremesMission() 
    { 
        return $this->hasMany( 
            BaremeMission::class, 
            'categorie_id' 
        ); 
    }
}