<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneMajoration extends Model
{
    use HasFactory;

    protected $table = 'zones_majoration';

    protected $fillable = [
        'code',
        'libelle',
        'taux',
        'description',
    ];

    protected $casts = [
        'taux' => 'decimal:2',
    ];

    /**
     * Pays appartenant à cette zone de majoration
     */
    public function pays()
    {
        return $this->hasMany(Pays::class, 'zone_majoration_id');
    }
}