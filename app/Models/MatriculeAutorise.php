<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatriculeAutorise extends Model
{
    protected $table = 'matricules_autorises';
    protected $fillable = ['matricule', 'utilise'];
}