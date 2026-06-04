<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

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
    ];

    public function etudiante()
    {
        return $this->belongsTo(User::class, 'etudiante_id');
    }

    public function article()
    {
        return $this->belongsTo(ArticleFoyer::class, 'article_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'resp_foyer_id');
    }
}