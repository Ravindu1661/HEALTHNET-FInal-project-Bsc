<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminNotificationController extends Controller
{
    // ── Notification type → icon map ────────────────────────────
    private function typeIcon(string $type): string
    {
        return match ($type) {
            'appointment'         => 'calendar-check',
            'payment'             => 'money-bill-wave',
            'prescription'        => 'prescription',
            'lab_report','labreport' => 'flask',
            'reminder'            => 'bell',
            'announcement'        => 'bullhorn',
            'workplace_request'   => 'hospital',
            'workplace_approved'  => 'check-circle',
            'workplace_rejected'  => 'times-circle',
            default               => 'bell',
        };
    }

    // ── Base query helper ────────────────────────────────────────
    private function baseQuery()
    {
        return DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', Auth::id());
    }

    // ════════════════════════════════════════════════════════════
    // INDEX — Full notifications page
    // ════════════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');   // all | unread | read
        $type   = $request->input('type', '');

        $query = $this->baseQuery();

        if ($filter === 'unread') $query->where('is_read', false);
        if ($filter === 'read')   $query->where('is_read', true);
        if ($type)                $query->where('type', $type);

        $notifications = $query
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Counts for tabs
        $unreadCount = $this->baseQuery()->where('is_read', false)->count();
        $totalCount  = $this->baseQuery()->count();

        // Type breakdown (for filter dropdown)
        $typeCounts = $this->baseQuery()
            ->select('type', DB::raw('COUNT(*) as cnt'))
            ->groupBy('type')
            ->pluck('cnt', 'type');

        return view('admin.notifications.index', compact(
            'notifications',
            'unreadCount',
            'totalCount',
            'typeCounts',
            'filter',
            'type'
        ));
    }

    // ════════════════════════════════════════════════════════════
    // MARK SINGLE — as read (AJAX or redirect)
    // ════════════════════════════════════════════════════════════
    public function markRead(Request $request, $id)
    {
        $updated = $this->baseQuery()
            ->where('id', $id)
            ->update([
                'is_read' => true,
                'read_at' => now(),
                'updated_at' => now(),
            ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => (bool) $updated,
                'message' => $updated ? 'Marked as read.' : 'Notification not found.',
            ]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    // ════════════════════════════════════════════════════════════
    // MARK ALL — as read
    // ════════════════════════════════════════════════════════════
    public function markAllRead(Request $request)
    {
        $this->baseQuery()
            ->where('is_read', false)
            ->update([
                'is_read'    => true,
                'read_at'    => now(),
                'updated_at' => now(),
            ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    // ════════════════════════════════════════════════════════════
    // DELETE SINGLE
    // ════════════════════════════════════════════════════════════
    public function destroy(Request $request, $id)
    {
        $deleted = $this->baseQuery()
            ->where('id', $id)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => (bool) $deleted,
                'message' => $deleted ? 'Notification deleted.' : 'Not found.',
            ]);
        }

        return back()->with('success', 'Notification deleted.');
    }

    // ════════════════════════════════════════════════════════════
    // DELETE ALL (read notifications only)
    // ════════════════════════════════════════════════════════════
    public function destroyRead(Request $request)
    {
        $this->baseQuery()
            ->where('is_read', true)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Read notifications cleared.']);
        }

        return back()->with('success', 'Read notifications cleared.');
    }

    // ════════════════════════════════════════════════════════════
    // AJAX — Get latest N notifications (topbar dropdown)
    // GET /admin/notifications/latest
    // ════════════════════════════════════════════════════════════
    public function latest()
    {
        $notifications = $this->baseQuery()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'type'       => $n->type ?? 'general',
                'icon'       => $this->typeIcon($n->type ?? 'general'),
                'title'      => $n->title ?? 'Notification',
                'message'    => $n->message ?? '',
                'is_read'    => (bool) $n->is_read,
                'time'       => Carbon::parse($n->created_at)->diffForHumans(),
                'created_at' => $n->created_at,
            ]);

        $unreadCount = $this->baseQuery()->where('is_read', false)->count();

        return response()->json([
            'success'      => true,
            'notifications'=> $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    // ════════════════════════════════════════════════════════════
    // AJAX — Unread count only (badge refresh)
    // GET /admin/notifications/count
    // ════════════════════════════════════════════════════════════
    public function count()
    {
        $count = $this->baseQuery()->where('is_read', false)->count();

        return response()->json(['unread_count' => $count]);
    }
}
