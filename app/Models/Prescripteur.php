<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescripteur extends Model
{
    protected $fillable = ['id', 'code_prescripteur', 'prenom_prescripteur', 'nom_prescripteur'];

    public function prestations()
    {
        return $this->hasMany(Prestation::class);
    }
}
