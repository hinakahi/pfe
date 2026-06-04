<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        
        'type',
         'libelle',
          'date_debut',
           'date_fin',
            'active',
            ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'active' => 'boolean',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

   public function isActive(): bool
{
    $today = now()->toDateString();
    return $this->active &&
           $this->date_debut <= $today &&
           $this->date_fin >= $today;
}

public function isOuverte(): bool
{
    return $this->isActive();
}
}