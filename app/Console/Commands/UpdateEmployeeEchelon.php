<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Notification;
use Carbon\Carbon;

class UpdateEmployeeEchelon extends Command
{
    protected $signature = 'employee:update-echelons';
    protected $description = 'Met à jour automatiquement les grades et échelons des employés selon les règles RH';

    public function handle()
    {
        $today = Carbon::now();
        $employees = Employee::all();

        foreach ($employees as $employee) {
            if (!$employee->grade || !$employee->echelon || !$employee->date_effet1) {
                continue; // ignorer les données incomplètes
            }

            $dateEffet = Carbon::parse($employee->date_effet1);
            $dueDate = $dateEffet->copy()->addYears(2)->startOfYear();

            if ($today->greaterThanOrEqualTo($dueDate)) {

                $ancien_grade = $employee->grade;
                $ancien_echelon = $employee->echelon;
                $nouveau_grade = $ancien_grade;
                $nouveau_echelon = $ancien_echelon;

                // Logique d'évolution
                if ($ancien_grade == 'MC') {
                    if ($ancien_echelon < 3) {
                        $nouveau_echelon++;
                    } else {
                        $nouveau_grade = 'MCH';
                        $nouveau_echelon = 1;
                    }
                } elseif ($ancien_grade == 'MCH') {
                    if ($ancien_echelon < 4) {
                        $nouveau_echelon++;
                    } else {
                        $nouveau_grade = 'PES';
                        $nouveau_echelon = 1;
                    }
                } elseif ($ancien_grade == 'PES') {
                    if ($ancien_echelon < 6) {
                        $nouveau_echelon++;
                    } else {
                        continue; // Aucun changement si PES + 6
                    }
                }

                // Mise à jour des données de l'employé
                $employee->update([
                    'grade' => $nouveau_grade,
                    'ech' => $nouveau_echelon,
                    'date_effet1' => Carbon::createFromDate($today->year + 2, 1, 1)->startOfDay(),
                ]);

                // Créer une notification
                Notification::create([
                    'employee_id' => $employee->id,
                    'ancien_grade' => $ancien_grade,
                    'nouveau_grade' => $nouveau_grade,
                    'ancien_echelon' => $ancien_echelon,
                    'nouveau_echelon' => $nouveau_echelon,
                    'date_changement' => now(),
                    'message' => "L’enseignant " . $employee->NOM_ET_PRENOM . " est passé au grade $nouveau_grade, échelon $nouveau_echelon le " . now()->format('d/m/Y'),

                ]);
            }
        }

        $this->info('✅ Mise à jour terminée avec succès.');
    }
}
