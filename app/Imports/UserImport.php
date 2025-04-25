<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeeImport implements ToModel
{
    private $rowIndex = 0;

    public function model(array $row)
    {
        $this->rowIndex++;

        // Ignore header row
        if ($this->rowIndex === 1) {
            return null;
        }

        // Générer NOM_ET_PRENOM à partir de first_name et last_name
        $firstName = $row[1] ?? '';
        $lastName = $row[2] ?? '';
        $fullName = trim($firstName . ' ' . $lastName);

        return new Employee([
            'tpr' => $row[0] ?? null,
            'uuid' => Str::uuid()->toString(),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'NOM_ET_PRENOM' => $fullName !== '' ? $fullName : null,
            'nom_prenom' => $row[3] ?? null,
            'nomar' => $row[4] ?? null,
            'prenomar' => $row[5] ?? null,
            'national_id' => $row[6] ?? null,
            'cin' => $row[7] ?? null,
            'drmc' => $this->formatDate($row[8] ?? null),
            'drm_att_s' => $this->formatDate($row[9] ?? null),
            'cadre' => $row[10] ?? null,
            'grade' => $row[11] ?? null,
            'date_effet1' => $this->formatDate($row[12] ?? null),
            'ech' => $row[13] ?? null,
            'indice' => $row[14] ?? null,
            'dep' => $row[15] ?? null,
            'specialite' => $row[16] ?? null,
            'nationality' => $row[17] ?? null,
            'gender' => $row[18] ?? null,
            'sex' => $row[19] ?? null,
            'date_of_birth' => $this->formatDate($row[20] ?? null),
            'email' => $row[21] ?? null,
            'phone_number' => $row[22] ?? null,
            'address' => $row[23] ?? null,
            'salary' => $row[24] ?? null,
            'emergency_contact' => $row[25] ?? null,
            'position_id' => $row[26] ?? null,
            'cv' => $row[27] ?? null,
            'image' => $row[28] ?? null,
            'training' => $row[29] ?? null,
            'start_date' => $this->formatDate($row[30] ?? null),
            'created_at' => now(),
            'updated_at' => now(),
            'schedule_id' => $row[31] ?? null,
            'is_active' => $row[32] ?? true,
            'date_effet2' => $this->formatDate($row[33] ?? null),
        ]);
    }

    private function formatDate($date)
    {
        if (empty($date)) return null;

        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function convertSex($sex)
    {
        return match (strtolower($sex)) {
            'm' => 'male',
            'f' => 'female',
            default => 'other',
        };
    }
}
