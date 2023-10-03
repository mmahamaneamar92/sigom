<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    protected $fillable = ['id', 'date', 'assure', 'nature_examen', 'renseignement', 'prescripteur', 'technicien', 'provenance', 'pharmacie', 'feuille', 'controleur', 'ap1', 'ap2', 'ap3', 'ap4', 'ap5', 'ap6', 'ap7', 'ap8', 'ap9', 'ap10', 'type1', 'type2', 'type3', 'type4', 'type5', 'type6', 'type7', 'type8', 'type9', 'type10', 'quantite1', 'quantite2', 'quantite3', 'quantite4', 'quantite5', 'quantite6', 'quantite7', 'quantite8', 'quantite9', 'quantite10'];

    public function ap()
    {
        return $this->belongsTo(Ap::class);
    }

    public function assure()
    {
        return $this->belongsTo(Assure::class);
    }

    public function hopital()
    {
        return $this->belongsTo(Hopital::class);
    }

    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }

    public function prescripteur()
    {
        return $this->belongsTo(Prescripteur::class);
    }

    public function reparation()
    {
        return $this->belongsTo(Reparation::class);
    }

    public function technicien()
    {
        return $this->belongsTo(Technicien::class);
    }
}
