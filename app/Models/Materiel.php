<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_id',
        'stock_id',
        'nom_materiel',
        'quantite',
        'stock_epuise',
        'description_incident',
    ];

    protected $casts = [
        'stock_epuise' => 'boolean',
    ];

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class, 'maintenance_id');
    }
    public function stock()
{
    return $this->belongsTo(Stock::class, 'stock_id');
}
}