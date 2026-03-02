<?php

namespace App\Http\Controllers\MedicalCentre;

use App\Http\Controllers\Controller;
use App\Models\MedicalCentre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MedicalCentreAnnouncementController extends Controller
{
    // ═══════════════════════════════════════════
    // HELPER
    // ═══════════════════════════════════════════
    private function getMedicalCentre(): MedicalCentre
    {
        return MedicalCentre::where('user_id', Auth::id())->firstOrFail();
    }

    // ═══════════════════════════════════════════
    // INDEX
    // ═══════════════════════════════════════════
    public function index(Request $request)
    {
        $mc     = $this->getMedicalCentre();
        $search = $request->input('search', '');
        $type   = $request->input('type', '');
        $status = $request->input('status', '');

        $query = DB::table('announcements')
            ->where('publisher_type', 'medical_centre')
            ->where('publisher_id', $mc->id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                  ->orWhere('content', 'LIKE', "%$search%");
            });
        }

        if ($type)   $query->where('announcement_type', $type);

        if ($status === 'active')   $query->where('is_active', true);
        if ($status === 'inactive') $query->where('is_active', false);

        $announcements = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total'    => DB::table('announcements')->where('publisher_type', 'medical_centre')->where('publisher_id', $mc->id)->count(),
            'active'   => DB::table('announcements')->where('publisher_type', 'medical_centre')->where('publisher_id', $mc->id)->where('is_active', true)->count(),
            'inactive' => DB::table('announcements')->where('publisher_type', 'medical_centre')->where('publisher_id', $mc->id)->where('is_active', false)->count(),
        ];

        return view('medical_centre.announcements.index', compact(
            'mc', 'announcements', 'stats', 'search', 'type', 'status'
        ));
    }

    // ═══════════════════════════════════════════
    // CREATE
    // ═══════════════════════════════════════════
    public function create()
    {
        $mc = $this->getMedicalCentre();
        return view('medical_centre.announcements.create', compact('mc'));
    }

    // ═══════════════════════════════════════════
    // STORE
    // ═══════════════════════════════════════════
    public function store(Request $request)
    {
        $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'content'           => ['required', 'string'],
            'announcement_type' => ['required', 'in:health_camp,special_offer,new_service,emergency,general'],
            'start_date'        => ['nullable', 'date'],
            'end_date'          => ['nullable', 'date', 'after_or_equal:start_date'],
            'image'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $mc = $this->getMedicalCentre();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

       DB::table('announcements')->insert([
        'publisher_type'    => 'medical_centre',
        'publisher_id'      => $mc->id,
        'title'             => $request->input('title'),
        'content'           => $request->input('content'),
        'announcement_type' => $request->input('announcement_type'),
        'image_path'        => $imagePath,
        'start_date'        => $request->input('start_date') ?: null,
        'end_date'          => $request->input('end_date') ?: null,
        'is_active'         => $request->boolean('is_active', true),
        'created_at'        => now(),
        'updated_at'        => now(),
    ]);


        return redirect()->route('medical_centre.announcements')
            ->with('success', 'Announcement created successfully.');
    }

    // ═══════════════════════════════════════════
    // SHOW
    // ═══════════════════════════════════════════
    public function show($id)
    {
        $mc           = $this->getMedicalCentre();
        $announcement = DB::table('announcements')
            ->where('id', $id)
            ->where('publisher_type', 'medical_centre')
            ->where('publisher_id', $mc->id)
            ->first();

        if (!$announcement) abort(404);

        return view('medical_centre.announcements.show', compact('mc', 'announcement'));
    }

    // ═══════════════════════════════════════════
    // EDIT
    // ═══════════════════════════════════════════
    public function edit($id)
    {
        $mc           = $this->getMedicalCentre();
        $announcement = DB::table('announcements')
            ->where('id', $id)
            ->where('publisher_type', 'medical_centre')
            ->where('publisher_id', $mc->id)
            ->first();

        if (!$announcement) abort(404);

        return view('medical_centre.announcements.edit', compact('mc', 'announcement'));
    }

    // ═══════════════════════════════════════════
    // UPDATE
    // ═══════════════════════════════════════════
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'content'           => ['required', 'string'],
            'announcement_type' => ['required', 'in:health_camp,special_offer,new_service,emergency,general'],
            'start_date'        => ['nullable', 'date'],
            'end_date'          => ['nullable', 'date', 'after_or_equal:start_date'],
            'image'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $mc           = $this->getMedicalCentre();
        $announcement = DB::table('announcements')
            ->where('id', $id)
            ->where('publisher_type', 'medical_centre')
            ->where('publisher_id', $mc->id)
            ->first();

        if (!$announcement) abort(404);

        $imagePath = $announcement->image_path;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

        // Remove image if checkbox checked
        if ($request->boolean('remove_image') && $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = null;
        }

        DB::table('announcements')->where('id', $id)->update([
        'title'             => $request->input('title'),
        'content'           => $request->input('content'),
        'announcement_type' => $request->input('announcement_type'),
        'image_path'        => $imagePath,
        'start_date'        => $request->input('start_date') ?: null,
        'end_date'          => $request->input('end_date') ?: null,
        'is_active'         => $request->boolean('is_active'),
        'updated_at'        => now(),
    ]);


        return redirect()->route('medical_centre.announcements')
            ->with('success', 'Announcement updated successfully.');
    }

    // ═══════════════════════════════════════════
    // TOGGLE ACTIVE STATUS
    // ═══════════════════════════════════════════
    public function toggleStatus($id)
    {
        $mc           = $this->getMedicalCentre();
        $announcement = DB::table('announcements')
            ->where('id', $id)
            ->where('publisher_type', 'medical_centre')
            ->where('publisher_id', $mc->id)
            ->first();

        if (!$announcement) abort(404);

        DB::table('announcements')->where('id', $id)->update([
            'is_active'  => !$announcement->is_active,
            'updated_at' => now(),
        ]);

        $msg = $announcement->is_active ? 'Announcement deactivated.' : 'Announcement activated.';
        return back()->with('success', $msg);
    }

    // ═══════════════════════════════════════════
    // DELETE
    // ═══════════════════════════════════════════
    public function destroy($id)
    {
        $mc           = $this->getMedicalCentre();
        $announcement = DB::table('announcements')
            ->where('id', $id)
            ->where('publisher_type', 'medical_centre')
            ->where('publisher_id', $mc->id)
            ->first();

        if (!$announcement) abort(404);

        if ($announcement->image_path && Storage::disk('public')->exists($announcement->image_path)) {
            Storage::disk('public')->delete($announcement->image_path);
        }

        DB::table('announcements')->where('id', $id)->delete();

        return redirect()->route('medical_centre.announcements')
            ->with('success', 'Announcement deleted successfully.');
    }
}
