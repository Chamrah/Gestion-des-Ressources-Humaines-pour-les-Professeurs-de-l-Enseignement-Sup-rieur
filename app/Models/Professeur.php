<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    use HasFactory;

    // Définir les colonnes que vous pouvez remplir via des requêtes massives
    protected $fillable = [
        'tpr',
        'first_name',
        'last_name',
        'nom',
        'prenom',
        'sex',
        'cin',
        'date_of_birth',
        'drmc',
        'drm_att_s',
        'cadre',
        'grade',
        'date_effet',
        'ech',
        'indice',
        'dep',
        'specialite',
    ];
}
