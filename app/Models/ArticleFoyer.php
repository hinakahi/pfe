<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleFoyer extends Model
{
    use HasFactory;

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
    ];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'resp_foyer_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'article_id');
    }

    public function isEnStock(): bool
    {
        return $this->stock > 0 && $this->disponible;
    }
}