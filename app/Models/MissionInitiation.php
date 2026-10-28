<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionInitiation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'objet',
        'type',
        'tdr_path',
        'statut',
    ];

    public function demandeur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mission()
    {
        return $this->hasOne(Mission::class, 'mission_initiation_id');
    }
}