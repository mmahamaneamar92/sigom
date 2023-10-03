<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hopital extends Model
{
    protected $fillable = ['id', 'code_hopital', 'nom_hopital'];

    public function prestations()
    {
        return $this->hasMany(Prestation::class);
    }
}
