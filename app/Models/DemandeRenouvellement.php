<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeRenouvellement extends Model
{
    protected $table = 'demandes_renouvellement';

    protected $fillable = [
        'etudiante_id',
        'chambre_id',
        'resp_hebergement_id',
        'statut',
        'justificatif_scolarite',
        'justificatif_paiement',
        'motif_refus',
    ];

    public function etudiante()
    {
        return $this->belongsTo(User::class, 'etudiante_id');
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'chambre_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'resp_hebergement_id');
    }
}