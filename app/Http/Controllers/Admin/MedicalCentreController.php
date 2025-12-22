<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalCentre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ProviderStatusChangedNotification;


class MedicalCentreController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalCentre::with('user');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('registration_number', 'like', "%$search%")
                  ->orWhere('city', 'like', "%$search%");
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        $medicalCentres = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.medical-centres.index', compact('medicalCentres'));
    }

    public function create()
    {
        return view('admin.medical-centres.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'name' => 'required|max:255',
            'registration_number' => 'required|unique:medical_centres,registration_number',
            'phone' => 'required|max:20',
            'city' => 'required|max:100',
            'province' => 'required|max:100',
            'profile_image' => 'nullable|image|max:5120',
            'document' => 'nullable|file|mimes:pdf,jpeg,png|max:5120',
        ]);
        DB::transaction(function() use ($request, $validated) {
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_type' => 'medical_centre',
                'status' => 'pending',
                'email_verified_at' => now(),
            ]);
            $profileImagePath = $request->file('profile_image')
                ? $request->file('profile_image')->store('medical_centres/profiles', 'public')
                : null;
            $docPath = $request->file('document')
                ? $request->file('document')->store('medical_centres/documents', 'public')
                : null;
            MedicalCentre::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'registration_number' => $validated['registration_number'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $request->address,
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postal_code' => $request->postal_code,
                'operating_hours' => $request->operating_hours,
                'specializations' => $request->specializations ? json_encode(array_map('trim', explode(',', $request->specializations))) : null,
                'facilities' => $request->facilities ? json_encode(array_map('trim', explode(',', $request->facilities))) : null,
                'description' => $request->description,
                'profile_image' => $profileImagePath,
                'document_path' => $docPath,
                'status' => 'pending',
            ]);
        });
        return redirect()->route('admin.medical-centres.index')->with('success', 'Medical Centre created successfully!');
    }

    public function show($id)
    {
        $medicalCentre = MedicalCentre::with(['user', 'approvedBy'])->findOrFail($id);
        return view('admin.medical-centres.show', compact('medicalCentre'));
    }

    public function edit($id)
    {
        $medicalCentre = MedicalCentre::with('user')->findOrFail($id);
        return view('admin.medical-centres.edit', compact('medicalCentre'));
    }

    public function update(Request $request, $id)
    {
        $medicalCentre = MedicalCentre::findOrFail($id);
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $medicalCentre->user_id,
            'name' => 'required|max:255',
            'registration_number' => 'required|unique:medical_centres,registration_number,' . $id,
            'phone' => 'required|max:20',
            'city' => 'required|max:100',
            'province' => 'required|max:100',
            'profile_image' => 'nullable|image|max:5120',
            'document' => 'nullable|file|mimes:pdf,jpeg,png|max:5120',
        ]);
        DB::transaction(function() use ($request, $medicalCentre, $validated) {
            $medicalCentre->user->update(['email' => $validated['email']]);
            $profileImagePath = $medicalCentre->profile_image;
            if ($request->hasFile('profile_image')) {
                if ($profileImagePath) Storage::disk('public')->delete($profileImagePath);
                $profileImagePath = $request->file('profile_image')->store('medical_centres/profiles', 'public');
            }
            $docPath = $medicalCentre->document_path;
            if ($request->hasFile('document')) {
                if ($docPath) Storage::disk('public')->delete($docPath);
                $docPath = $request->file('document')->store('medical_centres/documents', 'public');
            }
            $medicalCentre->update([
                'name' => $validated['name'],
                'registration_number' => $validated['registration_number'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $request->address,
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postal_code' => $request->postal_code,
                'operating_hours' => $request->operating_hours,
                'specializations' => $request->specializations ? json_encode(array_map('trim', explode(',', $request->specializations))) : null,
                'facilities' => $request->facilities ? json_encode(array_map('trim', explode(',', $request->facilities))) : null,
                'description' => $request->description,
                'profile_image' => $profileImagePath,
                'document_path' => $docPath,
            ]);
        });
        return redirect()->route('admin.medical-centres.index')->with('success', 'Medical Centre updated successfully!');
    }

    public function destroy($id)
    {
        $medicalCentre = MedicalCentre::findOrFail($id);
        if ($medicalCentre->profile_image) Storage::disk('public')->delete($medicalCentre->profile_image);
        if ($medicalCentre->document_path) Storage::disk('public')->delete($medicalCentre->document_path);
        $medicalCentre->user()->delete();
        $medicalCentre->delete();
        return redirect()->route('admin.medical-centres.index')->with('success', 'Medical Centre deleted successfully!');
    }

    public function approve($id)
    {
        try {
            $medicalCentre = MedicalCentre::findOrFail($id);

            if ($medicalCentre->status != 'pending') {
                return response()->json(['success' => false, 'message' => 'Medical Centre is not pending'], 400);
            }
            $medicalCentre->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);
            $medicalCentre->user->update(['status' => 'active']);

            // Notification/Email Send
            try {
                $medicalCentre->user->notify(
                    new ProviderStatusChangedNotification('approved', 'Medical Centre')
                );
            } catch (\Exception $e) {
                // Error log/handle, optional
            }

            return response()->json(['success' => true, 'message' => 'Medical Centre approved!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function reject($id)
    {
        $medicalCentre = MedicalCentre::findOrFail($id);
        $medicalCentre->update(['status' => 'rejected']);
        $medicalCentre->user()->update(['status' => 'rejected']);
        return response()->json(['success' => true, 'message' => 'Medical Centre rejected.']);
    }

   public function suspend($id)
    {
        try {
            $medicalCentre = MedicalCentre::findOrFail($id);
            $medicalCentre->update(['status' => 'suspended']);
            $medicalCentre->user->update(['status' => 'suspended']);

            // Notification/Email Send
            try {
                $medicalCentre->user->notify(
                    new ProviderStatusChangedNotification('suspended', 'Medical Centre')
                );
            } catch (\Exception $e) {
                // Error log/handle, optional
            }

            return response()->json(['success' => true, 'message' => 'Medical Centre suspended.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    // public function activate($id)
    // {
    //     $medicalCentre = MedicalCentre::findOrFail($id);
    //     $medicalCentre->update(['status' => 'approved']);
    //     $medicalCentre->user()->update(['status' => 'active']);
    //     return response()->json(['success' => true, 'message' => 'Medical Centre activated!']);
    // }

    public function activate($id)
    {
        try {
            $centre = MedicalCentre::findOrFail($id);
            $centre->update(['status' => 'approved']);
            $centre->user->update(['status' => 'active']);

            // Email/notification
            try {
                $centre->user->notify(
                    new ProviderStatusChangedNotification('active', 'Medical Centre')
                );
            } catch (\Exception $e) {
                \Log::error('Medical Centre activate notification error: ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Medical Centre activated!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}
