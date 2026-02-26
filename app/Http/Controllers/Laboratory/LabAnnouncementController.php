<?php
namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LabAnnouncementController extends Controller
{
    private function getLab(): Laboratory
    {
        return Laboratory::where('userid', Auth::id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $lab = $this->getLab();

        $query = Announcement::where('publishertype', 'laboratory')
            ->where('publisherid', $lab->id);

        if ($request->input('filter') === 'active') {
            $query->where('isactive', true);
        } elseif ($request->input('filter') === 'expired') {
            $query->where(function($q) {
                $q->where('isactive', false)
                  ->orWhere('enddate', '<', now()->toDateString());
            });
        }

        $announcements = $query->latest()->paginate(12);

        return view('laboratory.announcements.index', compact('lab', 'announcements'));
    }

    public function create()
    {
        $lab = $this->getLab();
        return view('laboratory.announcements.create', compact('lab'));
    }

    public function store(Request $request)
    {
        $lab = $this->getLab();

        $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'announcementtype' => 'required|string',
            'startdate'        => 'nullable|date',
            'enddate'          => 'nullable|date',
            'image'            => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

        Announcement::create([
            'publishertype'    => 'laboratory',
            'publisherid'      => $lab->id,
            'title'            => $request->input('title'),
            'content'          => $request->input('content'),
            'announcementtype' => $request->input('announcementtype'),
            'imagepath'        => $imagePath,
            'startdate'        => $request->input('startdate'),
            'enddate'          => $request->input('enddate'),
            'isactive'         => $request->boolean('isactive', true),
        ]);

        return redirect()->route('laboratory.announcements.index')
            ->with('success', 'Announcement created!');
    }

    public function edit(Announcement $announcement)
    {
        $lab = $this->getLab();
        abort_if($announcement->publisherid !== $lab->id, 403);
        return view('laboratory.announcements.edit', compact('lab', 'announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $lab = $this->getLab();
        abort_if($announcement->publisherid !== $lab->id, 403);

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $data = [
            'title'            => $request->input('title'),
            'content'          => $request->input('content'),
            'announcementtype' => $request->input('announcementtype'),
            'startdate'        => $request->input('startdate'),
            'enddate'          => $request->input('enddate'),
            'isactive'         => $request->boolean('isactive'),
        ];

        if ($request->hasFile('image')) {
            if ($announcement->imagepath) {
                Storage::disk('public')->delete($announcement->imagepath);
            }
            $data['imagepath'] = $request->file('image')->store('announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('laboratory.announcements.index')
            ->with('success', 'Announcement updated!');
    }

    public function destroy(Announcement $announcement)
    {
        $lab = $this->getLab();
        abort_if($announcement->publisherid !== $lab->id, 403);

        if ($announcement->imagepath) {
            Storage::disk('public')->delete($announcement->imagepath);
        }
        $announcement->delete();

        return back()->with('success', 'Announcement deleted!');
    }

    public function toggleActive(Announcement $announcement)
    {
        $lab = $this->getLab();
        abort_if($announcement->publisherid !== $lab->id, 403);
        $announcement->update(['isactive' => !$announcement->isactive]);
        return response()->json(['is_active' => $announcement->isactive]);
    }
}
