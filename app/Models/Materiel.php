<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    protected $fillable = ['id', 'designation'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'gestions');
    }

    public function reparations()
    {
        return $this->hasMany(Reparation::class);
    }
}
