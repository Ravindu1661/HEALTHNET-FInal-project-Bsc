<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorNotificationController extends Controller
{
    private function notifiableType(): string
    {
        return 'App\Models\User';
    }

    private function notifiableId(): int
    {
        return Auth::id();
    }

    // ══════════════════════════════════════════
    //  INDEX
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $query = Notification::where('notifiable_type', $this->notifiableType())
            ->where('notifiable_id', $this->notifiableId())
            ->orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('read')) {
            $query->where('is_read', $request->read === 'read');
        }

        // AJAX — return JSON
        if ($request->expectsJson() || $request->ajax()) {
            $notifications = $query->limit(10)->get();
            return response()->json([
                'success'       => true,
                'notifications' => $notifications,
                'unread_count'  => $this->unreadCount(),
            ]);
        }

        $notifications = $query->paginate(20);
        $unreadCount   = $this->unreadCount();

        // Stats
        $totalCount  = Notification::where('notifiable_type', $this->notifiableType())
            ->where('notifiable_id', $this->notifiableId())
            ->count();

        $categoryCounts = Notification::where('notifiable_type', $this->notifiableType())
            ->where('notifiable_id', $this->notifiableId())
            ->selectRaw('type, COUNT(*) as total,
                         SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread')
            ->whereNotNull('type')
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        $filterType = $request->get('read',  'all');
        $filterCat  = $request->get('type',  'all');

        // Email verification status
        $user = DB::table('users')->where('id', Auth::id())->first();

        return view('doctor.notifications.index', compact(
            'notifications',
            'unreadCount',
            'totalCount',
            'categoryCounts',
            'filterType',
            'filterCat',
            'user'
        ));
    }

    // ══════════════════════════════════════════
    //  MARK ONE READ
    // ══════════════════════════════════════════
    public function markAsRead($id)
    {
        try {
            Notification::where('notifiable_type', $this->notifiableType())
                ->where('notifiable_id', $this->notifiableId())
                ->where('id', $id)
                ->update(['is_read' => true, 'read_at' => now()]);

            return response()->json([
                'success'      => true,
                'unread_count' => $this->unreadCount(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ══════════════════════════════════════════
    //  MARK ALL READ
    // ══════════════════════════════════════════
    public function markAllAsRead()
    {
        try {
            Notification::where('notifiable_type', $this->notifiableType())
                ->where('notifiable_id', $this->notifiableId())
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);

            return response()->json([
                'success'      => true,
                'unread_count' => 0,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ══════════════════════════════════════════
    //  GET UNREAD COUNT — AJAX
    // ══════════════════════════════════════════
    public function getUnreadCount()
    {
        try {
            $count = $this->unreadCount();

            // Latest 5 unread for dropdown
            $latest = Notification::where('notifiable_type', $this->notifiableType())
                ->where('notifiable_id', $this->notifiableId())
                ->where('is_read', false)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(['id', 'type', 'title', 'message', 'created_at']);

            return response()->json([
                'success' => true,
                'count'   => $count,
                'latest'  => $latest,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'count'   => 0,
            ], 500);
        }
    }

    // ══════════════════════════════════════════
    //  RESEND EMAIL VERIFICATION
    // ══════════════════════════════════════════
    public function resendVerification(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user->email_verified_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your email is already verified.',
                ], 422);
            }

            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'Verification email sent to ' . $user->email,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email. Please try again.',
            ], 500);
        }
    }

    // ══════════════════════════════════════════
    //  PRIVATE HELPER
    // ══════════════════════════════════════════
    private function unreadCount(): int
    {
        return Notification::where('notifiable_type', $this->notifiableType())
            ->where('notifiable_id', $this->notifiableId())
            ->where('is_read', false)
            ->count();
    }
}
