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
        'promo_active',
        'prix_promo',
        'promo_remarque',
        'promo_date_fin',
        'date_peremption',
    ];
    
    protected $casts = [
        'disponible' => 'boolean',
        'promo_active' => 'boolean',  
        'prix'   => 'decimal:2',
        'prix_promo' => 'decimal:2',
        'stock' => 'integer',
        'date_peremption'=> 'date',     
         'promo_date_fin' => 'date',
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
       return $this->hasMany(Reservation::class, 'article_id');
    }
    // ─── ACCESSEURS ─────────────────────────────────────

    /**
     * Retourne le prix actuel : prix promo si promo active, sinon prix normal.
     * Utilisation : $article->prix_actuel
     */
    public function getPrixActuelAttribute()
    {
        if ($this->promo_active && !is_null($this->prix_promo) && $this->prix_promo < $this->prix) {
            return $this->prix_promo;
        }
        return $this->prix;
    }
}