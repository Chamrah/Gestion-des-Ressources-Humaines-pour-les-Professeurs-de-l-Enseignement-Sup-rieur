<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'nom_complet',
        'ancien_grade',
        'nouveau_grade',
        'ancien_echelon',
        'nouveau_echelon',
        'date_changement',
        'message',
    ];

    // علاقة مع الموظف
   // App\Models\Notification.php
public function employee()
{
    return $this->belongsTo(Employee::class);
}

}
