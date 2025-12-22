<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use App\Models\DoctorWorkplace; // ✅ NEW
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ProviderStatusChangedNotification;

class DoctorController extends Controller
{
    /**
     * Display a listing of doctors
     */
    public function index(Request $request)
    {
        $query = Doctor::with('user');

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('slmc_number', 'LIKE', "%{$search}%")
                  ->orWhere('specialization', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by specialization
        if ($request->has('specialization') && $request->specialization != '') {
            $query->where('specialization', 'LIKE', "%{$request->specialization}%");
        }

        $doctors = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new doctor
     */
    public function create()
    {
        return view('admin.doctors.create');
    }

    /**
     * Store a newly created doctor
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'slmc_number' => 'required|unique:doctors,slmc_number',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'consultation_fee' => 'nullable|numeric|min:0',
            'experience_years' => 'nullable|integer|min:0',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::transaction(function() use ($request) {
                // Create user account
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'user_type' => 'doctor',
                    'status' => $request->status ?? 'pending',
                    'email_verified_at' => now(),
                ]);

                // Handle document upload
                $documentPath = null;
                if ($request->hasFile('document')) {
                    $documentPath = $request->file('document')->store('doctors/documents', 'public');
                }

                // Create doctor profile
                Doctor::create([
                    'user_id' => $user->id,
                    'status' => $request->status ?? 'pending',
                    'slmc_number' => $request->slmc_number,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'specialization' => $request->specialization,
                    'qualifications' => $request->qualifications,
                    'experience_years' => $request->experience_years,
                    'phone' => $request->phone,
                    'bio' => $request->bio,
                    'consultation_fee' => $request->consultation_fee,
                    'document_path' => $documentPath,
                ]);
            });

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor created successfully!');
        } catch (\Exception $e) {
            \Log::error('Doctor creation error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create doctor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified doctor
     */
    public function show($id)
    {
        $doctor = Doctor::with(['user', 'approvedBy'])
            ->withCount(['appointments', 'workplaces'])
            ->findOrFail($id);

        // ✅ NEW: Get doctor's workplaces with relationships
        $workplaces = DoctorWorkplace::where('doctor_id', $id)
            ->with(['hospital', 'medicalCentre', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.doctors.show', compact('doctor', 'workplaces'));
    }

    /**
     * Show the form for editing the specified doctor
     */
    public function edit($id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);
        return view('admin.doctors.edit', compact('doctor'));
    }

    /**
     * Update the specified doctor
     */
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $doctor->user_id,
            'slmc_number' => 'required|unique:doctors,slmc_number,' . $id,
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'consultation_fee' => 'nullable|numeric|min:0',
            'experience_years' => 'nullable|integer|min:0',
            'password' => 'nullable|min:8|confirmed',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::transaction(function() use ($request, $doctor) {
                // Update user account
                $userData = [
                    'email' => $request->email,
                    'status' => $request->status ?? $doctor->user->status,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $doctor->user->update($userData);

                // Handle document upload
                if ($request->hasFile('document')) {
                    // Delete old document
                    if ($doctor->document_path) {
                        Storage::disk('public')->delete($doctor->document_path);
                    }
                    $documentPath = $request->file('document')->store('doctors/documents', 'public');
                } else {
                    $documentPath = $doctor->document_path;
                }

                // Update doctor profile
                $doctor->update([
                    'status' => $request->status ?? $doctor->status,
                    'slmc_number' => $request->slmc_number,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'specialization' => $request->specialization,
                    'qualifications' => $request->qualifications,
                    'experience_years' => $request->experience_years,
                    'phone' => $request->phone,
                    'bio' => $request->bio,
                    'consultation_fee' => $request->consultation_fee,
                    'document_path' => $documentPath,
                ]);
            });

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Doctor update error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to update doctor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified doctor
     */
    public function destroy($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);

            // Delete document if exists
            if ($doctor->document_path) {
                Storage::disk('public')->delete($doctor->document_path);
            }

            // Delete user account (will cascade delete doctor via foreign key)
            $doctor->user->delete();

            return redirect()->route('admin.doctors.index')
                ->with('success', 'Doctor deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Doctor deletion error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete doctor: ' . $e->getMessage());
        }
    }

    /**
     * Approve doctor
     */
    public function approve($id)
    {
        $doctor = Doctor::findOrFail($id);

        $doctor->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        $doctor->user->update(['status' => 'active']);

        // ✅ Email notification
        $doctor->user->notify(new ProviderStatusChangedNotification('approved', 'Doctor'));

        return response()->json([
            'success' => true,
            'message' => 'Doctor approved successfully!'
        ]);
    }

    /**
     * Reject doctor
     */
    public function reject($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $doctor->update([
                'status' => 'rejected',
            ]);

            // Also update user status
            $doctor->user->update(['status' => 'rejected']);

            return response()->json([
                'success' => true,
                'message' => 'Doctor rejected!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject doctor!'
            ], 500);
        }
    }

    /**
     * Suspend doctor
     */
    public function suspend($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->update(['status' => 'suspended']);
        $doctor->user->update(['status' => 'suspended']);

        // ✅ Email notification
        $doctor->user->notify(new ProviderStatusChangedNotification('suspended', 'Doctor'));

        return response()->json([
            'success' => true,
            'message' => 'Doctor suspended successfully!'
        ]);
    }

    /**
     * Activate doctor
     */
    public function activate($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $doctor->update(['status' => 'approved']);
            $doctor->user->update(['status' => 'active']);

            // Email/notification
            try {
                $doctor->user->notify(
                    new ProviderStatusChangedNotification('active', 'Doctor')
                );
            } catch (\Exception $e) {
                \Log::error('Doctor activate notification error: ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Doctor activated!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ========================================
    // ✅ NEW WORKPLACE MANAGEMENT METHODS
    // ========================================

    /**
     * Approve doctor workplace
     */
    public function approveWorkplace($workplaceId)
    {
        try {
            $workplace = DoctorWorkplace::findOrFail($workplaceId);

            $workplace->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Workplace approved successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Workplace approval error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve workplace!'
            ], 500);
        }
    }

    /**
     * Reject doctor workplace
     */
    public function rejectWorkplace($workplaceId)
    {
        try {
            $workplace = DoctorWorkplace::findOrFail($workplaceId);

            $workplace->update([
                'status' => 'rejected',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Workplace rejected!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Workplace rejection error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject workplace!'
            ], 500);
        }
    }

    /**
     * Delete doctor workplace
     */
    public function deleteWorkplace($workplaceId)
    {
        try {
            $workplace = DoctorWorkplace::findOrFail($workplaceId);
            $workplace->delete();

            return response()->json([
                'success' => true,
                'message' => 'Workplace deleted successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Workplace deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete workplace!'
            ], 500);
        }
    }
}
