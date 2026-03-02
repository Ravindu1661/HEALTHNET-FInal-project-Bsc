<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminNotificationController extends Controller
{
    // All notifications list page
    public function index()
    {
        $userId = Auth::id();

        $notifications = DB::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    // Mark single notification as read (AJAX or normal)
    public function markRead($id)
    {
        $userId = Auth::id();

        DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'Notification marked as read.');
    }

    // Mark all as read
    public function markAllRead()
    {
        $userId = Auth::id();

        DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
