<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HealthNetFeatureTest extends TestCase
{
    /**
     * AT01 - Homepage access test
     */
    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * AT02 - Login page access test
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * AT03 - Register page access test
     */
    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * AT04 - Forgot password page access test
     */
    public function test_forgot_password_page_is_accessible(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    /**
     * AT05 - Guest cannot access admin dashboard
     */
    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(302);
    }

    /**
     * AT06 - Guest cannot access admin appointments
     */
    public function test_guest_cannot_access_admin_appointments(): void
    {
        $response = $this->get('/admin/appointments');
        $response->assertStatus(302);
    }

    /**
     * AT07 - Guest cannot access admin chatbot page
     */
    public function test_guest_cannot_access_admin_chatbot(): void
    {
        $response = $this->get('/admin/chatbot');
        $response->assertStatus(302);
    }

    /**
     * AT08 - Guest cannot access admin notifications page
     */
    public function test_guest_cannot_access_admin_notifications_page(): void
    {
        $response = $this->get('/admin/notifications');
        $response->assertStatus(302);
    }

    /**
     * AT09 - Guest cannot access admin profile page
     */
    public function test_guest_cannot_access_admin_profile_page(): void
    {
        $response = $this->get('/admin/profile');
        $response->assertStatus(302);
    }

    /**
     * AT10 - Guest cannot access admin settings page
     */
    public function test_guest_cannot_access_admin_settings_page(): void
    {
        $response = $this->get('/admin/settings');
        $response->assertStatus(302);
    }

    /**
     * AT11 - Guest cannot access admin reports page
     */
    public function test_guest_cannot_access_admin_reports_page(): void
    {
        $response = $this->get('/admin/reports');
        $response->assertStatus(302);
    }

    /**
     * AT12 - Guest cannot access admin announcements page
     */
    public function test_guest_cannot_access_admin_announcements_page(): void
    {
        $response = $this->get('/admin/announcements');
        $response->assertStatus(302);
    }

    /**
     * AT13 - Guest cannot access admin users page
     */
    public function test_guest_cannot_access_admin_users_page(): void
    {
        $response = $this->get('/admin/users');
        $response->assertStatus(302);
    }

    /**
     * AT14 - Guest cannot access admin doctors page
     */
    public function test_guest_cannot_access_admin_doctors_page(): void
    {
        $response = $this->get('/admin/doctors');
        $response->assertStatus(302);
    }

    /**
     * AT15 - Guest cannot access patient profile page
     */
    public function test_guest_cannot_access_patient_profile_page(): void
    {
        $response = $this->get('/patient/profile');
        $response->assertStatus(302);
    }

    /**
     * AT16 - Guest cannot access patient health portfolio page
     */
    public function test_guest_cannot_access_patient_health_portfolio_page(): void
    {
        $response = $this->get('/patient/health-portfolio');
        $response->assertStatus(302);
    }

    /**
     * AT17 - Guest cannot access patient notifications page
     */
    public function test_guest_cannot_access_patient_notifications_page(): void
    {
        $response = $this->get('/patient/notifications');
        $response->assertStatus(302);
    }

    /**
     * AT18 - Guest cannot access patient appointments page
     */
    public function test_guest_cannot_access_patient_appointments_page(): void
    {
        $response = $this->get('/patient/appointments');
        $response->assertStatus(302);
    }

    /**
     * AT19 - Guest cannot access patient doctors page
     */
    public function test_guest_cannot_access_patient_doctors_page(): void
    {
        $response = $this->get('/patient/doctors');
        $response->assertStatus(302);
    }

    /**
     * AT20 - Guest cannot access patient laboratories page
     */
    public function test_guest_cannot_access_patient_laboratories_page(): void
    {
        $response = $this->get('/patient/laboratories');
        $response->assertStatus(302);
    }

    /**
     * AT21 - Guest cannot access patient pharmacies page
     */
    public function test_guest_cannot_access_patient_pharmacies_page(): void
    {
        $response = $this->get('/patient/pharmacies');
        $response->assertStatus(302);
    }

    /**
     * AT22 - Guest cannot access doctor dashboard
     */
    public function test_guest_cannot_access_doctor_dashboard(): void
    {
        $response = $this->get('/doctor/dashboard');
        $response->assertStatus(302);
    }

    /**
     * AT23 - Guest cannot access doctor appointments page
     */
    public function test_guest_cannot_access_doctor_appointments_page(): void
    {
        $response = $this->get('/doctor/appointments');
        $response->assertStatus(302);
    }

    /**
     * AT24 - Guest cannot access laboratory dashboard
     */
    public function test_guest_cannot_access_laboratory_dashboard(): void
    {
        $response = $this->get('/laboratory/dashboard');
        $response->assertStatus(302);
    }

    /**
     * AT25 - Guest cannot access laboratory orders page
     */
    public function test_guest_cannot_access_laboratory_orders_page(): void
    {
        $response = $this->get('/laboratory/orders');
        $response->assertStatus(302);
    }

    /**
     * AT26 - Guest cannot access pharmacy dashboard
     */
    public function test_guest_cannot_access_pharmacy_dashboard(): void
    {
        $response = $this->get('/pharmacy/dashboard');
        $response->assertStatus(302);
    }

    /**
     * AT27 - Guest cannot access pharmacy medicines page
     */
    public function test_guest_cannot_access_pharmacy_medicines_page(): void
    {
        $response = $this->get('/pharmacy/medicines');
        $response->assertStatus(302);
    }

    /**
     * AT28 - Guest cannot access hospital dashboard
     */
    public function test_guest_cannot_access_hospital_dashboard(): void
    {
        $response = $this->get('/hospital/dashboard');
        $response->assertStatus(302);
    }

    /**
     * AT29 - Guest cannot access medical centre dashboard
     */
    public function test_guest_cannot_access_medical_centre_dashboard(): void
    {
        $response = $this->get('/medical-centre/dashboard');
        $response->assertStatus(302);
    }

    /**
     * AT30 - Guest cannot access medical centre appointments page
     */
    public function test_guest_cannot_access_medical_centre_appointments_page(): void
    {
        $response = $this->get('/medical-centre/appointments');
        $response->assertStatus(302);
    }
}
