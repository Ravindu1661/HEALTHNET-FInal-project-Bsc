<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PatientStatusChangedNotification;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('user');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%$s%")
                  ->orWhere('last_name', 'like', "%$s%")
                  ->orWhere('nic', 'like', "%$s%")
                  ->orWhere('phone', 'like', "%$s%");
            });
        }

        // If status is stored in 'user', filter through relation
        if ($request->filled('status')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
            return view('admin.patients.create');
    }

    public function store(Request $request    )
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'nullable|string|max:100',
            'nic' => 'required|unique:patients,nic',
            'phone' => 'required|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|max:5120',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_type' => 'patient',
                'status' => 'pending',
                'email_verified_at' => now(),
            ]);

            $imagePath = $request->hasFile('profile_image')
                            ? $request->file('profile_image')->store('patients/profiles', 'public')
                : null;

            Patient::create([
                'user_id'    => $user->id,
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],
                'nic'        => $validated['nic'],
                'phone'      => $validated['phone'],
                'address'    => $request->address,
                'city'       => $request->city,
                'province'   => $request->province,
                'profile_image' => $imagePath,
            ]);
        });

        return redirect()->route('admin.patients.index')->with('success', 'Patient         created successfully!');
    }

    public function show($id)
    {
        $patient = Patient::with('user')->findOrFail($id);
        return view('admin.patients.show', compact('patient'));
    }

    public function edit($id)
    {
        $patient = Patient::with('user')->findOrFail($id    );
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id    );

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $patient->user_id,
            'first_name' => 'required|string|max:100',
            'last_name'  => 'nullable|string|max:100',
            'nic' => 'required|unique:patients,nic,' . $id,
            'phone' => 'required|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|max:5120',
        ]);

        DB::transaction(function() use ($request, $patient, $validated) {
            $patient->user->update(['email' => $validated['email']]);
            if ($request->filled('password')) {
                $patient->user->update(['password' => Hash::make($request->password)]);
            }

            $imagePath = $patient->profile_image;
            if ($request->hasFile('profile_image')) {
                            if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('profile_image')->store('patients/profiles', 'public');
            }

            $patient->update([
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],
                'nic'        => $validated['nic'],
                'phone'      => $validated['phone'],
                'address'    => $request->address,
                'city'       => $request->city,
                'province'   => $request->province,
                'profile_image' => $imagePath,
            ]);
        });

        return redirect()->route('admin.patients.index')->with('success', 'Patient updated successfully!');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        if ($patient->profile_image) {
            Storage::disk('public')->delete($patient->profile_image);
        }
        $patient->user()->delete();
        $patient->delete();

        return redirect()->route('admin.patients.index')->with('success', 'Patient deleted successfully!');
    }

    // Approval logic: update status         in user table (no status in patient table!)
    public function approve($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->user()->update(['status' => 'active']);
        return response()->json(['success' => true, 'message' => 'Patient approved!']);
    }

    public function reject($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->user()->update(['status' => 'rejected']);
        return response()->json(['success' => true, 'message' => 'Patient rejected.']);
    }

    // public function suspend($id)
    // {
    //     $patient = Patient::findOrFail($id);
    //     $patient->user()->update(['status' => 'suspended']);
    //     return response()->json(['success' => true, 'message' => 'Patient suspended.']);
    // }

    // public function activate($id)
    // {
    //     $patient = Patient::findOrFail($id);
    //     $patient->user()->update(['status' => 'active']);
    //     return response()->json(['success' => true, 'message' => 'Patient activated!']);
    // }


    public function suspend($id)
{
    try {
        $patient = Patient::findOrFail($id);
        $patient->user()->update(['status' => 'suspended']);

        // Send notification/email
        try {
            $patient->user->notify(new PatientStatusChangedNotification('suspended'));
        } catch (\Exception $e) {
            \Log::error('Patient suspend notification error: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Patient suspended.']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function activate($id)
{
    try {
        $patient = Patient::findOrFail($id);
        $patient->user()->update(['status' => 'active']);

        // Send notification/email
        try {
            $patient->user->notify(new PatientStatusChangedNotification('active'));
        } catch (\Exception $e) {
            \Log::error('Patient activate notification error: ' . $e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Patient activated!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

}
