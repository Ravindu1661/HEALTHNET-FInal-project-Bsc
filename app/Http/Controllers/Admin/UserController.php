<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Laboratory;
use App\Models\Pharmacy;
use App\Models\MedicalCentre;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Notifications\ProviderStatusChangedNotification;
use App\Notifications\PatientStatusChangedNotification;

class UserController extends Controller
{
    /**
     * Display a listing of all users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'LIKE', "%{$search}%")
                  ->orWhere('user_type', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        // Filter by user type
        if ($request->has('user_type') && $request->user_type != '') {
            $query->where('user_type', $request->user_type);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Order by latest first
        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:8|confirmed',
            'user_type' => 'required|in:patient,doctor,hospital,laboratory,pharmacy,medical_centre,admin',
            'status' => 'required|in:pending,active,suspended,rejected',
            'first_name' => 'required_if:user_type,patient,doctor|string|max:100',
            'last_name' => 'required_if:user_type,patient,doctor|string|max:100',
        ]);

        try {
            DB::transaction(function() use ($request) {
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'user_type' => $request->user_type,
                    'status' => $request->status,
                    'email_verified_at' => now(),
                ]);

                // Create related profile based on user type
                if ($request->user_type == 'patient') {
                    Patient::create([
                        'user_id' => $user->id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'phone' => $request->phone ?? null,
                        'gender' => $request->gender ?? null,
                        'date_of_birth' => $request->date_of_birth ?? null,
                    ]);
                }
            });

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            \Log::error('User creation error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Get related profile data based on user type
        $profile = null;
        switch($user->user_type) {
            case 'doctor':
                $profile = Doctor::where('user_id', $user->id)->first();
                break;
            case 'hospital':
                $profile = Hospital::where('user_id', $user->id)->first();
                break;
            case 'laboratory':
                $profile = Laboratory::where('user_id', $user->id)->first();
                break;
            case 'pharmacy':
                $profile = Pharmacy::where('user_id', $user->id)->first();
                break;
            case 'medical_centre':
                $profile = MedicalCentre::where('user_id', $user->id)->first();
                break;
            case 'patient':
                $profile = Patient::where('user_id', $user->id)->first();
                break;
        }

        return view('admin.users.show', compact('user', 'profile'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Get related profile
        $profile = null;
        switch($user->user_type) {
            case 'doctor':
                $profile = Doctor::where('user_id', $user->id)->first();
                break;
            case 'hospital':
                $profile = Hospital::where('user_id', $user->id)->first();
                break;
            case 'laboratory':
                $profile = Laboratory::where('user_id', $user->id)->first();
                break;
            case 'pharmacy':
                $profile = Pharmacy::where('user_id', $user->id)->first();
                break;
            case 'medical_centre':
                $profile = MedicalCentre::where('user_id', $user->id)->first();
                break;
            case 'patient':
                $profile = Patient::where('user_id', $user->id)->first();
                break;
        }

        return view('admin.users.edit', compact('user', 'profile'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id . '|max:255',
            'user_type' => 'required|in:patient,doctor,hospital,laboratory,pharmacy,medical_centre,admin',
            'status' => 'required|in:pending,active,suspended,rejected',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            DB::transaction(function() use ($request, $user) {
                $userData = [
                    'email' => $request->email,
                    'user_type' => $request->user_type,
                    'status' => $request->status,
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $user->update($userData);

                // Update profile if exists
                if ($user->user_type == 'patient' && $request->has('first_name')) {
                    $patient = Patient::where('user_id', $user->id)->first();
                    if ($patient) {
                        $patient->update([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'phone' => $request->phone,
                        ]);
                    }
                }
            });

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            \Log::error('User update error: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting own account
            if ($user->id == auth()->id()) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'You cannot delete your own account!');
            }

            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('User deletion error: ' . $e->getMessage());
            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Suspend user account
     */
    public function suspend($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->id == auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot suspend your own account!'
                ], 403);
            }

            $user->update(['status' => 'suspended']);

            // Provider-types: send provider notification
            if (in_array($user->user_type, ['doctor','hospital','laboratory','pharmacy','medical_centre'])) {
                try {
                    $user->notify(new ProviderStatusChangedNotification('suspended', ucfirst(str_replace('_', ' ', $user->user_type))));
                } catch (\Exception $e) {
                    \Log::error('Provider suspension notification error: ' . $e->getMessage());
                }
            }

            // Patients: send patient notification
            if ($user->user_type === 'patient') {
                try {
                    $user->notify(new PatientStatusChangedNotification('suspended'));
                } catch (\Exception $e) {
                    \Log::error('Patient suspension notification error: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'User suspended successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('User suspension error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to suspend user!'
            ], 500);
        }
    }


    /**
     * Activate user account
     */
   public function activate($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->update(['status' => 'active']);

            // Provider-types: send provider notification
            if (in_array($user->user_type, ['doctor','hospital','laboratory','pharmacy','medical_centre'])) {
                try {
                    $user->notify(new ProviderStatusChangedNotification('active', ucfirst(str_replace('_', ' ', $user->user_type))));
                } catch (\Exception $e) {
                    \Log::error('Provider activation notification error: ' . $e->getMessage());
                }
            }

            // Patients: send patient notification
            if ($user->user_type === 'patient') {
                try {
                    $user->notify(new PatientStatusChangedNotification('active'));
                } catch (\Exception $e) {
                    \Log::error('Patient activation notification error: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'User activated successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('User activation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate user!'
            ], 500);
        }
    }

}
