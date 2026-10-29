<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Direction extends Model
{
    use HasFactory;

    protected $table = 'directions';

    protected $fillable = [
        'nom_direction',
        'description',
    ];

    /**
     * Une direction possède plusieurs services.
     */
    public function services()
    {
        return $this->hasMany(
            Service::class,
            'direction',
            'nom_direction'
        );
    }

    public function users()
{
    return $this->hasMany(
        User::class,
        'direction_id'
    );
}
}