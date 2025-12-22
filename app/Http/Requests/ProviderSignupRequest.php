<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProviderSignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $type = $this->input('provider_type');

        // Base rules for all provider types
        $baseRules = [
            'provider_type' => ['required', Rule::in(['doctor', 'hospital', 'laboratory', 'pharmacy', 'medical_centre'])],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'confirmed', 'min:8'],
            'profile_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'document'      => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];

        // Provider-specific validation rules
        $providerRules = [
            'doctor' => [
                'slmc_number'       => ['required', 'string', 'max:50', 'unique:doctors,slmc_number'],
                'first_name'        => ['required', 'string', 'max:100'],
                'last_name'         => ['required', 'string', 'max:100'],
                'specialization'    => ['required', 'string', 'max:100'],
                'qualifications'    => ['required', 'string'],
                'experience_years'  => ['required', 'integer', 'min:0', 'max:60'],
                'phone'             => ['required', 'string', 'max:20'],
                'consultation_fee'  => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
                'bio'               => ['nullable', 'string', 'max:1000'],
            ],

            'hospital' => [
                'name'                => ['required', 'string', 'max:255'],
                'type'                => ['required', Rule::in(['government', 'private'])],
                'registration_number' => ['required', 'string', 'max:100', 'unique:hospitals,registration_number'],
                'phone'               => ['required', 'string', 'max:20'],
                'email'               => ['required', 'email', 'max:255'],
                'address'             => ['required', 'string'],
                'city'                => ['required', 'string', 'max:100'],
                'province'            => ['required', 'string', 'max:100'],
                'postal_code'         => ['nullable', 'string', 'max:10'],
                'specializations'     => ['required', 'string'],
                'facilities'          => ['required', 'string'],
                'operating_hours'     => ['nullable', 'string'],
                'description'         => ['nullable', 'string'],
                'website'             => ['nullable', 'url', 'max:255'],
            ],

            'laboratory' => [
                'name'                => ['required', 'string', 'max:255'],
                'registration_number' => ['required', 'string', 'max:100', 'unique:laboratories,registration_number'],
                'phone'               => ['required', 'string', 'max:20'],
                'email'               => ['required', 'email', 'max:255'],
                'address'             => ['required', 'string'],
                'city'                => ['required', 'string', 'max:100'],
                'province'            => ['required', 'string', 'max:100'],
                'postal_code'         => ['nullable', 'string', 'max:10'],
                'services'            => ['required', 'string'],
                'operating_hours'     => ['nullable', 'string'],
                'description'         => ['nullable', 'string'],
            ],

            'pharmacy' => [
                'name'                => ['required', 'string', 'max:255'],
                'registration_number' => ['required', 'string', 'max:100', 'unique:pharmacies,registration_number'],
                'pharmacist_name'     => ['required', 'string', 'max:100'],
                'pharmacist_license'  => ['required', 'string', 'max:100'],
                'phone'               => ['required', 'string', 'max:20'],
                'email'               => ['required', 'email', 'max:255'],
                'address'             => ['required', 'string'],
                'city'                => ['required', 'string', 'max:100'],
                'province'            => ['required', 'string', 'max:100'],
                'postal_code'         => ['nullable', 'string', 'max:10'],
                'operating_hours'     => ['nullable', 'string'],
                'delivery_available'  => ['nullable', 'boolean'],
            ],

            'medical_centre' => [
                'name'                => ['required', 'string', 'max:255'],
                'registration_number' => ['required', 'string', 'max:100', 'unique:medical_centres,registration_number'],
                'phone'               => ['required', 'string', 'max:20'],
                'email'               => ['required', 'email', 'max:255'],
                'address'             => ['required', 'string'],
                'city'                => ['required', 'string', 'max:100'],
                'province'            => ['required', 'string', 'max:100'],
                'postal_code'         => ['nullable', 'string', 'max:10'],
                'specializations'     => ['required', 'string'],
                'facilities'          => ['required', 'string'],
                'operating_hours'     => ['nullable', 'string'],
                'description'         => ['nullable', 'string'],
            ],
        ];

        // Merge base rules with provider-specific rules
        return array_merge($baseRules, $providerRules[$type] ?? []);
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            // Base validation messages
            'provider_type.required'       => 'Provider type is required.',
            'provider_type.in'             => 'Invalid provider type selected.',
            'email.required'               => 'Email address is required.',
            'email.email'                  => 'Please enter a valid email address.',
            'email.unique'                 => 'This email is already registered.',
            'password.required'            => 'Password is required.',
            'password.min'                 => 'Password must be at least 8 characters.',
            'password.confirmed'           => 'Password confirmation does not match.',
            'document.required'            => 'Registration document is required.',
            'document.mimes'               => 'Document must be a PDF, JPG, JPEG, or PNG file.',
            'document.max'                 => 'Document size must not exceed 5MB.',
            'profile_image.mimes'          => 'Profile image must be a JPG, JPEG, or PNG file.',
            'profile_image.max'            => 'Profile image size must not exceed 5MB.',
            
            // Doctor-specific messages
            'slmc_number.required'         => 'SLMC registration number is required.',
            'slmc_number.unique'           => 'This SLMC number is already registered.',
            'first_name.required'          => 'First name is required.',
            'last_name.required'           => 'Last name is required.',
            'specialization.required'      => 'Specialization is required.',
            'qualifications.required'      => 'Qualifications are required.',
            'experience_years.required'    => 'Years of experience is required.',
            'consultation_fee.numeric'     => 'Consultation fee must be a valid number.',
            
            // Common messages for other provider types
            'name.required'                => 'Name is required.',
            'registration_number.required' => 'Registration number is required.',
            'registration_number.unique'   => 'This registration number is already registered.',
            'phone.required'               => 'Phone number is required.',
            'address.required'             => 'Address is required.',
            'city.required'                => 'City is required.',
            'province.required'            => 'Province is required.',
            'type.required'                => 'Hospital type is required.',
            'type.in'                      => 'Invalid hospital type selected.',
            'specializations.required'     => 'Specializations are required.',
            'facilities.required'          => 'Facilities information is required.',
            'services.required'            => 'Services information is required.',
            'pharmacist_name.required'     => 'Pharmacist name is required.',
            'pharmacist_license.required'  => 'Pharmacist license number is required.',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'slmc_number'           => 'SLMC registration number',
            'first_name'            => 'first name',
            'last_name'             => 'last name',
            'specialization'        => 'specialization',
            'qualifications'        => 'qualifications',
            'experience_years'      => 'years of experience',
            'consultation_fee'      => 'consultation fee',
            'bio'                   => 'professional bio',
            'registration_number'   => 'registration number',
            'phone'                 => 'phone number',
            'email'                 => 'email address',
            'address'               => 'address',
            'city'                  => 'city',
            'province'              => 'province',
            'postal_code'           => 'postal code',
            'type'                  => 'hospital type',
            'specializations'       => 'specializations',
            'facilities'            => 'facilities',
            'operating_hours'       => 'operating hours',
            'services'              => 'services',
            'pharmacist_name'       => 'pharmacist name',
            'pharmacist_license'    => 'pharmacist license',
            'delivery_available'    => 'home delivery availability',
            'description'           => 'description',
            'website'               => 'website URL',
            'document'              => 'registration document',
            'profile_image'         => 'profile image',
        ];
    }
}
