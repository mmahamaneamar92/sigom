<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacie extends Model
{
    protected $fillable = ['id', 'code_pharmacie', 'nom_pharmacie'];

    public function prestations()
    {
        return $this->hasMany(Prestation::class);
    }
}
