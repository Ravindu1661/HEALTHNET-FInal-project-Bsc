<?php
namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LabProfileController extends Controller
{
    private function getLab(): Laboratory
    {
        return Laboratory::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $lab = $this->getLab();
        return view('laboratory.profile.index', compact('lab'));
    }

    public function edit()
    {
        $lab = $this->getLab();
        return view('laboratory.profile.edit', compact('lab'));
    }

    public function update(Request $request)
    {
        $lab = $this->getLab();
        $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:255',
            'address'          => 'nullable|string',
            'city'             => 'nullable|string|max:100',
            'province'         => 'nullable|string|max:100',
            'operating_hours'  => 'nullable|string',
            'description'      => 'nullable|string',
            'services'         => 'nullable|array',
        ]);

        $lab->update($request->only([
            'name','phone','email','address','city','province',
            'postal_code','operating_hours','description','services',
        ]));

        return redirect()->route('laboratory.profile.index')
            ->with('success', 'Profile updated!');
    }

    public function uploadImage(Request $request)
    {
        $lab = $this->getLab();
        $request->validate(['profile_image' => 'required|image|max:2048']);

        if ($lab->profile_image) {
            Storage::disk('public')->delete($lab->profile_image);
        }

        $path = $request->file('profile_image')
            ->store('laboratory/profiles', 'public');
        $lab->update(['profile_image' => $path]);

        return back()->with('success', 'Profile image updated!');
    }

    public function uploadDocument(Request $request)
    {
        $lab = $this->getLab();
        $request->validate(['document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120']);

        $path = $request->file('document')
            ->store('laboratory/documents', 'public');
        $lab->update(['document_path' => $path]);

        return back()->with('success', 'Document uploaded!');
    }
}
