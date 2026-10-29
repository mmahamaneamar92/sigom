<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissionCommentaire extends Model
{
    use HasFactory;

    /**
     * Nom de la table
     */
    protected $table = 'mission_commentaires';

    /**
     * Champs pouvant être remplis
     */
    protected $fillable = [
        'mission_id',
        'user_id',
        'etape',
        'action',
        'commentaire',
    ];

    /**
     * Relation avec la mission
     */
    public function mission()
    {
        return $this->belongsTo(
            Mission::class,
            'mission_id'
        );
    }

    /**
     * Relation avec l'utilisateur
     * qui a effectué l'action
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}