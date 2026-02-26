<?php
namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabNotificationController extends Controller
{
    private function baseQuery()
    {
        return DB::table('notifications')
            ->where('notifiable_type', \App\Models\User::class)
            ->where('notifiable_id', Auth::id());
    }

    public function index(Request $request)
    {
        $query = $this->baseQuery();
        if ($request->filled('type') && $request->type === 'unread')
            $query->where('is_read', false);
        $notifications = $query->orderBy('created_at','desc')->paginate(20);
        $unreadCount = $this->baseQuery()->where('is_read', false)->count();
        return view('laboratory.notifications.index', compact('notifications','unreadCount'));
    }

    public function markRead($id)
    {
        $this->baseQuery()->where('id', $id)
            ->update(['is_read' => true, 'read_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        $this->baseQuery()->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        return back()->with('success', 'All notifications marked as read!');
    }

    public function destroy($id)
    {
        $this->baseQuery()->where('id', $id)->delete();
        return back()->with('success', 'Notification deleted!');
    }

    public function getCount()
    {
        return response()->json([
            'count' => $this->baseQuery()->where('is_read', false)->count()
        ]);
    }
}
