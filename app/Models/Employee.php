<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class Employee extends Model
{
    use HasFactory, SoftDeletes;

    // Nom de la table associée
    protected $table = 'employees';

    // Ajout des nouveaux champs dans le tableau $fillable pour les autoriser à être assignés en masse
    protected $fillable = [
        'tpr',
        'nom_prenom',
        'first_name',
        'last_name',
        'nomar',
        'prenomar',
        'NOM_ET_PRENOM', // ← بدون فراغات
        'national_id',
        'drmc',
        'drm_att_s',
        'cadre',
        'date_effet1',
        'date_effet2',
        'sex',
        'ech',
        'indice',
        'dep',
        'specialite',
        'nationality',
        'gender',
        'date_of_birth',
        'date_retarite',
        'email',
        'phone_number',
        'address',
        'salary',
        'emergency_contact',
        'cv',
        'image',
        'position_id',
        'training',
        'start_date',
    ];

    // Définition de la relation entre Employee et Position
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    // Définition de la relation entre Employee et Vacation
    public function vacations()
    {
        return $this->hasMany(Vacation::class);
    }

    // Définition de la relation entre Employee et Schedule
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Vérifier si l'employé est actuellement en vacances.
     */
    public function isOnVacation()
    {
        $today = now()->toDateString();
        return $this->vacations()
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->exists();
    }

    // Générer un UUID pour chaque employé avant sa création
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    /**
     * Vérifier si l'employé a une prochaine période de vacances.
     */
    public function hasUpcomingVacation()
    {
        $today = now()->toDateString(); // Date d'aujourd'hui

        // Vérifier s'il a des vacances à venir
        return $this->vacations()
            ->where('start_date', '>', $today) // Vacances commençant après aujourd'hui
            ->exists();}


// Dans app/Models/Employee.php
public function notifications()
{
    return $this->hasMany(Notification::class);
}

// Et dans le controller
public function index()
{
    $employee = auth()->user()->employee; // Supposant une relation entre User et Employee
    $notifications = $employee->notifications()
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    return view('notifications.index', compact('notifications'));
}
// في app/Models/Employee.php
public function getNomCompletAttribute()
{
    return $this->prenom . ' ' . $this->nom;
}


}
