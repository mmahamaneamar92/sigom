<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technicien extends Model
{
    protected $fillable = ['id', 'code_technicien', 'prenom_technicien', 'nom_technicien'];

    public function prestations()
    {
        return $this->hasMany(Prestation::class);
    }
}
