<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Log;
class EmployeeImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
{
    // Nettoyage des clés
    $row = array_combine(array_map('trim', array_keys($row)), array_values($row));
    try {
        $nationalId = $row['cin'] ?? null;

        // ✅ فلترة: فقط الموظفين الجدد (حسب CIN)
        if (empty($nationalId) || Employee::where('national_id', $nationalId)->exists()) {
            return null; // Skip les doublons ou CIN vide
        }
    // $dateNaissance = $this->formatDate($row['date_de_naissance'] ?? null);
    // $dateRetraite = $this->formatDate($row['date_de_retarite'] ?? null);
    // $drmc = isset($row['drmc']) ? $this->formatDate($row['drmc']) : null;
    // $drm_att_s = isset($row['drmatts']) ? $this->formatDate($row['drmatts']) : null;
    // $dateEffet2 = isset($row['date_effet2']) ? $this->formatDate($row['date_effet2']) : null;
    // $dateEffet1 = isset($row['date_effet1']) ? $this->formatDate($row['date_effet1']) : null;


    // $drmc =isset($row['drmc']) ? Carbon::parse($row['drmc'])->format('Y-m-d') : null;
    // $drm_att_s =isset($row['drm_att_s']) ? Carbon::parse($row['drm_att_s'])->format('Y-m-d') : null;
    // $dateNaissance = isset($row['date_of_birth']) ? Carbon::parse($row['date_of_birth'])->format('Y-m-d') : null;
    //  $dateRetraite = isset($row['date_de_retarite']) ? Carbon::parse($row['date_de_retarite'])->format('Y-m-d') : null;
    //  $dateEffet1 = isset($row['date_effet1']) ? Carbon::parse($row['date_effet1'])->format('Y-m-d') : null;
    //  $dateEffet2 = isset($row['date_effet2']) ? Carbon::parse($row['date_effet2'])->format('Y-m-d') : null;

    // $dateNaissance = $this->formatDate($row['date_de_naissance'] ?? null) ??
    //              (isset($row['date_de_naissance']) ? Carbon::parse($row['date_de_naissance'])->format('Y-m-d') : null);

$dateRetraite = $this->formatDate($row['date_de_retarite'] ?? null) ??
                (isset($row['date_de_retarite']) ? Carbon::parse($row['date_de_retarite'])->format('Y-m-d') : null);
 $dateNaissance = isset($row['date_of_birth'])
                ? Carbon::parse($row['date_of_birth'])->format('Y-m-d')
                : ($this->formatDate($row['date_de_naissance'] ?? null) ?? null);
$drm_att_s = isset($row['drm_att_s'])
                ? Carbon::parse($row['drm_att_s'])->format('Y-m-d')
                : (isset($row['drmatts']) ? $this->formatDate($row['drmatts']) : null);


$drmc = $this->formatDate($row['drmc'] ?? null) ??
        (isset($row['drmc']) ? Carbon::parse($row['drmc'])->format('Y-m-d') : null);

// $drm_att_s = $this->formatDate($row['drmatts'] ?? null) ??
//              (isset($row['drmatts']) ? Carbon::parse($row['drmatts'])->format('Y-m-d') : null);

$dateEffet1 = $this->formatDate($row['date_effet1'] ?? null) ??
              (isset($row['date_effet1']) ? Carbon::parse($row['date_effet1'])->format('Y-m-d') : null);

$dateEffet2 = $this->formatDate($row['date_effet2'] ?? null) ??
              (isset($row['date_effet2']) ? Carbon::parse($row['date_effet2'])->format('Y-m-d') : null);

    // 🧠 Ajout automatique du position_id selon le cadre
    $cadre = $row['cadre'] ?? null;
    $position_id = 0;

    switch ($cadre) {
        case 'PES':
            $position_id = 1;
            break;

        case 'MC':
            $position_id = 2;
            break;
        case 'MCH':
            $position_id = 3;
            break;
        default:
            $position_id = $row['position_id'] ?? null; // fallback
            break;
    }

    return new Employee([
        'tpr' => $row['tpr'] ?? null,
        'first_name' => isset($row['nom']) ? $this->handleArabicText($row['nom']) : null,
        'last_name' => isset($row['prenom']) ? $this->handleArabicText($row['prenom']) : null,
        'nom_prenom' => $row['alasm_o_alnsb'] ?? null,
        'nomar' => $row['alnsb'] ?? null,
        'prenomar' => $row['alasm'] ?? null,
        "NOM_ET_PRENOM" => (isset($row['nom']) && isset($row['prenom']))
            ? $this->handleArabicText($row['nom']) . ' ' . $this->handleArabicText($row['prenom'])
            : null,
        'national_id' => $row['cin'] ?? null,
        'date_of_birth' => $dateNaissance,
        'date_retarite' => $dateRetraite,
        'email' => $row['email'] ?? null,
        'phone_number' => $row['phone_number'] ?? null,
        'address' => $row['address'] ?? null,
        'salary' => $row['salary'] ?? null,
        'emergency_contact' => $row['emergency_contact'] ?? null,
        'position_id' => $position_id,
        'cv' => $row['cv'] ?? null,
        'image' => $row['image'] ?? null,
'training' => (int) ($row['training'] ?? 0),


        'nationality' => $row['nationality'] ?? null,
        'drmc' => $drmc,
        'drm_att_s' => $drm_att_s,
        'cadre' => $cadre,
        'date_effet1' => $dateEffet1,
        'date_effet2' => $dateEffet2,
        'ech' => $row['ech'] ?? null,
        'indice' => $row['indice'] ?? null,
        'dep' => isset($row['dep']) ? mb_convert_encoding($row['dep'], 'UTF-8') : null,
        'specialite' => isset($row['specialite']) ? mb_convert_encoding($row['specialite'], 'UTF-8') : null,
        'sex' => $row['sex'] ?? null,
    ]);

} catch (\Exception $e) {
    \Log::error("Erreur import: " . $e->getMessage());
    return null;
}
}

    /**
     * Formate les dates venant d'Excel ou d'autres formats.
     */
    private function formatDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // Si la valeur est une date Excel (numérique)
        if (is_numeric($value) && (int)$value > 10000) {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // Si la valeur est une année (ex : 1961)
        if (is_numeric($value) && strlen($value) === 4) {
            return Carbon::createFromFormat('Y', $value)->startOfYear()->format('Y-m-d');
        }

        // Format classique : 14/02/1991
        if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $value)) {
            try {
                return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * Gère les textes arabes avec détection et conversion d'encodage.
     */
    private function handleArabicText($text)
    {
        if (empty($text)) {
            return null;
        }

        $encodings = ['UTF-8', 'ISO-8859-1'];
        $detectedEncoding = mb_detect_encoding($text, $encodings, true);

        if ($detectedEncoding && $detectedEncoding !== 'UTF-8') {
            return mb_convert_encoding($text, 'UTF-8', $detectedEncoding);
        }

        return mb_convert_encoding($text, 'UTF-8', 'UTF-8');}


        
}