<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assure extends Model
{
    protected $fillable = ['id', 'numero_assurer', 'prenom', 'nom', 'tel', 'ogd'];

    public function prestations()
    {
        return $this->hasMany(Prestation::class);
    }
}
