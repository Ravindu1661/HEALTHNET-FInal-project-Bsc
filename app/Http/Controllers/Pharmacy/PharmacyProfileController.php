<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Pharmacy;

class PharmacyProfileController extends Controller
{
    /**
     * Show pharmacy profile
     */
    public function index()
    {
        $pharmacy = Auth::user()->pharmacy;

        if (!$pharmacy) {
            return redirect()->route('pharmacy.profile.create')
                ->with('error', 'Please complete your pharmacy profile first.');
        }

        return view('pharmacy.profile.index', compact('pharmacy'));
    }

    /**
     * Show create profile form
     */
    public function create()
    {
        $pharmacy = Auth::user()->pharmacy;

        if ($pharmacy) {
            return redirect()->route('pharmacy.profile')
                ->with('info', 'You already have a pharmacy profile.');
        }

        return view('pharmacy.profile.create');
    }

    /**
     * Store pharmacy profile
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:pharmacies,registration_number',
            'pharmacist_name' => 'required|string|max:100',
            'pharmacist_license' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'operating_hours' => 'nullable|string',
            'delivery_available' => 'boolean',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'document_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $validatedData['profile_image'] = $request->file('profile_image')
                ->store('pharmacies/profiles', 'public');
        }

        // Handle document upload
        if ($request->hasFile('document_path')) {
            $validatedData['document_path'] = $request->file('document_path')
                ->store('pharmacies/documents', 'public');
        }

        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'pending';

        Pharmacy::create($validatedData);

        return redirect()->route('pharmacy.dashboard')
            ->with('success', 'Pharmacy profile created successfully. Awaiting admin approval.');
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $pharmacy = Auth::user()->pharmacy;

        if (!$pharmacy) {
            return redirect()->route('pharmacy.profile.create');
        }

        return view('pharmacy.profile.edit', compact('pharmacy'));
    }

    /**
     * Update pharmacy profile
     */
    public function update(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        if (!$pharmacy) {
            return redirect()->route('pharmacy.profile.create')
                ->with('error', 'Please create your pharmacy profile first.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'pharmacist_name' => 'required|string|max:100',
            'pharmacist_license' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'operating_hours' => 'nullable|string',
            'delivery_available' => 'boolean',
        ]);

        $pharmacy->update($validatedData);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Upload profile image
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pharmacy = Auth::user()->pharmacy;

        if ($request->hasFile('profile_image')) {
            // Delete old image
            if ($pharmacy->profile_image) {
                Storage::disk('public')->delete($pharmacy->profile_image);
            }

            // Store new image
            $path = $request->file('profile_image')->store('pharmacies/profiles', 'public');
            $pharmacy->update(['profile_image' => $path]);
        }

        return back()->with('success', 'Profile image updated successfully.');
    }

    /**
     * Delete profile image
     */
    public function deleteImage()
    {
        $pharmacy = Auth::user()->pharmacy;

        if ($pharmacy->profile_image) {
            Storage::disk('public')->delete($pharmacy->profile_image);
            $pharmacy->update(['profile_image' => null]);
        }

        return back()->with('success', 'Profile image deleted successfully.');
    }
}
