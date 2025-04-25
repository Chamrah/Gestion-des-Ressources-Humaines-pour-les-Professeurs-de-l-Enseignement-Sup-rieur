<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\ProfesseurImport;
use App\Exports\ProfesseurExport;
use Maatwebsite\Excel\Facades\Excel;

class ProfesseurController extends Controller
{
    public function export()
    {
        return Excel::download(new ProfesseurExport, 'professeurs.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new ProfesseurImport, $request->file('file'));

        return back()->with('success', 'Importation réussie.');
    }
}
