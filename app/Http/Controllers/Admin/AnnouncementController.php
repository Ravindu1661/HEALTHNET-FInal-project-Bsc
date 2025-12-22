<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $q = Announcement::query()->orderByDesc('created_at');

        if ($request->filled('type')) {
            $q->where('announcement_type', $request->string('type'));
        }

        if ($request->filled('active')) {
            $active = $request->string('active')->toString();
            if (in_array($active, ['0', '1'], true)) {
                $q->where('is_active', (int) $active);
            }
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $q->where(function ($qq) use ($search) {
                $qq->where('title', 'like', "%{$search}%")
                   ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $announcements = $q->paginate(10)->appends($request->query());

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $types = Announcement::types();
        return view('admin.announcements.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // Admin publishes as admin
        $data['publisher_type'] = 'admin';
        $data['publisher_id']   = auth()->id();

        // image upload -> image_path
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('announcements', 'public');
        }

        $data['is_active'] = (int) ($data['is_active'] ?? 1);

        Announcement::create($data);

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
        $types = Announcement::types();
        return view('admin.announcements.edit', compact('announcement', 'types'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->validateData($request, $announcement->id);

        if ($request->hasFile('image')) {
            if ($announcement->image_path && Storage::disk('public')->exists($announcement->image_path)) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $data['image_path'] = $request->file('image')->store('announcements', 'public');
        }

        $data['is_active'] = (int) ($data['is_active'] ?? 1);

        $announcement->update($data);

        return redirect()
            ->route('admin.announcements.index')
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

    public function toggleActive(Announcement $announcement)
    {
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement status updated.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'announcement_type' => ['required', Rule::in(Announcement::types())],

            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],

            'is_active' => ['nullable', 'boolean'],

            'image' => ['nullable', 'image', 'max:2048'], // 2MB
        ]);
    }
}
