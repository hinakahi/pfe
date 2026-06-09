<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleFoyer extends Model
{
    protected $table = 'articles_foyer';
    
    protected $fillable = [
        'resp_foyer_id',
        'nom_article',
        'categorie',
        'description',
        'prix',
        'stock',
        'photo',
        'disponible',
    ];
    
    protected $casts = [
        'disponible' => 'boolean',
        'prix' => 'decimal:2',
        'stock' => 'integer',
    ];
    
    // ─── RELATIONS ──────────────────────────────────────
    
    /**
     * Relation vers le responsable du foyer
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resp_foyer_id');
    }
    
    /**
     * Relation vers les réservations
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}