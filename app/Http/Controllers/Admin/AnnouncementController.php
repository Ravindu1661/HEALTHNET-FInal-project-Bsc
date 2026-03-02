<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        // Allowed types for filters + forms (views තුළ foreach $types as $t) [file:3]
        $types = [
            'health_camp',
            'awareness',
            'special_offer',
            'new_service',
            'emergency',
            'general',
        ];

        $query = Announcement::query();

        // Search by title or content
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Type filter (request('type')) [file:3]
        if ($type = $request->get('type')) {
            if ($type !== 'All Types') {
                $query->where('announcement_type', $type);
            }
        }

        // Active filter (request('active') = 1 / 0) [file:3]
        if ($request->filled('active')) {
            if ($request->active === '1' || $request->active === '0') {
                $query->where('is_active', $request->active);
            }
        }

        $announcements = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->appends($request->query());

        return view('admin.announcements.index', compact('announcements', 'types'));
    }

    public function create()
    {
        $types = [
            'health_camp',
            'awareness',
            'special_offer',
            'new_service',
            'emergency',
            'general',
        ]; // views/admin/announcements/create.blade.php වල use කරන ලැයිස්තුව [file:3]

        return view('admin.announcements.create', compact('types'));
    }

    public function store(Request $request)
    {
        $types = [
            'health_camp',
            'awareness',
            'special_offer',
            'new_service',
            'emergency',
            'general',
        ];

        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'announcementtype' => ['required', Rule::in($types)],
            'content'          => ['required', 'string'],
            'startdate'        => ['nullable', 'date'],
            'enddate'          => ['nullable', 'date', 'after_or_equal:startdate'],
            'isactive'         => ['nullable', 'in:0,1'],
            'image'            => ['nullable', 'image', 'max:2048'],
        ]);

        $announcement = new Announcement();
        $announcement->title             = $validated['title'];
        $announcement->announcement_type = $validated['announcementtype'];
        $announcement->content           = $validated['content'];
        $announcement->start_date        = $validated['startdate'] ?? null;
        $announcement->end_date          = $validated['enddate'] ?? null;
        $announcement->is_active         = $validated['isactive'] ?? 1;

        // Publisher info (views show Publisher: publishertype + publisherid) [file:3]
        $announcement->publisher_type = 'admin';
        $announcement->publisher_id   = Auth::id();

        // Image upload (stored in storage/app/public/announcements/..) [file:3]
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('announcements', 'public');
            $announcement->image_path = $path;
        }

        $announcement->save();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement)
    {
        return view('admin.announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        $types = [
            'health_camp',
            'awareness',
            'special_offer',
            'new_service',
            'emergency',
            'general',
        ];

        return view('admin.announcements.edit', compact('announcement', 'types'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $types = [
            'health_camp',
            'awareness',
            'special_offer',
            'new_service',
            'emergency',
            'general',
        ];

        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'announcementtype' => ['required', Rule::in($types)],
            'content'          => ['required', 'string'],
            'startdate'        => ['nullable', 'date'],
            'enddate'          => ['nullable', 'date', 'after_or_equal:startdate'],
            'isactive'         => ['nullable', 'in:0,1'],
            'image'            => ['nullable', 'image', 'max:2048'],
        ]);

        $announcement->title             = $validated['title'];
        $announcement->announcement_type = $validated['announcementtype'];
        $announcement->content           = $validated['content'];
        $announcement->start_date        = $validated['startdate'] ?? null;
        $announcement->end_date          = $validated['enddate'] ?? null;
        $announcement->is_active         = $validated['isactive'] ?? $announcement->is_active;

        // Image update (delete old file if exists) [file:3][file:14]
        if ($request->hasFile('image')) {
            if ($announcement->image_path && Storage::disk('public')->exists($announcement->image_path)) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $path = $request->file('image')->store('announcements', 'public');
            $announcement->image_path = $path;
        }

        $announcement->save();

        return redirect()
            ->route('admin.announcements.show', $announcement)
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->image_path && Storage::disk('public')->exists($announcement->image_path)) {
            Storage::disk('public')->delete($announcement->image_path);
        }

        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    // Toggle active (index/show views use POST /admin/announcements/{id}/toggle) [file:3]
    public function toggle($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_active = $announcement->is_active ? 0 : 1;
        $announcement->save();

        return redirect()
            ->back()
            ->with('success', 'Announcement status updated.');
    }
}
