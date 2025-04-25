<?php

namespace App\Imports;

use App\Models\Professeur;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProfesseurImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
{
    dd($row);  // Affiche toutes les clés et les valeurs dans $row
    return new Professeur([
        'tpr'               => $row['TPR'] ?? 'Valeur par défaut',
        'first_name'        => $row['NOM ET PRENOM'], // Correspondance avec Excel
        'last_name'         => $row['Nom'], // Correspondance avec Excel
        'nom'               => $row['الاسم و النسب'], // Correspondance avec Excel
        'prenom'            => $row['الاسم'], // Correspondance avec Excel
        'sex'               => $row['sex'],
        'cin'               => $row['CIN'],
        'date_of_birth'     => $row['DATE DE NAISSANCE'],
        'drmc'              => $row['D,R,M,C'],
        'drm_att_s'         => $row['D,R,M,ATT,S'],
        'cadre'             => $row['cadre'],
        'grade'             => $row['GRADE'],
        'date_effet1'        => $row['date_effet'],
        'ech'               => $row['ech'],
        'date_effet2'        => $row['date_effet'],

        'indice'            => $row['indice'],
        'dep'               => $row['DEP'],
        'specialite'        => $row['SPECIALITE'],
    ]);
}


}
