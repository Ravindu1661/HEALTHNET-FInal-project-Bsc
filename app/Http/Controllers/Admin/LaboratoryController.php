<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ProviderStatusChangedNotification;


class LaboratoryController extends Controller
{
    // Show list page with filters and pagination
    public function index(Request $request)
    {
        $query = Laboratory::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('registration_number', 'like', "%$search%")
                  ->orWhere('city', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laboratories = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.laboratories.index', compact('laboratories'));
    }

    // Form to create new lab
    public function create()
    {
        return view('admin.laboratories.create');
    }

    // Store new lab and user info with file uploads
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'name'          => 'required|string|max:255',
            'registration_number' => 'required|unique:laboratories,registration_number',
            'phone'         => 'required|max:20',
            'city'          => 'required|max:100',
            'province'      => 'required|max:100',
            'profile_image' => 'nullable|image|max:5120',
            'document'      => 'nullable|file|mimes:pdf,jpeg,png|max:5120',
        ]);

        DB::transaction(function () use ($request, $validated) {
            // Create user first
            $user = User::create([
                'email'             => $validated['email'],
                'password'          => Hash::make($validated['password']),
                'user_type'         => 'laboratory',
                'status'            => 'pending',
                'email_verified_at' => now(),
            ]);

            // Handle files
            $imagePath = $request->hasFile('profile_image')
                ? $request->file('profile_image')->store('laboratories/profiles', 'public')
                : null;

            $docPath = $request->hasFile('document')
                ? $request->file('document')->store('laboratories/documents', 'public')
                : null;

            // Create laboratory
            Laboratory::create([
                'user_id'           => $user->id,
                'name'              => $validated['name'],
                'registration_number'=> $validated['registration_number'],
                'phone'             => $validated['phone'],
                'email'             => $validated['email'],
                'address'           => $request->address,
                'city'              => $validated['city'],
                'province'          => $validated['province'],
                'postal_code'       => $request->postal_code,
                'services'          => $request->services ? json_encode(array_map('trim', explode(',', $request->services))) : null,
                'operating_hours'   => $request->operating_hours,
                'description'       => $request->description,
                'profile_image'     => $imagePath,
                'document_path'     => $docPath,
                'status'            => 'pending',
            ]);
        });

        return redirect()->route('admin.laboratories.index')->with('success', 'Laboratory created successfully!');
    }

    // Show lab details
    public function show($id)
    {
        $lab = Laboratory::with(['user', 'approvedBy'])->findOrFail($id);
       return view('admin.laboratories.show', compact('lab'));
    }

    // Change form
    public function edit($id)
    {
        $lab = Laboratory::with('user')->findOrFail($id);
        return view('admin.laboratories.edit', compact('lab'));
    }

    // Update laboratory information and user email/password + files
    public function update(Request $request, $id)
    {
        $lab = Laboratory::findOrFail($id);

        $validated = $request->validate([
            'email'         => 'required|email|unique:users,email,' . $lab->user_id,
            'name'          => 'required|string|max:255',
            'registration_number' => 'required|unique:laboratories,registration_number,' . $id,
            'phone'         => 'required|max:20',
            'city'          => 'required|max:100',
            'province'      => 'required|max:100',
            'profile_image' => 'nullable|image|max:5120',
            'document'      => 'nullable|file|mimes:pdf,jpeg,png|max:5120',
        ]);

        DB::transaction(function() use ($request, $lab, $validated) {
            $lab->user->update(['email' => $validated['email']]);
            if ($request->filled('password')) {
                $lab->user->update(['password' => Hash::make($request->password)]);
            }

            $imagePath = $lab->profile_image;
            if ($request->hasFile('profile_image')) {
                if ($imagePath) Storage::disk('public')->delete($imagePath);
                $imagePath = $request->file('profile_image')->store('laboratories/profiles', 'public');
            }

            $docPath = $lab->document_path;
            if ($request->hasFile('document')) {
                if ($docPath) Storage::disk('public')->delete($docPath);
                $docPath = $request->file('document')->store('laboratories/documents', 'public');
            }

            $lab->update([
                'name'              => $validated['name'],
                'registration_number'=> $validated['registration_number'],
                'phone'             => $validated['phone'],
                'email'             => $validated['email'],
                'address'           => $request->address,
                'city'              => $validated['city'],
                'province'          => $validated['province'],
                'postal_code'       => $request->postal_code,
                'services'          => $request->services ? json_encode(array_map('trim', explode(',', $request->services))) : null,
                'operating_hours'   => $request->operating_hours,
                'description'       => $request->description,
                'profile_image'     => $imagePath,
                'document_path'     => $docPath,
            ]);
        });

        return redirect()->route('admin.laboratories.index')->with('success', 'Laboratory updated successfully!');
    }

    // Delete
    public function destroy($id)
    {
        $lab = Laboratory::findOrFail($id);

        if ($lab->profile_image) Storage::disk('public')->delete($lab->profile_image);
        if ($lab->document_path) Storage::disk('public')->delete($lab->document_path);

        $lab->user()->delete();
        $lab->delete();

        return redirect()->route('admin.laboratories.index')->with('success', 'Laboratory deleted successfully!');
    }

    // Approval workflow functions
    public function approve($id)
{
    try {
        $lab = Laboratory::findOrFail($id);
        if ($lab->status != 'pending') {
            return response()->json(['success' => false, 'message' => 'Lab is not pending'], 400);
        }

        $lab->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);
        $lab->user->update(['status' => 'active']);

        // Notification
        try {
            $lab->user->notify(
                new ProviderStatusChangedNotification('approved', 'Laboratory')
            );
        } catch (\Exception $e) {
            // Optional: error logging for notification issue
        }

        return response()->json(['success' => true, 'message' => 'Laboratory approved!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


    public function reject($id)
    {
        $lab = Laboratory::findOrFail($id);
        $lab->update(['status' => 'rejected']);
        $lab->user()->update(['status' => 'rejected']);
        return response()->json(['success' => true, 'message' => 'Laboratory rejected.']);
    }

   public function suspend($id)
{
    try {
        $lab = Laboratory::findOrFail($id);

        $lab->update(['status' => 'suspended']);
        $lab->user->update(['status' => 'suspended']);

        // Notification
        try {
            $lab->user->notify(
                new ProviderStatusChangedNotification('suspended', 'Laboratory')
            );
        } catch (\Exception $e) {
            // Optional: error logging for notification issue
        }

        return response()->json(['success' => true, 'message' => 'Laboratory suspended.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


    // public function activate($id)
    // {
    //     $lab = Laboratory::findOrFail($id);
    //     $lab->update(['status' => 'approved']);
    //     $lab->user()->update(['status' => 'active']);
    //     return response()->json(['success' => true, 'message' => 'Laboratory activated!']);
    // }
    public function activate($id)
    {
        try {
            $lab = Laboratory::findOrFail($id);
            $lab->update(['status' => 'approved']);
            $lab->user->update(['status' => 'active']);

            // Email/notification
            try {
                $lab->user->notify(
                    new ProviderStatusChangedNotification('active', 'Laboratory')
                );
            } catch (\Exception $e) {
                \Log::error('Lab activate notification error: ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Laboratory activated!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}
