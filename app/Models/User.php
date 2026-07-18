<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'matricule',
        'email',
        'photo',
        'role',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Vérifications de rôle
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isEtudiante(): bool { return $this->role === 'etudiante'; }
    public function isTechnicien(): bool { return $this->role === 'technicien'; }
    public function isRespHebergement(): bool { return $this->role === 'resp_hebergement'; }
    public function isRespFoyer(): bool { return $this->role === 'resp_foyer'; }

    // Relations
    public function chambre()
    {
        return $this->hasOne(Chambre::class, 'resp_hebergement_id');
    }

    public function demandesRenouvellement()
    {
        return $this->hasMany(DemandeRenouvellement::class, 'etudiante_id');
    }

    public function demandesChangement()
    {
        return $this->hasMany(DemandeChangement::class, 'etudiante_id');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'etudiante_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'etudiante_id');
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'etudiante_id');
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'user_id');
    }

    public function periodes()
    {
        return $this->hasMany(Periode::class, 'admin_id');
    }
}