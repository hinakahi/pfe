<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $table = 'reservations';
    
    protected $fillable = [
        'etudiante_id',
        'article_id',
        'resp_foyer_id',
        'quantite',
        'statut',
        'date_reservation',
    ];
    
    protected $casts = [
        'date_reservation' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // ─── RELATIONS ──────────────────────────────────────
    
    /**
     * Relation vers l'étudiante qui a fait la réservation
     */
    public function etudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'etudiante_id');
    }
    
    /**
     * Relation vers l'article réservé
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(ArticleFoyer::class);
    }
    
    /**
     * Relation vers le responsable du foyer
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resp_foyer_id');
    }
}