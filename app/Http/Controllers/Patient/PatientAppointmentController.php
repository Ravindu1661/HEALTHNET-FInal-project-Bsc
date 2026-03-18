<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\DoctorWorkplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PatientAppointmentController extends Controller
{
    // ═══════════════════════════════════════════
    // CREATE — Appointment Form Page
    // ═══════════════════════════════════════════
    public function create(Request $request)
    {
        $doctorId = $request->get('doctor_id');

        if (!$doctorId) {
            return redirect()->route('patient.doctors')
                ->with('error', 'Please select a doctor first.');
        }

        $doctor = Doctor::with([
            'workplaces' => function ($q) {
                $q->where('status', 'approved')
                  ->with(['hospital', 'medicalCentre']);
            }
        ])->where('status', 'approved')->findOrFail($doctorId);

        $workplaces = $doctor->workplaces;

        return view('patient.create-appointment', compact('doctor', 'workplaces'));
    }
public function getScheduleDays(Request $request)
    {
        $doctorId    = $request->integer('doctor_id');
        $wpId        = $request->integer('workplace_id');  // doctor_workplaces.id

        if (!$doctorId || !$wpId) {
            return response()->json(['success' => false, 'message' => 'Invalid parameters.']);
        }

        // doctor_workplaces row ලබා ගන්නවා — workplace_type + workplace_id දෙකම ලබා ගන්නවා
        $workplace = DB::table('doctor_workplaces')
            ->where('id', $wpId)
            ->where('doctor_id', $doctorId)
            ->first();

        if (!$workplace) {
            return response()->json(['success' => false, 'message' => 'Workplace not found.']);
        }

        // doctor_schedules table query — doctor_id + workplace_type + workplace_id match
        $schedules = DB::table('doctor_schedules')
            ->where('doctor_id', $doctorId)
            ->where('workplace_type', $workplace->workplace_type)
            ->where('workplace_id', $workplace->workplace_id)
            ->where('is_active', 1)
            ->select('day_of_week', 'start_time', 'end_time', 'max_appointments', 'consultation_fee')
            ->orderBy('day_of_week')
            ->get();

        if ($schedules->isEmpty()) {
            return response()->json(['success' => false, 'days' => [], 'message' => 'No active schedule found.']);
        }

        // Unique days
        $days = $schedules->pluck('day_of_week')->unique()->values()->toArray();

        // Schedule info text (e.g. "Mon, Wed, Fri • 09:00 AM – 05:00 PM")
        $dayLabels = array_map(fn($d) => ucfirst(substr($d, 0, 3)), $days);
        $firstSch  = $schedules->first();
        $startFmt  = Carbon::createFromTimeString($firstSch->start_time)->format('h:i A');
        $endFmt    = Carbon::createFromTimeString($firstSch->end_time)->format('h:i A');

        $scheduleInfo = implode(', ', $dayLabels) . ' • ' . $startFmt . ' – ' . $endFmt
            . ' • Max ' . $firstSch->max_appointments . ' appointments/session';

        return response()->json([
            'success'       => true,
            'days'          => $days,
            'schedules'     => $schedules,
            'schedule_info' => $scheduleInfo,
        ]);
    }

    // ═══════════════════════════════════════════════════════════
    // AJAX — Get Available Time Slots for a given date
    // GET /patient/appointments/available-slots
    //     ?doctor_id=X&workplace_id=Y&date=YYYY-MM-DD
    // ═══════════════════════════════════════════════════════════
    public function getAvailableSlots(Request $request)
    {
        $doctorId = $request->integer('doctor_id');
        $wpId     = $request->integer('workplace_id');  // doctor_workplaces.id
        $date     = $request->get('date');              // YYYY-MM-DD

        if (!$doctorId || !$wpId || !$date) {
            return response()->json(['success' => false, 'message' => 'Invalid parameters.']);
        }

        // Validate date format
        try {
            $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid date format.']);
        }

        $dayOfWeek = strtolower($carbonDate->format('l')); // e.g. 'monday'

        // doctor_workplaces row
        $workplace = DB::table('doctor_workplaces')
            ->where('id', $wpId)
            ->where('doctor_id', $doctorId)
            ->first();

        if (!$workplace) {
            return response()->json(['success' => false, 'message' => 'Workplace not found.']);
        }

        // Doctor schedule for this day + location
        $schedule = DB::table('doctor_schedules')
            ->where('doctor_id', $doctorId)
            ->where('workplace_type', $workplace->workplace_type)
            ->where('workplace_id', $workplace->workplace_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', 1)
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'slots'   => [],
                'message' => 'No schedule found for this day.',
            ]);
        }

        // Already-booked appointments for this doctor on this date at this workplace
        $bookedTimes = DB::table('appointments')
            ->where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->where('workplace_type', $workplace->workplace_type)
            ->where('workplace_id', $workplace->workplace_id)
            ->whereNotIn('status', ['cancelled'])
            ->pluck('appointment_time')
            ->map(fn($t) => Carbon::createFromTimeString($t)->format('H:i'))
            ->toArray();

        // Generate 30-minute slots between start_time and end_time
        $slots     = [];
        $slotStep  = 30; // minutes per slot
        $current   = Carbon::createFromTimeString($schedule->start_time);
        $endTime   = Carbon::createFromTimeString($schedule->end_time);
        $maxApt    = $schedule->max_appointments;
        $slotCount = 0;

        while ($current < $endTime && $slotCount < $maxApt) {
            $timeStr = $current->format('H:i'); // 24h for DB
            $label   = $current->format('h:i A'); // 12h for display

            // Count how many booked for this exact slot
            $bookedCount = count(array_filter($bookedTimes, fn($bt) => $bt === $timeStr));

            $slots[] = [
                'time'        => $timeStr,
                'label'       => $label,
                'booked'      => $bookedCount > 0,
            ];

            $current->addMinutes($slotStep);
            $slotCount++;
        }

        if (empty($slots)) {
            return response()->json(['success' => false, 'slots' => [], 'message' => 'No slots available.']);
        }

        return response()->json([
            'success'  => true,
            'slots'    => $slots,
            'schedule' => [
                'start'           => Carbon::createFromTimeString($schedule->start_time)->format('h:i A'),
                'end'             => Carbon::createFromTimeString($schedule->end_time)->format('h:i A'),
                'max_appointments'=> $schedule->max_appointments,
            ],
        ]);
    }
    // ═══════════════════════════════════════════
    // STORE — Appointment Save → Payment Redirect
    // ═══════════════════════════════════════════
    public function store(Request $request, $doctorId)
    {
        $request->validate([
            'workplace_id'     => 'required|exists:doctor_workplaces,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason'           => 'required|string|max:1000',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient) {
            return redirect()->back()
                ->with('error', 'Patient profile not found. Please complete your profile first.');
        }

        $doctor    = Doctor::findOrFail($doctorId);
        $workplace = DoctorWorkplace::findOrFail($request->workplace_id);

        $appointmentNumber = 'APT-' . strtoupper(uniqid());

        $appointment = Appointment::create([
            'appointment_number'  => $appointmentNumber,
            'patient_id'          => $patient->id,
            'doctor_id'           => $doctorId,
            'doctor_workplace_id' => $request->workplace_id,
            'workplace_type'      => $workplace->workplace_type,
            'workplace_id'        => $workplace->workplace_id ?? null,
            'appointment_date'    => $request->appointment_date,
            'appointment_time'    => $request->appointment_time,
            'reason'              => $request->reason,
            'notes'               => $request->notes,
            'status'              => 'pending',
            'consultation_fee'    => $doctor->consultation_fee ?? 0,
            'payment_status'      => 'unpaid',
            'appointment_type'    => 'consultation',
        ]);

        return redirect()->route('patient.appointments.payment', $appointment->id)
            ->with('success', 'Appointment details saved! Please complete payment.');
    }

    // ═══════════════════════════════════════════
    // PAYMENT PAGE — Show Payment Form
    // ═══════════════════════════════════════════
    public function payment($appointmentId)
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient profile not found.');
        }

        $appointment = Appointment::with(['doctor', 'doctor.user'])
            ->where('patient_id', $patient->id)
            ->findOrFail($appointmentId);

        if ($appointment->payment_status === 'paid') {
            return redirect()->route('patient.appointments.index')
                ->with('info', 'This appointment is already paid.');
        }

        $workplace = null;
        if ($appointment->doctor_workplace_id) {
            $workplace = DoctorWorkplace::with(['hospital', 'medicalCentre'])
                ->find($appointment->doctor_workplace_id);
        }

        $stripeKey = config('services.stripe.key');

        return view('patient.appointment-payment',
            compact('appointment', 'workplace', 'stripeKey'));
    }

    // ═══════════════════════════════════════════
    // PAY — Stripe Payment Process
    // ═══════════════════════════════════════════
    public function pay(Request $request, $appointmentId)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient) {
            return redirect()->route('patient.appointments.index')
                ->with('error', 'Patient profile not found.');
        }

        $appointment = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->findOrFail($appointmentId);

        if ($appointment->payment_status === 'paid') {
            return redirect()->route('patient.appointments.index')
                ->with('info', 'This appointment is already paid.');
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $amount = (int) round(($appointment->consultation_fee ?? 0) * 100);

            if ($amount < 100) {
                return redirect()->route('patient.appointments.payment', $appointmentId)
                    ->with('error', 'Invalid amount. Minimum payment is Rs. 1.00');
            }

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount'              => $amount,
                'currency'            => 'lkr',
                'payment_method'      => $request->payment_method_id,
                'confirmation_method' => 'automatic',
                'confirm'             => true,
                'return_url'          => route('patient.appointments.index'),
                'description'         => 'HealthNet Appointment #' . $appointmentId,
                'metadata'            => [
                    'appointment_id' => $appointmentId,
                    'patient_id'     => $patient->id,
                ],
            ]);

            if ($paymentIntent->status === 'succeeded') {
                $this->markAppointmentPaid(
                    $appointment,
                    $paymentIntent->id,
                    $patient->id,
                    $request->cardholder_name ?? null
                );

                return redirect()->route('patient.appointments.index')
                    ->with('success', '✅ Payment successful! Your appointment is confirmed.');
            }

            if ($paymentIntent->status === 'requires_action'
                && $paymentIntent->next_action
                && $paymentIntent->next_action->type === 'redirect_to_url') {

                session(['pending_payment_intent' => $paymentIntent->id]);
                session(['pending_appointment_id' => $appointmentId]);

                return redirect()->away(
                    $paymentIntent->next_action->redirect_to_url->url
                );
            }

            if ($paymentIntent->status === 'requires_payment_method') {
                return redirect()->route('patient.appointments.payment', $appointmentId)
                    ->with('error', '💳 Card was declined. Please try a different card.');
            }

            return redirect()->route('patient.appointments.payment', $appointmentId)
                ->with('error', 'Payment unsuccessful. Status: ' . $paymentIntent->status);

        } catch (\Stripe\Exception\CardException $e) {
            Log::warning('Stripe Card Declined: ' . $e->getMessage());
            return redirect()->route('patient.appointments.payment', $appointmentId)
                ->with('error', '💳 ' . $e->getUserMessage());

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error('Stripe Invalid Request: ' . $e->getMessage());

            if (str_contains($e->getMessage(), 'currency')) {
                return redirect()->route('patient.appointments.payment', $appointmentId)
                    ->with('error', 'LKR currency issue. Please contact support.');
            }

            return redirect()->route('patient.appointments.payment', $appointmentId)
                ->with('error', 'Invalid payment request: ' . $e->getMessage());

        } catch (\Stripe\Exception\AuthenticationException $e) {
            Log::error('Stripe Auth Error: ' . $e->getMessage());
            return redirect()->route('patient.appointments.payment', $appointmentId)
                ->with('error', '⚙️ Payment service error. Please check API keys.');

        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            return redirect()->route('patient.appointments.payment', $appointmentId)
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════
    // PAYMENT CALLBACK — 3DS redirect return
    // ═══════════════════════════════════════════
    public function paymentCallback(Request $request)
    {
        $appointmentId = session('pending_appointment_id');
        $intentId      = session('pending_payment_intent');

        if (!$appointmentId || !$intentId) {
            return redirect()->route('patient.appointments.index')
                ->with('error', 'Payment session expired. Please try again.');
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = \Stripe\PaymentIntent::retrieve($intentId);
            $patient       = Patient::where('user_id', Auth::id())->first();
            $appointment   = Appointment::where('patient_id', $patient->id)
                                ->findOrFail($appointmentId);

            if ($paymentIntent->status === 'succeeded') {
                $this->markAppointmentPaid(
                    $appointment,
                    $intentId,
                    $patient->id,
                    null
                );

                session()->forget(['pending_payment_intent', 'pending_appointment_id']);

                return redirect()->route('patient.appointments.index')
                    ->with('success', '✅ Payment successful! Your appointment is confirmed.');
            }

            return redirect()->route('patient.appointments.payment', $appointmentId)
                ->with('error', 'Payment authentication failed. Please try again.');

        } catch (\Exception $e) {
            Log::error('Payment Callback Error: ' . $e->getMessage());
            return redirect()->route('patient.appointments.index')
                ->with('error', 'Payment verification failed.');
        }
    }

    // ═══════════════════════════════════════════
    // HELPER — Mark Appointment Paid + payments table insert
    // ═══════════════════════════════════════════
    private function markAppointmentPaid(
        Appointment $appointment,
        string $transactionId,
        int $patientId,
        ?string $cardholderName
    ): void {
        DB::transaction(function () use ($appointment, $transactionId, $patientId, $cardholderName) {

            // Appointment update — pending
            $appointment->update([
                'payment_status' => 'paid',
                'payment_method' => 'card',
                'status'         => 'pending',
            ]);

            //  2. Payment record insert
            $paymentNumber = 'PAY-' . strtoupper(uniqid());

            DB::table('payments')->insert([
                'payment_number' => $paymentNumber,
                'payer_id'       => $patientId,
                'payee_type'     => 'doctor',
                'payee_id'       => $appointment->doctor_id,
                'related_type'   => 'appointment',
                'related_id'     => $appointment->id,
                'amount'         => $appointment->consultation_fee ?? 0,
                'payment_method' => 'card',
                'payment_status' => 'completed',
                'transaction_id' => $transactionId,
                'payment_date'   => now()->toDateString(),
                'notes'          => $cardholderName
                                    ? 'Cardholder: ' . $cardholderName
                                    : 'Online card payment via Stripe',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // ── Load fresh relationships ──
            $appointment->load(['doctor', 'patient', 'doctor.user', 'patient.user']);

            $doctor    = $appointment->doctor;
            $patient   = $appointment->patient;
            $workplace = null;
            $providerName = 'HealthNet Clinic';

            if ($appointment->doctor_workplace_id) {
                $workplace = DoctorWorkplace::with(['hospital', 'medicalCentre'])
                    ->find($appointment->doctor_workplace_id);

                if ($workplace) {
                    if ($workplace->workplace_type === 'hospital' && $workplace->hospital) {
                        $providerName = $workplace->hospital->name;
                    } elseif ($workplace->workplace_type === 'medical_centre' && $workplace->medicalCentre) {
                        $providerName = $workplace->medicalCentre->name;
                    } elseif ($workplace->workplace_type === 'private') {
                        $providerName = 'Private Practice – Dr. ' . ($doctor->first_name ?? '');
                    }
                }
            }

            // ✅ 3. Patient — Notification + Email
            try {
                // In-app notification
                DB::table('notifications')->insert([
                    'notifiable_type' => \App\Models\User::class,
                    'notifiable_id'   => $patient->user_id,
                    'type'            => 'payment',
                    'title'           => '✅ Payment Successful',
                    'message'         => 'Your payment of Rs. ' . number_format($appointment->consultation_fee ?? 0, 2)
                                    . ' for appointment #' . $appointment->appointment_number
                                    . ' with Dr. ' . ($doctor->first_name ?? '') . ' ' . ($doctor->last_name ?? '')
                                    . ' at ' . $providerName . ' has been received.'
                                    . ' Appointment is pending doctor confirmation.',
                    'related_type'    => 'appointment',
                    'related_id'      => $appointment->id,
                    'is_read'         => false,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                // Email
                if ($patient->user && $patient->user->email) {
                    $patient->user->notify(
                        new \App\Notifications\AppointmentPaymentNotification(
                            $appointment, 'patient', $providerName, $paymentNumber
                        )
                    );
                }
            } catch (\Exception $e) {
                Log::warning('Patient notification error: ' . $e->getMessage());
            }

            // ✅ 4. Doctor — Notification + Email
            try {
                if ($doctor && $doctor->user_id) {
                    DB::table('notifications')->insert([
                        'notifiable_type' => \App\Models\User::class,
                        'notifiable_id'   => $doctor->user_id,
                        'type'            => 'payment',
                        'title'           => '🔔 New Paid Appointment',
                        'message'         => 'Patient ' . ($patient->first_name ?? '') . ' ' . ($patient->last_name ?? '')
                                        . ' has paid Rs. ' . number_format($appointment->consultation_fee ?? 0, 2)
                                        . ' for appointment #' . $appointment->appointment_number
                                        . ' on ' . Carbon::parse($appointment->appointment_date)->format('d M Y')
                                        . ' at ' . Carbon::parse($appointment->appointment_time)->format('h:i A')
                                        . '. Please confirm the appointment.',
                        'related_type'    => 'appointment',
                        'related_id'      => $appointment->id,
                        'is_read'         => false,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);

                    if ($doctor->user && $doctor->user->email) {
                        $doctor->user->notify(
                            new \App\Notifications\AppointmentPaymentNotification(
                                $appointment, 'doctor', $providerName, $paymentNumber
                            )
                        );
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Doctor notification error: ' . $e->getMessage());
            }

            // ✅ 5. Provider (Hospital / Medical Centre) — Notification + Email
            try {
                if ($workplace) {
                    $providerUserId = null;
                    $providerEmail  = null;

                    if ($workplace->workplace_type === 'hospital' && $workplace->hospital) {
                        $providerUserId = $workplace->hospital->user_id;
                        $providerEmail  = $workplace->hospital->user->email ?? null;
                    } elseif ($workplace->workplace_type === 'medical_centre' && $workplace->medicalCentre) {
                        $providerUserId = $workplace->medicalCentre->user_id;
                        $providerEmail  = $workplace->medicalCentre->user->email ?? null;
                    }

                    if ($providerUserId) {
                        DB::table('notifications')->insert([
                            'notifiable_type' => \App\Models\User::class,
                            'notifiable_id'   => $providerUserId,
                            'type'            => 'payment',
                            'title'           => '🏥 New Appointment Booked',
                            'message'         => 'Appointment #' . $appointment->appointment_number
                                            . ' booked at ' . $providerName
                                            . ' with Dr. ' . ($doctor->first_name ?? '') . ' ' . ($doctor->last_name ?? '')
                                            . ' for patient ' . ($patient->first_name ?? '') . ' ' . ($patient->last_name ?? '')
                                            . ' on ' .Carbon::parse($appointment->appointment_date)->format('d M Y')
                                            . '. Payment Rs. ' . number_format($appointment->consultation_fee ?? 0, 2) . ' received.',
                            'related_type'    => 'appointment',
                            'related_id'      => $appointment->id,
                            'is_read'         => false,
                            'created_at'      => now(),
                            'updated_at'      => now(),
                        ]);
                    }

                    // Provider email
                    if ($providerEmail) {
                        $providerUser = \App\Models\User::where('id', $providerUserId)->first();
                        if ($providerUser) {
                            $providerUser->notify(
                                new \App\Notifications\AppointmentPaymentNotification(
                                    $appointment, 'provider', $providerName, $paymentNumber
                                )
                            );
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Provider notification error: ' . $e->getMessage());
            }
        });
    }




    // ═══════════════════════════════════════════
    // INDEX — My Appointments List
    // ═══════════════════════════════════════════
    public function index(Request $request)
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient profile not found.');
        }

        $query = Appointment::with(['doctor.user', 'hospital', 'medicalCentre'])
            ->where('patient_id', $patient->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }

        $appointments = $query
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(8);

        $baseQuery    = Appointment::where('patient_id', $patient->id);
        $statusCounts = [
            'pending'   => (clone $baseQuery)->where('status', 'pending')->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        return view('patient.appointments', compact('appointments', 'statusCounts'));
    }

    // ═══════════════════════════════════════════
    // CANCEL — Appointment Cancel
    // ═══════════════════════════════════════════
    public function cancel(Request $request, $appointmentId)
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient) {
            return redirect()->route('patient.appointments.index')
                ->with('error', 'Patient profile not found.');
        }

        $appointment = Appointment::where('patient_id', $patient->id)
            ->findOrFail($appointmentId);

        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return redirect()->route('patient.appointments.index')
                ->with('error', 'This appointment cannot be cancelled.');
        }

        $appointment->update([
            'status'              => 'cancelled',
            'cancelled_by'        => Auth::id(),
            'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by patient',
            'cancelled_at'        => now(),
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment cancelled successfully.');
    }
}
