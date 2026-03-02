<?php
// app/Http/Controllers/Admin/AdminLogController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     */
    public function index(Request $request)
    {
        $module     = $request->get('module');      // optional: string inside action/description
        $userId     = $request->get('user_id');
        $action     = $request->get('action');
        $dateFrom   = $request->get('date_from');
        $dateTo     = $request->get('date_to');

        $logs = ActivityLog::with('user')
            ->when($userId, function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->when($action, function ($q) use ($action) {
                $q->where('action', 'like', '%' . $action . '%');
            })
            ->when($module, function ($q) use ($module) {
                // module එක description / action ඇතුළේ text එකක් විදිහට log කරන ලෙස assume කරනවා
                $q->where(function ($sub) use ($module) {
                    $sub->where('description', 'like', '%' . $module . '%')
                        ->orWhere('action', 'like', '%' . $module . '%');
                });
            })
            ->when($dateFrom, function ($q) use ($dateFrom) {
                $q->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($q) use ($dateTo) {
                $q->whereDate('created_at', '<=', $dateTo);
            })
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('admin.logs.index', [
            'logs'      => $logs,
            'module'    => $module,
            'userId'    => $userId,
            'action'    => $action,
            'dateFrom'  => $dateFrom,
            'dateTo'    => $dateTo,
        ]);
    }

    /**
     * Display the specified log entry.
     */
    public function show(int $id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);

        return view('admin.logs.show', compact('log'));
    }

    /**
     * Helper method (optional) – centralized logging
     * call from වෙන controller වලून static ලෙස.
     */
    public static function record(?int $userId, string $action, ?string $description = null, ?Request $request = null): void
    {
        $request = $request ?? request();

        ActivityLog::create([
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'created_at'  => now(),
        ]);
    }
}
