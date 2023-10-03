<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ap extends Model
{
    protected $fillable = ['id', 'code_ap', 'designation_ap', 'prix_rembousement_ap', 'prix_public_ap'];
    public function prestations()
    {
        return $this->hasMany(Prestation::class);
    }
}
