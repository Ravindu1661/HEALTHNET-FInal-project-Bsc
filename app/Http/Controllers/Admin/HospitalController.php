<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ProviderStatusChangedNotification;
class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $query = Hospital::with('user');
         $query->select('hospitals.*');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name','like',"%$search%")
                  ->orWhere('registration_number','like',"%$search%")
                  ->orWhere('city','like',"%$search%");
            });
        }
        if ($request->filled('type')) $query->where('type', $request->type);
        if ($request->filled('status')) $query->where('status', $request->status);
        $hospitals = $query->orderBy('created_at','desc')->paginate(15);
        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function create() { return view('admin.hospitals.create'); }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'name' => 'required|string|max:255',
            'type' => 'required|in:government,private',
            'registration_number' => 'required|unique:hospitals,registration_number',
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
                'usertype' => 'hospital',
                'status' => 'pending',
                'emailverifiedat'=>now(),
            ]);
            $profileImagePath = $request->file('profile_image')
                ? $request->file('profile_image')->store('hospitals/profiles', 'public')
                : null;
            $docPath = $request->file('document')
                ? $request->file('document')->store('hospitals/documents', 'public')
                : null;
            Hospital::create([
                'userid' => $user->id,
                'name' => $validated['name'],
                'type' => $validated['type'],
                'registrationnumber' => $validated['registration_number'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $request->address,
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postalcode' => $request->postal_code,
                'specializations' => $request->specializations ? json_encode(array_map('trim', explode(',', $request->specializations))) : null,
                'facilities' => $request->facilities ? json_encode(array_map('trim', explode(',', $request->facilities))) : null,
                'operatinghours' => $request->operating_hours,
                'description' => $request->description,
                'website' => $request->website,
                'profileimage' => $profileImagePath,
                'documentpath' => $docPath,
                'status' => 'pending',
            ]);
        });
        return redirect()->route('admin.hospitals.index')->with('success','Hospital created successfully!');
    }

    public function show($id)
    {
        $hospital = Hospital::with(['user','approvedBy'])->findOrFail($id);
        return view('admin.hospitals.show', compact('hospital'));
    }

    public function edit($id)
    {
        $hospital = Hospital::with('user')->findOrFail($id);
        return view('admin.hospitals.edit', compact('hospital'));
    }

    public function update(Request $request, $id)
    {
        $hospital = Hospital::findOrFail($id);
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $hospital->userid,
            'name' => 'required|max:255',
            'type' => 'required|in:government,private',
            'registration_number' => 'required|unique:hospitals,registration_number,' . $id,
            'phone' => 'required|max:20',
            'city' => 'required|max:100',
            'province' => 'required|max:100',
            'profile_image' => 'nullable|image|max:5120',
            'document' => 'nullable|file|mimes:pdf,jpeg,png|max:5120',
        ]);
        DB::transaction(function() use ($request, $hospital, $validated) {
            $hospital->user->update(['email'=>$validated['email']]);
            $profileImagePath = $request->file('profile_image')
                ? $request->file('profile_image')->store('hospitals/profiles','public')
                : $hospital->profileimage;
            $docPath = $request->file('document')
                ? $request->file('document')->store('hospitals/documents','public')
                : $hospital->documentpath;
            $hospital->update([
                'name'=>$validated['name'],
                'type'=>$validated['type'],
                'registrationnumber'=>$validated['registration_number'],
                'phone'=>$validated['phone'],
                'email'=>$validated['email'],
                'address'=>$request->address,
                'city'=>$validated['city'],
                'province'=>$validated['province'],
                'postalcode'=>$request->postal_code,
                'specializations'=>$request->specializations
                    ? json_encode(array_map('trim',explode(',',$request->specializations))) : null,
                'facilities'=>$request->facilities
                    ? json_encode(array_map('trim',explode(',',$request->facilities))) : null,
                'operatinghours'=>$request->operating_hours,
                'description'=>$request->description,
                'website'=>$request->website,
                'profileimage'=>$profileImagePath,
                'documentpath'=>$docPath,
            ]);
        });
        return redirect()->route('admin.hospitals.index')->with('success','Hospital updated successfully!');
    }

    public function destroy($id)
    {
        $hospital = Hospital::findOrFail($id);
        if ($hospital->profileimage) Storage::disk('public')->delete($hospital->profileimage);
        if ($hospital->documentpath) Storage::disk('public')->delete($hospital->documentpath);
        $hospital->user->delete();
        return redirect()->route('admin.hospitals.index')->with('success','Hospital deleted successfully!');
    }

    //--- Approval workflow ---
   public function approve($id)
{
    try {
        $hospital = Hospital::findOrFail($id);
        if ($hospital->status != 'pending') {
            return response()->json(['success' => false, 'message' => 'Hospital is not pending'], 400);
        }

        $hospital->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        $hospital->user->update(['status' => 'active']);

        // Notification in try-catch (don't fail request if notification error)
        try {
            $hospital->user->notify(
                new ProviderStatusChangedNotification('approved', 'Hospital')
            );
        } catch (\Exception $e) {
            // Log error but don't stop approve
        }

        return response()->json(['success' => true, 'message' => 'Hospital approved!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function reject($id)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->update(['status'=>'rejected']);
        $hospital->user()->update(['status'=>'rejected']);
        return response()->json(['success'=>true,'message'=>'Hospital rejected.']);
    }
    public function suspend($id)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->update(['status' => 'suspended']);
        $hospital->user()->update(['status' => 'suspended']);
        $hospital->user->notify(
            new ProviderStatusChangedNotification('suspended', 'Hospital')
        );
        return response()->json(['success' => true, 'message' => 'Hospital suspended.']);
    }
    // public function activate($id)
    // {
    //     $hospital = Hospital::findOrFail($id);
    //     $hospital->update(['status'=>'approved']);
    //     $hospital->user()->update(['status'=>'active']);
    //     return response()->json(['success'=>true,'message'=>'Hospital activated!']);
    // }

     public function activate($id)
    {
        try {
            $hospital = Hospital::findOrFail($id);
            $hospital->update(['status' => 'approved']);
            $hospital->user->update(['status' => 'active']);

            // Email/notification
            try {
                $hospital->user->notify(
                    new ProviderStatusChangedNotification('active', 'Hospital')
                );
            } catch (\Exception $e) {
                \Log::error('Hospital activate notification error: ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Hospital activated!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
