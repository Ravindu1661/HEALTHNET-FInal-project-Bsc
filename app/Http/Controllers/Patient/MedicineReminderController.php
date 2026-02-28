<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicineReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MedicineReminderController extends Controller
{
    // ══════════════════════════════════════════════════════
    //  INDEX
    // ══════════════════════════════════════════════════════
    public function index()
    {
        $patient = Auth::user()->patient;

        $active = MedicineReminder::where('patient_id', $patient->id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', today());
            })
            ->orderBy('medicine_name')
            ->get();

        $inactive = MedicineReminder::where('patient_id', $patient->id)
            ->where(function ($q) {
                $q->where('is_active', false)
                  ->orWhere('end_date', '<', today());
            })
            ->orderByDesc('updated_at')
            ->get();

        $totalActive   = $active->count();
        $totalInactive = $inactive->count();
        $totalAll      = $totalActive + $totalInactive;

        $today      = Carbon::now('Asia/Colombo')->toDateString();
        $todayCount = MedicineReminder::where('patient_id', $patient->id)
            ->where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $today);
            })
            ->count();

        $nowTime = Carbon::now('Asia/Colombo')->format('H:i');

        return view('patient.medicine-reminders', compact(
            'active', 'inactive',
            'totalActive', 'totalInactive', 'totalAll',
            'todayCount', 'nowTime'
        ));
    }

    // ══════════════════════════════════════════════════════
    //  STORE — notification නෑ
    // ══════════════════════════════════════════════════════
    public function store(Request $request)
    {
        $data = $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage'        => 'nullable|string|max:100',
            'frequency'     => 'required|in:once_daily,twice_daily,thrice_daily,four_times_daily,custom',
            'times'         => 'required|array|min:1',
            'times.*'       => 'required|date_format:H:i',
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'notes'         => 'nullable|string|max:500',
        ]);

        $data['patient_id'] = Auth::user()->patient->id;
        $data['is_active']  = true;

        MedicineReminder::create($data);

        return back()->with('success', "Reminder for '{$data['medicine_name']}' has been set!");
    }

    // ══════════════════════════════════════════════════════
    //  UPDATE — notification නෑ
    // ══════════════════════════════════════════════════════
    public function update(Request $request, MedicineReminder $reminder)
    {
        $this->gate($reminder);

        $data = $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage'        => 'nullable|string|max:100',
            'frequency'     => 'required|in:once_daily,twice_daily,thrice_daily,four_times_daily,custom',
            'times'         => 'required|array|min:1',
            'times.*'       => 'required|date_format:H:i',
            'start_date'    => 'required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'notes'         => 'nullable|string|max:500',
        ]);

        $reminder->update($data);

        return back()->with('success', "Reminder for '{$reminder->medicine_name}' updated!");
    }

    // ══════════════════════════════════════════════════════
    //  TOGGLE — notification නෑ
    // ══════════════════════════════════════════════════════
    public function toggle(MedicineReminder $reminder)
    {
        $this->gate($reminder);

        $reminder->update(['is_active' => !$reminder->is_active]);

        $status = $reminder->is_active ? 'activated' : 'paused';

        return back()->with('success', "Reminder {$status} successfully!");
    }

    // ══════════════════════════════════════════════════════
    //  DESTROY — notification නෑ
    // ══════════════════════════════════════════════════════
    public function destroy(MedicineReminder $reminder)
    {
        $this->gate($reminder);
        $name = $reminder->medicine_name;
        $reminder->delete();

        return back()->with('success', "Reminder for '{$name}' deleted.");
    }

    // ══════════════════════════════════════════════════════
    //  PRIVATE HELPERS
    // ══════════════════════════════════════════════════════
    private function gate(MedicineReminder $reminder): void
    {
        if ($reminder->patient_id !== Auth::user()->patient->id) {
            abort(403);
        }
    }
}
