<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Employee::select([

            'tpr',
            'nom_prenom',
            'nomar',
            'prenomar',
            'first_name',
            'last_name',
            "NOM_ET_PRENOM",
            'sex',
            'national_id',
            // 'cin',
            'date_of_birth',
            'date_retarite',
            'drmc',
            'drm_att_s',
            'cadre',
            'grade',
            'date_effet1',
            'ech',
            'date_effet2',
            'indice',
            'dep',
            'specialite',
            // 'updated_at', // ✅ Pour vérifier la mise à jour



        ])->get();
    }

    public function headings(): array
    {
        return [

            'TPR',
            'الاسم و النسب',
            'النسب',
            'الاسم',
            'prenom',
            'nom',
            "nom et prenom",
            'Sex',
            'CIN',
            'date_of_birth',
            "Date de retarite",
            // "date de naissance",
            'drmc',
            'drm_att_s',
            'Cadre',
            'Grade',
            "date_effet1 ",
            'ech',
            "date_effet2",
            'Indice',
            'DEP',
            'SPECIALITE',
            // 'Date de dernière modification',
            // 'TPR','الاسم و النسب','الاسم','النسب','NOM ET PRENOM','Nom','Prénom','sex','CIN','date de naissance','date de naissance','Date de retarite','D,R,M,C','D,R,M,ATT,S','cadre','date_effet1','ech','date_effet2','indice','DEP','SPECIALITE',




        ];
    }


    
}


