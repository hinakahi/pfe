<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'type',
        'bloc',
        'etage',
        'capacite',
        'etudiante_1',
        'etudiante_2',
        'publiee',
        'statut',
    ];

    protected $casts = [
        'publiee' => 'boolean',
    ];

    // Statut calculé automatiquement 
    public function getStatutAttribute(): string
    {
        if ($this->type === 'individuelle') {
            return $this->etudiante_1 ? 'occupee' : 'libre';
        }
        // Double
        if ($this->etudiante_1 && $this->etudiante_2) return 'occupee';
        if ($this->etudiante_1 || $this->etudiante_2) return 'partielle';
        return 'libre';
    }

    // Vide = aucune étudiante
    public function isVide(): bool
    {
        return !$this->etudiante_1 && !$this->etudiante_2;
    }

     // Scopes
public function scopeLibres($query)
 {
    return $query->whereNull('etudiante_1')->whereNull('etudiante_2');
 }

 public function scopePartielles($query)
 {
    return $query->where('type', '!=', 'individuelle')
        ->where(function ($q) {
            $q->where(function ($q2) {
                $q2->whereNotNull('etudiante_1')->whereNull('etudiante_2');
            })->orWhere(function ($q2) {
                $q2->whereNull('etudiante_1')->whereNotNull('etudiante_2');
            });
        });
 }

   public function scopeOccupees($query)
  {
    return $query->where(function ($q) {
        $q->where('type', 'individuelle')
          ->whereNotNull('etudiante_1');
    })->orWhere(function ($q) {
        $q->where('type', '!=', 'individuelle')
          ->whereNotNull('etudiante_1')
          ->whereNotNull('etudiante_2');
    });
 }

  public function scopePubliees($query)
  {
    return $query->where('publiee', true);
  }
}