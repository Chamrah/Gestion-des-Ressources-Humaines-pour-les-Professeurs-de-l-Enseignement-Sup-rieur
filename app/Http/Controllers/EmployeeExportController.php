<?php

namespace App\Http\Controllers;

use App\Exports\EmployeesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class EmployeeExportController extends Controller
{
    public function generateOnly()
    {
        // 1. نحذف النسخة القديمة
        if (Storage::exists('public/nom1.xlsx')) {
            Storage::delete('public/nom1.xlsx');
        }

        // 2. نخزّن نسخة جديدة محدثة فقط
        Excel::store(new EmployeesExport, 'public/nom1.xlsx');

        // 3. نرجع للمستخدم برسالة نجاح (ماكاين لا téléchargment)
        return back()->with('success', '✅ Le fichier nom1.xlsx a été mis à jour à ' . now()->format('H:i:s'));

    }
}
