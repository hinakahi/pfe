<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeChangement extends Model
{
    use HasFactory;

    protected $table = 'demandes_changement';

    protected $fillable = [
        'etudiante_id',
        'chambre_actuelle_id',
        'chambre_demandee_id',
        'resp_hebergement_id',
        'motif',
        'statut',
        'motif_refus',
    ];

    public function etudiante()
    {
        return $this->belongsTo(User::class, 'etudiante_id');
    }

    public function chambreActuelle()
    {
        return $this->belongsTo(Chambre::class, 'chambre_actuelle_id');
    }

    public function chambreDemandee()
    {
        return $this->belongsTo(Chambre::class, 'chambre_demandee_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'resp_hebergement_id');
    }
}