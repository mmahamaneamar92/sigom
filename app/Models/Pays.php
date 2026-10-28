<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    use HasFactory;

    protected $table = 'pays';

    protected $fillable = [
        'nom',
        'code_iso',
        'zone_majoration_id',
    ];

    /**
     * Zone de majoration du pays
     */
    public function zoneMajoration()
    {
        return $this->belongsTo(
            ZoneMajoration::class,
            'zone_majoration_id'
        );
    }

    /**
     * Régions appartenant au pays
     */
    public function regions()
    {
        return $this->hasMany(
            Region::class,
            'pays_id',
            'id'
        );
    }

    /**
     * Missions effectuées dans ce pays
     */
    public function missions()
    {
        return $this->hasMany(
            Mission::class,
            'pays_id'
        );
    }
}