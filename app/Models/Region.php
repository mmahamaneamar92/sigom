<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $table = 'regions';

    protected $fillable = [
        'pays_id',
        'nom',
        'code',
    ];

    /**
     * La région appartient à un pays
     */
    public function pays()
    {
        return $this->belongsTo(
            Pays::class,
            'pays_id',
            'id'
        );
    }

    /**
     * Missions réalisées dans cette région
     */
    public function missions()
    {
        return $this->hasMany(
            Mission::class,
            'region_id'
        );
    }
}