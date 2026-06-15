<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation',
        'photo',
        'quantite',
        'unite',
        'seuil_minimum',
        'categorie',
        'description',
    ];

    // Stock épuisé
    public function getEstEpuiseAttribute(): bool
    {
        return $this->quantite === 0;
    }

    // Stock faible (sous le seuil)
    public function getEstFaibleAttribute(): bool
    {
        return $this->quantite <= $this->seuil_minimum && $this->quantite > 0;
    }
}