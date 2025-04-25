<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Employee;  // إضافة هذا السطر
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notifications()
{
    $notifications = Notification::with('employee')->latest()->paginate();
    return view('rh.notifications', compact('notifications'));
}


    public function index(Request $request)
    {
        $year = $request->input('year', date('Y')); // السنة الحالية افتراضيًا
        $employee_id = $request->input('employee_id');

        // استعلام الإشعارات بناءً على السنة الحالية
        $notifications = Notification::whereYear('date_changement', $year);

        // إذا تم تحديد الموظف، قم بتصفية الإشعارات حسب الموظف
        if ($employee_id) {
            $notifications->where('employee_id', $employee_id);
        }

        // جلب الإشعارات
        $notifications = $notifications->get();

        $employees = Employee::all(); // جلب جميع الموظفين لاختيارهم في الفورم

        return view('notifications.index', compact('notifications', 'employees'));

    }


}
