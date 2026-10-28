<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MissionBudgetInternational extends Model
{
    use HasFactory;
    protected $table = 'mission_budgets_internationaux';
    protected $fillable = ['mission_id', 'agent_id', 'frais_participation', 'frais_visa', 'billet_avion', 'classe_billet', 'montant_total',];
    protected $casts = ['mission_id' => 'integer', 'agent_id' => 'integer', 'frais_participation' => 'decimal:2', 'frais_visa' => 'decimal:2', 'billet_avion' => 'decimal:2', 'montant_total' => 'decimal:2',];
    public function mission()
    {
        return $this->belongsTo(Mission::class, 'mission_id');
    }
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}