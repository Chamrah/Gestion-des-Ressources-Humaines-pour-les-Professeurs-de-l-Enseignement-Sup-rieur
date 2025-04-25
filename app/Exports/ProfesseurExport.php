<?php

namespace App\Exports;


use App\Models\Professeur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfesseurExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Professeur::all();
    }

    public function headings(): array
    {
        return [
            'id', 'tpr', 'first_name', 'last_name', 'nom', 'prenom', 'sex', 'cin',
            'date_of_birth', 'drmc', 'drm_att_s', 'cadre', 'grade', 'date_effet',
            'ech', 'indice', 'dep', 'specialite', 'national_id', 'nationality',
            'gender', 'email', 'phone_number', 'address', 'salary', 'emergency_contact',
            'cv', 'image', 'position', 'training', 'created_at', 'updated_at'
        ];
    }
}

