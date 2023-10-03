<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reparation extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'materiels_designation', 'quantite', 'commentaire'];

    public function materiel()
    {
        return $this->belongsTo(Materiel::class);
    }
}
