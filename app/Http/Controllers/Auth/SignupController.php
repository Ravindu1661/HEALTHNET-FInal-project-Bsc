<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientSignupRequest;
use App\Http\Requests\ProviderSignupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Laboratory;
use App\Models\Pharmacy;
use App\Models\MedicalCentre;
use App\Services\NotificationService;

class SignupController extends Controller
{
    /**
     * Register a new patient
     */
    public function registerPatient(PatientSignupRequest $request)
    {
        try {
            $data = $request->validated();

            return DB::transaction(function() use ($data) {
                // Create user
                $user = User::create([
                    'email'             => $data['email'],
                    'password'          => Hash::make($data['password']),
                    'user_type'         => 'patient',
                    'status'            => 'active',
                    'email_verified_at' => null,
                ]);

                // Create patient profile
                DB::table('patients')->insert([
                    'user_id'                 => $user->id,
                    'first_name'              => $data['first_name'],
                    'last_name'               => $data['last_name'],
                    'nic'                     => $data['nic'] ?? null,
                    'date_of_birth'           => $data['date_of_birth'],
                    'gender'                  => $data['gender'],
                    'blood_group'             => $data['blood_group'] ?? null,
                    'phone'                   => $data['phone'],
                    'address'                 => $data['address'],
                    'city'                    => $data['city'],
                    'province'                => $data['province'],
                    'postal_code'             => $data['postal_code'] ?? null,
                    'emergency_contact_name'  => $data['emergency_contact_name'] ?? null,
                    'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                ]);

                // ✅ Send welcome notification
                NotificationService::sendWelcomeNotification($user);

                // Send verification email in background
                event(new Registered($user));

                // ✅ Send verification email notification
                NotificationService::sendVerificationSentNotification($user);

                // Auto login
                Auth::login($user);

                // ✅ Redirect directly to dashboard with success message
                return response()->json([
                    'ok'              => true,
                    'message'         => 'Registration successful!',
                    'welcome_message' => 'Verification email sent to ' . $user->email,
                    'user_name'       => $data['first_name'] . ' ' . $data['last_name'],
                    'redirect'        => route('patient.dashboard'),
                    'show_email_sent_message' => true,
                ], 200);
            });

        } catch (\Exception $e) {
            \Log::error('Patient Registration Error: ' . $e->getMessage());

            return response()->json([
                'ok'      => false,
                'message' => 'Registration failed. Please try again.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register a new provider
     */
    public function registerProvider(ProviderSignupRequest $request)
    {
        try {
            $payload = $request->validated();
            $type = $payload['provider_type'];

            $documentPath = null;
            $profileImagePath = null;
            $deliveryAvailable = $request->has('delivery_available');

            return DB::transaction(function () use (
                $request,
                $payload,
                $type,
                &$documentPath,
                &$profileImagePath,
                $deliveryAvailable
            ) {
                // Create user
                $user = User::create([
                    'email'             => $payload['email'],
                    'password'          => Hash::make($payload['password']),
                    'user_type'         => $type,
                    'status'            => 'pending',
                    'email_verified_at' => null,
                ]);

                // ✅ Send welcome notification
                NotificationService::sendWelcomeNotification($user);

                // File uploads
                if ($request->hasFile('document')) {
                    $documentPath = $request->file('document')->store($type . 's/documents', 'public');
                }

                if ($request->hasFile('profile_image')) {
                    $profileImagePath = $request->file('profile_image')->store($type . 's/profiles', 'public');
                }

                $userName = '';
                $dashboardRoute = '';

                switch ($type) {
                    case 'doctor':
                        Doctor::create([
                            'user_id'          => $user->id,
                            'status'           => 'pending',
                            'slmc_number'      => $payload['slmc_number'],
                            'first_name'       => $payload['first_name'],
                            'last_name'        => $payload['last_name'],
                            'specialization'   => $payload['specialization'] ?? null,
                            'qualifications'   => $payload['qualifications'] ?? null,
                            'experience_years' => $payload['experience_years'] ?? null,
                            'phone'            => $payload['phone'],
                            'consultation_fee' => $payload['consultation_fee'] ?? null,
                            'bio'              => $payload['bio'] ?? null,
                            'profile_image'    => $profileImagePath,
                            'document_path'    => $documentPath,
                        ]);
                        $userName = $payload['first_name'] . ' ' . $payload['last_name'];
                        $dashboardRoute = route('doctor.dashboard');
                        break;

                    case 'hospital':
                        Hospital::create([
                            'user_id'             => $user->id,
                            'status'              => 'pending',
                            'name'                => $payload['name'],
                            'type'                => $payload['type'],
                            'registration_number' => $payload['registration_number'],
                            'phone'               => $payload['phone'],
                            'email'               => $payload['email'],
                            'address'             => $payload['address'],
                            'city'                => $payload['city'],
                            'province'            => $payload['province'],
                            'postal_code'         => $payload['postal_code'] ?? null,
                            'specializations'     => isset($payload['specializations'])
                                ? json_encode(array_map('trim', explode(',', $payload['specializations'])))
                                : null,
                            'facilities'          => isset($payload['facilities'])
                                ? json_encode(array_map('trim', explode(',', $payload['facilities'])))
                                : null,
                            'operating_hours'     => $payload['operating_hours'] ?? null,
                            'description'         => $payload['description'] ?? null,
                            'website'             => $payload['website'] ?? null,
                            'profile_image'       => $profileImagePath,
                            'document_path'       => $documentPath,
                        ]);
                        $userName = $payload['name'];
                        $dashboardRoute = route('hospital.dashboard');
                        break;

                    case 'laboratory':
                        Laboratory::create([
                            'user_id'             => $user->id,
                            'status'              => 'pending',
                            'name'                => $payload['name'],
                            'registration_number' => $payload['registration_number'],
                            'phone'               => $payload['phone'],
                            'email'               => $payload['email'],
                            'address'             => $payload['address'],
                            'city'                => $payload['city'],
                            'province'            => $payload['province'],
                            'postal_code'         => $payload['postal_code'] ?? null,
                            'services'            => isset($payload['services'])
                                ? json_encode(array_map('trim', explode(',', $payload['services'])))
                                : null,
                            'operating_hours'     => $payload['operating_hours'] ?? null,
                            'description'         => $payload['description'] ?? null,
                            'profile_image'       => $profileImagePath,
                            'document_path'       => $documentPath,
                        ]);
                        $userName = $payload['name'];
                        $dashboardRoute = route('laboratory.dashboard');
                        break;

                    case 'pharmacy':
                        Pharmacy::create([
                            'user_id'             => $user->id,
                            'status'              => 'pending',
                            'name'                => $payload['name'],
                            'registration_number' => $payload['registration_number'],
                            'pharmacist_name'     => $payload['pharmacist_name'] ?? null,
                            'pharmacist_license'  => $payload['pharmacist_license'] ?? null,
                            'phone'               => $payload['phone'],
                            'email'               => $payload['email'],
                            'address'             => $payload['address'],
                            'city'                => $payload['city'],
                            'province'            => $payload['province'],
                            'postal_code'         => $payload['postal_code'] ?? null,
                            'operating_hours'     => $payload['operating_hours'] ?? null,
                            'delivery_available'  => $deliveryAvailable,
                            'profile_image'       => $profileImagePath,
                            'document_path'       => $documentPath,
                        ]);
                        $userName = $payload['name'];
                        $dashboardRoute = route('pharmacy.dashboard');
                        break;

                    case 'medical_centre':
                        MedicalCentre::create([
                            'user_id'             => $user->id,
                            'status'              => 'pending',
                            'name'                => $payload['name'],
                            'registration_number' => $payload['registration_number'],
                            'phone'               => $payload['phone'],
                            'email'               => $payload['email'],
                            'address'             => $payload['address'],
                            'city'                => $payload['city'],
                            'province'            => $payload['province'],
                            'postal_code'         => $payload['postal_code'] ?? null,
                            'specializations'     => isset($payload['specializations'])
                                ? json_encode(array_map('trim', explode(',', $payload['specializations'])))
                                : null,
                            'facilities'          => isset($payload['facilities'])
                                ? json_encode(array_map('trim', explode(',', $payload['facilities'])))
                                : null,
                            'operating_hours'     => $payload['operating_hours'] ?? null,
                            'description'         => $payload['description'] ?? null,
                            'profile_image'       => $profileImagePath,
                            'document_path'       => $documentPath,
                        ]);
                        $userName = $payload['name'];
                        $dashboardRoute = route('medical_centre.dashboard');
                        break;

                    default:
                        throw new \Exception('Invalid provider type');
                }

                // Send verification email
                event(new Registered($user));

                // ✅ Send verification email notification
                NotificationService::sendVerificationSentNotification($user);

                // Auto-login
                Auth::login($user);

                // ✅ Redirect directly to dashboard
                return response()->json([
                    'ok'              => true,
                    'message'         => ucfirst($type) . ' registration successful!',
                    'welcome_message' => 'Verification email sent. Pending admin approval.',
                    'user_name'       => $userName,
                    'redirect'        => $dashboardRoute,
                    'show_email_sent_message' => true,
                ], 200);
            });

        } catch (\Exception $e) {
            \Log::error('Provider Registration Error: ' . $e->getMessage());

            return response()->json([
                'ok'      => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
