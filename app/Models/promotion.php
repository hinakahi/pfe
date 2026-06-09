<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';
    
    protected $fillable = [
        'titre',
        'contenu',
        'image',
        'date_debut',
        'date_fin',
    ];
    
    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}