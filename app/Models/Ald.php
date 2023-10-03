<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ald extends Model
{
    protected $fillable = ['id', 'code_ald', 'designation_ald', 'prix_remboursement_ald', 'prix_public_ald'];
}
