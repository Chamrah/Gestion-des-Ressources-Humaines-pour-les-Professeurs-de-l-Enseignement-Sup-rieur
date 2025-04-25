<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class NotificationController extends Controller
{
    public function index()
    {
        try {
            // استرجاع أحدث الإشعارات من جدول 'notifications'
            $notifications = DB::table('notifications')->latest()->get();

            // إرسال الإشعارات إلى الـ view
            return view('notifications.index', compact('notifications'));
        } catch (\Exception $e) {
            // سجل الخطأ
            \Log::error('Error fetching notifications: ' . $e->getMessage());

            return back()->withError('Something went wrong while fetching notifications.');
        }
    }
}
