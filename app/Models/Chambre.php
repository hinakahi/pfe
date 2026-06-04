<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'bloc',
        'etage',
        'capacite',
        'statut',
        'resp_hebergement_id',
    ];

    // Relations
    public function responsable()
    {
        return $this->belongsTo(User::class, 'resp_hebergement_id');
    }

    public function demandesRenouvellement()
    {
        return $this->hasMany(DemandeRenouvellement::class, 'chambre_id');
    }

    public function demandesChangement()
    {
        return $this->hasMany(DemandeChangement::class, 'chambre_actuelle_id');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'chambre_id');
    }

    public function isDisponible(): bool
    {
        return $this->statut === 'disponible';
    }
}