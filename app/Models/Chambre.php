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

    public function scopeOccupees($query)
    {
        return $query->whereNotNull('etudiante_1');
    }

    public function scopePubliees($query)
    {
        return $query->where('publiee', true);
    }
}