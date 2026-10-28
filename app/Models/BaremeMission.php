<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaremeMission extends Model
{
    use HasFactory;

    protected $table = 'baremes_mission';

    protected $fillable = [
        'categorie_id',
        'type_mission',
        'frais_mission',
        'indemnite_forfaitaire',
    ];

    protected $casts = [
        'categorie_id' => 'integer',
        'frais_mission' => 'decimal:2',
        'indemnite_forfaitaire' => 'decimal:2',
    ];

    /**
     * Catégorie concernée par le barème.
     */
    public function categorie()
    {
        return $this->belongsTo(
            Categorie::class,
            'categorie_id'
        );
    }
}
