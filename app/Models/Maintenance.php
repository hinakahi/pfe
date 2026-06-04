<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiante_id',
        'technicien_id',
        'chambre_id',
        'description',
        'type',
        'statut',
        'urgence',
        'date_signalement',
    ];

    protected $casts = [
        'date_signalement' => 'datetime',
    ];

    public function etudiante()
    {
        return $this->belongsTo(User::class, 'etudiante_id');
    }

    public function technicien()
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }

    public function chambre()
    {
        return $this->belongsTo(Chambre::class, 'chambre_id');
    }

    public function materiels()
    {
        return $this->hasMany(Materiel::class, 'maintenance_id');
    }
}