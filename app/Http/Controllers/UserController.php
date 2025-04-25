<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeImport;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

use Excel;


use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // public function import_excel_post(Request $request)
    // {
    //     // Validation du fichier Excel
    //     $request->validate([
    //         'excel_file' => 'required|file|mimes:xlsx,xls,csv',
    //     ]);

    //     // Importation des données
    //     Excel::import(new EmployeeImport, $request->file('excel_file'));

    //     // Message de succès
    //     return redirect()->back()->with('success', 'Employees imported successfully');
    // }
//     public function import_excel_post(Request $request)
//     {
//         // ✅ Validation du fichier Excel
//         $request->validate([
//             'excel_file' => 'required|file|mimes:xlsx,xls,csv',
//         ]);

//         // ✅ Importation des données
//         Excel::import(new EmployeeImport, $request->file('excel_file'));

//         // ✅ Attente pour éviter le conflit de mise à jour
//         sleep(1);

//         // ✅ Appel de la procédure stockée
//         DB::statement("CALL update_grades_and_echelons()");

//         // ✅ Retour avec message de succès
//         return redirect()->back()->with('success', 'Les employés ont été importés et les promotions ont été mises à jour.');
//     }

//     public function runUpgrade()
// {
//     DB::statement("CALL update_grades_and_echelons()");
//     return redirect()->back()->with('success', 'Mise à jour des grades et échelons effectuée avec succès.');
// }




public function import_excel_post(Request $request)
{
    $request->validate([
        'excel_file' => 'required|file|mimes:xlsx,xls,csv',
    ]);

    try {
        Excel::import(new EmployeeImport, $request->file('excel_file'));

        sleep(1);

        DB::statement("CALL update_grades_and_echelons()");

        return redirect()->back()->with('success', 'Les employés ont été importés et les promotions ont été mises à jour.');

    } catch (QueryException $e) {
        // ✅ Log du message technique pour le développeur
        Log::error('Erreur lors de l\'importation ou de la mise à jour : ' . $e->getMessage());

        // ✅ Message générique à afficher à l'utilisateur (sans détails techniques)
        return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la mise à jour. Veuillez vérifier vos données.');
    } catch (\Exception $e) {
        Log::error('Erreur inattendue : ' . $e->getMessage());

        return redirect()->back()->with('error', 'Une erreur inattendue est survenue.');
    }
}
public function runUpgrade()
{
    try {
        DB::statement("CALL update_grades_and_echelons()");
        return redirect()->route('employees.index')->with('success', 'Mise à jour des grades et échelons effectuée avec succès.');
    } catch (\Illuminate\Database\QueryException $e) {
        \Log::error('Erreur SQL dans runUpgrade: ' . $e->getMessage());
        return redirect()->route('employees.index')->with('error', 'Échec de la mise à jour. Vérifiez les données.');
    } catch (\Exception $e) {
        \Log::error('Erreur inattendue dans runUpgrade: ' . $e->getMessage());
        return redirect()->route('employees.index')->with('error', 'Une erreur inattendue est survenue.');
    }
}


}