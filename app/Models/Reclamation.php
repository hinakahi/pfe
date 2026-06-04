<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiante_id',
        'sujet',
        'message',
        'statut',
        'reponse',
        'date_reclamation',
    ];

    protected $casts = [
        'date_reclamation' => 'datetime',
    ];

    public function etudiante()
    {
        return $this->belongsTo(User::class, 'etudiante_id');
    }
}