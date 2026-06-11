<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatriculeAutorise extends Model
{
    protected $table = 'matricules_autorises';
    protected $fillable = ['matricule', 'role', 'utilise'];

    // Préfixes → rôles
    private static array $prefixes = [
        'ETU' => 'etudiante',
        'FOY' => 'resp_foyer',
        'TEC' => 'technicien',
        'ADM' => 'admin',
        'HEB' => 'resp_hebergement',
    ];

    // Détecte automatiquement le rôle depuis le matricule
    public static function detectRole(string $matricule): string
    {
        $matricule = strtoupper($matricule);
        foreach (self::$prefixes as $prefix => $role) {
            if (str_starts_with($matricule, $prefix)) {
                return $role;
            }
        }
        return 'etudiante'; // fallback
    }
}