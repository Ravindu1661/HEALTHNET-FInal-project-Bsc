<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ProviderStatusChangedNotification;

class PharmacyController extends Controller
{
    // Index: List + filters
    public function index(Request $request)
    {
        $query = Pharmacy::with('user');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('registration_number', 'like', "%$search%")
                  ->orWhere('city', 'like', "%$search%");
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        $pharmacies = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.pharmacies.index', compact('pharmacies'));
    }

    public function create()
    {
        return view('admin.pharmacies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'name' => 'required|max:255',
            'registration_number' => 'required|unique:pharmacies,registration_number',
            'pharmacist_name' => 'required|max:100',
            'pharmacist_license' => 'required|max:100',
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
                'user_type' => 'pharmacy',
                'status' => 'pending',
                'email_verified_at' => now(),
            ]);
            $profileImagePath = $request->file('profile_image')
                ? $request->file('profile_image')->store('pharmacies/profiles', 'public')
                : null;
            $docPath = $request->file('document')
                ? $request->file('document')->store('pharmacies/documents', 'public')
                : null;
            Pharmacy::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'registration_number' => $validated['registration_number'],
                'pharmacist_name' => $validated['pharmacist_name'],
                'pharmacist_license' => $validated['pharmacist_license'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $request->address,
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postal_code' => $request->postal_code,
                'operating_hours' => $request->operating_hours,
                'delivery_available' => $request->has('delivery_available'),
                'profile_image' => $profileImagePath,
                'document_path' => $docPath,
                'status' => 'pending',
            ]);
        });
        return redirect()->route('admin.pharmacies.index')->with('success', 'Pharmacy created successfully!');
    }

    public function show($id)
    {
        $pharmacy = Pharmacy::with(['user', 'approvedBy'])->findOrFail($id);
        return view('admin.pharmacies.show', compact('pharmacy'));
    }

    public function edit($id)
    {
        $pharmacy = Pharmacy::with('user')->findOrFail($id);
        return view('admin.pharmacies.edit', compact('pharmacy'));
    }

    public function update(Request $request, $id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $pharmacy->user_id,
            'name' => 'required|max:255',
            'registration_number' => 'required|unique:pharmacies,registration_number,' . $id,
            'pharmacist_name' => 'required|max:100',
            'pharmacist_license' => 'required|max:100',
            'phone' => 'required|max:20',
            'city' => 'required|max:100',
            'province' => 'required|max:100',
            'profile_image' => 'nullable|image|max:5120',
            'document' => 'nullable|file|mimes:pdf,jpeg,png|max:5120',
        ]);
        DB::transaction(function() use ($request, $pharmacy, $validated) {
            $pharmacy->user->update(['email' => $validated['email']]);
            $profileImagePath = $pharmacy->profile_image;
            if ($request->hasFile('profile_image')) {
                if ($profileImagePath) Storage::disk('public')->delete($profileImagePath);
                $profileImagePath = $request->file('profile_image')->store('pharmacies/profiles', 'public');
            }
            $docPath = $pharmacy->document_path;
            if ($request->hasFile('document')) {
                if ($docPath) Storage::disk('public')->delete($docPath);
                $docPath = $request->file('document')->store('pharmacies/documents', 'public');
            }
            $pharmacy->update([
                'name' => $validated['name'],
                'registration_number' => $validated['registration_number'],
                'pharmacist_name' => $validated['pharmacist_name'],
                'pharmacist_license' => $validated['pharmacist_license'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $request->address,
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postal_code' => $request->postal_code,
                'operating_hours' => $request->operating_hours,
                'delivery_available' => $request->has('delivery_available'),
                'profile_image' => $profileImagePath,
                'document_path' => $docPath,
            ]);
        });
        return redirect()->route('admin.pharmacies.index')->with('success', 'Pharmacy updated successfully!');
    }

    public function destroy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        if ($pharmacy->profile_image) Storage::disk('public')->delete($pharmacy->profile_image);
        if ($pharmacy->document_path) Storage::disk('public')->delete($pharmacy->document_path);
        $pharmacy->user()->delete();
        $pharmacy->delete();
        return redirect()->route('admin.pharmacies.index')->with('success', 'Pharmacy deleted successfully!');
    }

    public function approve($id)
    {
        try {
            $pharmacy = Pharmacy::findOrFail($id);
            if ($pharmacy->status != 'pending') {
                return response()->json(['success' => false, 'message' => 'Pharmacy is not pending'], 400);
            }
            $pharmacy->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);
            $pharmacy->user->update(['status' => 'active']);

            // Email/notification send
            try {
                $pharmacy->user->notify(new ProviderStatusChangedNotification('approved', 'Pharmacy'));
            } catch (\Exception $e) {
                // Optional: log error, don't block approval
            }

            return response()->json(['success' => true, 'message' => 'Pharmacy approved!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function reject($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->update(['status' => 'rejected']);
        $pharmacy->user()->update(['status' => 'rejected']);
        return response()->json(['success' => true, 'message' => 'Pharmacy rejected.']);
    }

    public function suspend($id)
    {
        try {
            $pharmacy = Pharmacy::findOrFail($id);
            $pharmacy->update(['status' => 'suspended']);
            $pharmacy->user->update(['status' => 'suspended']);

            // Email/notification send
            try {
                $pharmacy->user->notify(new ProviderStatusChangedNotification('suspended', 'Pharmacy'));
            } catch (\Exception $e) {
                // Optional: log error, don't block suspend
            }

            return response()->json(['success' => true, 'message' => 'Pharmacy suspended.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    // public function activate($id)
    // {
    //     $pharmacy = Pharmacy::findOrFail($id);
    //     $pharmacy->update(['status' => 'approved']);
    //     $pharmacy->user()->update(['status' => 'active']);
    //     return response()->json(['success' => true, 'message' => 'Pharmacy activated!']);
    // }

    public function activate($id)
    {
        try {
            $pharmacy = Pharmacy::findOrFail($id);
            $pharmacy->update(['status' => 'approved']);
            $pharmacy->user->update(['status' => 'active']);

            // Email/notification
            try {
                $pharmacy->user->notify(
                    new ProviderStatusChangedNotification('active', 'Pharmacy')
                );
            } catch (\Exception $e) {
                \Log::error('Pharmacy activate notification error: ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Pharmacy activated!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}
